# Laravel Api Boilerplate

A simple laravel 5.7 api boilerplate which include very basic function to get started.

## Features

- Api token generator (email verfication, password recovery)
- clockwork debug
- laravel auditing
- error logging to db
- jwt authentication
- laravel ide helper

## Installation

run the following command from terminal

```bash
composer create-project --prefer-dist neoson/laravel-api-min-boilerplate myProject
```

run migration

```bash
php artisan migrate
```

generate jwt secret

```bash
php artisan jwt:generate
```

rename `.env.example` to `.env` and setup your configuration

run the application

```bash
php artisan serve
```

## Usage

### Important Diretories
api route : `route/api.php`   
controller: `app/Http/Controllers/V1`   
model	  : `app/Model`   

This boilerplate provide function to send email

```php
//run this queue worker
php artisan queue:work

//email template directories
resources/views/emails
```

### Api

```php
POST api/auth/register        //register an account and send verification email
POST api/auth/login           //perform login and get jwt token
GET  api/auth/email/verify    //verify email for new account
POST api/auth/recovery        //send a recovery email
GET  api/auth/reset           //reset password after verify recovery email
POST api/auth/refresh         //refresh the jwt token

//the following api require jwt token
//you can include in header(Authorization: Bearer 'token') 
//or as request parameters(token)
POST api/auth/password/change //for changing password
POST api/auth/logout          //logout
POST api/auth/me              //get current authencated user
```

## Built With

- [Laravel](https://laravel.com/)

## License

This project is licensed under the MIT License - [LICENSE](LICENSE)

## Feedback

Your feedback and contribution are very welcome, hope you enjoy it.