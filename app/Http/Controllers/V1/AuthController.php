<?php

namespace App\Http\Controllers\V1;

use App\Http\Helper\ResponseHelper;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RecoveryPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Mail\RecoveryPasswordEmail;
use App\Mail\RegistrationEmail;
use App\Model\ApiToken;
use App\Model\LoginActivity;
use App\Model\User;
use Auth;
use Carbon\Carbon;
use Hash;
use Mail;
use Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $token = Auth::guard()->attempt($credentials);

        if (!$token) {
            throw new UnauthorizedHttpException('jwt-auth', 'incorrect email or password');
        }

        if (!Auth::user()->is_email_validated) {
            throw new UnauthorizedHttpException('', 'user not activated, please check your email for activation link');
        }

        //log login activity
        LoginActivity::disableAuditing();
        Auth::user()->loginActivities()->create([
            'user_agent' => Request::header('User-Agent'),
            'ip_address' => Request::ip(),
            'login_at' => Carbon::now(),
        ]);

        return ResponseHelper::success([
            'token' => $token,
            'duration' => Auth::guard()->factory()->getTTL() * 60,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        //create user
        $createdUser = User::create(
            $request->only(['email', 'password'])
        );
        $this->validateModel($createdUser);

        //create user profile
        $createdUserProfile = $createdUser->userProfile()->create(
            $request->only(['full_name'])
        );
        $this->validateModel($createdUserProfile);

        $apiToken = ApiToken::generate(ApiToken::$confirmEmail, $createdUser);
        Mail::to($createdUser)
            ->send(new RegistrationEmail($createdUser, $createdUserProfile, $apiToken));

        return ResponseHelper::success([
            'msg' => 'registration success, please verify your email before login',
        ], 201);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        //search user through email
        $user = User::whereEmail($request->get('email'))->first();
        $this->validateUser($user);

        if ($user->is_email_validated) {
            throw new AccessDeniedHttpException('user already activated');
        }

        /** @var ApiToken $apiToken */
        $apiToken = $user->apiTokens
            ->where('type', '=', ApiToken::$confirmEmail)
            ->sortByDesc('created_at')
            ->first();

        if ($apiToken === null) {
            throw new AccessDeniedHttpException('no verification process for user');
        }

        if (!$apiToken->validateAndClaim($request->get('confirm_token'))) {
            throw new UnauthorizedHttpException('api-token', 'unauthorized token');
        }

        $user->update([
            'is_email_validated' => true
        ]);

        return ResponseHelper::success([
            'msg' => 'email has been successfully verified'
        ]);
    }

    public function recoveryPassword(RecoveryPasswordRequest $request)
    {
        $user = User::whereEmail($request->get('email'))->first();
        $this->validateUser($user);

        if (!$user->is_email_validated) {
            throw new AccessDeniedHttpException('user not activated');
        }

        $token = ApiToken::generate(ApiToken::$resetPassword, $user);
        Mail::to($user)
            ->send(new RecoveryPasswordEmail($user, $token));

        return ResponseHelper::success([
            'msg' => 'reset password email has been sent'
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::whereEmail($request->get('email'))->first();
        $this->validateUser($user);

        /** @var ApiToken $apiToken */
        $apiToken = $user->apiTokens
            ->where('type', '=', ApiToken::$resetPassword)
            ->sortByDesc('created_at')
            ->first();

        if ($apiToken === null) {
            throw new AccessDeniedHttpException('no reset password request for user');
        }

        if (!$apiToken->validateAndClaim($request->get('reset_password_token'))) {
            throw new UnauthorizedHttpException('api-token', 'unauthorized token');
        }

        $user->update([
            'password' => $request->get('password')
        ]);
        $this->validateModel($user);

        return ResponseHelper::success([
            'msg' => 'password successfully reset, please login using your new password',
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var User $user */
        $user = Auth::guard()->user();

        //check for existing password
        if (!Hash::check($request->get('old_password'), $user->password)) {
            throw new UnauthorizedHttpException('password', 'incorrect password');
        }

        $user->update([
            'password' => $request->get('new_password')
        ]);
        $this->validateModel($user);

        $this->logout();

        return ResponseHelper::success([
            'msg' => 'password successfully updated, you will be redirect to login page'
        ]);
    }

    public function logout()
    {
        //log logout activity
        LoginActivity::disableAuditing();
        $lastLoginActivities = Auth::user()
            ->loginActivities
            ->sortByDesc('updated_at')
            ->first();

        if ($lastLoginActivities !== null && $lastLoginActivities->logout_at === null) {
            $lastLoginActivities->update([
                'logout_at' => Carbon::now()
            ]);
        }

        Auth::guard()->logout();

        return ResponseHelper::success([
            'msg' => 'successfully logged out'
        ]);
    }

    public function refresh()
    {
        $token = Auth::guard()->refresh();

        return ResponseHelper::success([
            'token' => $token,
            'duration' => Auth::guard()->factory()->getTTL() * 60,
        ]);
    }

    public function me()
    {
        return ResponseHelper::success(
            new UserResource(Auth::user())
        );
    }
}
