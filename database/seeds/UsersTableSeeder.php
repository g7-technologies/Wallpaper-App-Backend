<?php

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
        DB::table('users')->insert( [
            'name' => 'Wallpapers Rap administrator',
            'email' => 'legal'.env('EMAIL_POSTFIX'),
            'password' => bcrypt('iwantsomeRAP1!'),
            'gender' => 0,
            'role' => \App\User::ROLE_ADMIN,
            //'zodiac' => \App\Helper\ZODIAC_GEMINI,
            //'birthdate' => '1985-05-28',
            'timezone' => 'Europe/Lisbon',
            'push_key' => null,
            'push_time' => null,
            'locale' => 'en',
            'version' => null,
        ] );
    }
}
