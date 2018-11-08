<?php

use Dingo\Api\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {

    $api->group(['namespace' => 'App\Http\Controllers\V1', 'middleware' => 'bindings'], function (Router $api) {
        $api->group(['prefix' => 'auth'], function (Router $api) {
            $api->post('register', 'AuthController@register');
            $api->post('login', 'AuthController@login');
            $api->post('email/verify', 'AuthController@verifyEmail');

            $api->post('recovery', 'AuthController@recoveryPassword');
            $api->post('reset', 'AuthController@resetPassword');

            $api->post('refresh', 'AuthController@refresh');

            //required token
            $api->group(['middleware' => 'jwt.auth'], function (Router $api) {
                $api->post('password/change', 'AuthController@changePassword');
                $api->post('logout', 'AuthController@logout');
                $api->post('me', 'AuthController@me');
            });
        });
    });

});