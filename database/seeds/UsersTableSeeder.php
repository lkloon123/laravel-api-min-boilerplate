<?php

use App\Model\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'lkloon123@hotmail.com',
            'password' => 'demo123',
            'type' => 'superuser',
            'is_email_validated' => true
        ])
            ->userProfile()
            ->create([
                'full_name' => 'NeoSon'
            ]);
    }
}
