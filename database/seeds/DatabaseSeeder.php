<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 3)->make([
            'name' => 'test',
            'email' => 'test@test.te',
            'password' => bcrypt('Passw0rd')
        ])->each(function($user, $index) {
            $user->name .= $index;
            $user->email .= $index;
            $user->save();
        });
    }
}
