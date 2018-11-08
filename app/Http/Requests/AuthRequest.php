<?php

namespace App\Http\Requests;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ];
    }
}

class VerifyEmailRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'confirm_token' => 'required'
        ];
    }
}

class RecoveryPasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }
}

class ResetPasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'reset_password_token' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ];
    }
}

class ChangePasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required|same:new_password'
        ];
    }
}