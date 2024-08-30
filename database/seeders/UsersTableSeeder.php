<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Bouncer;
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
//        $users = User::factory(1)->create(
//            [
//                'first_name' => 'Luke',
//                'last_name' => 'Skywalker',
//                'email' => 'luke@jedi.com',
//                'email_verified_at' => null,
//                'password' => bcrypt('123123')
//            ]
//        );
        $user1 = User::updateOrCreate(
            ['email' => 'gak@gmail.com'],
            [
                'name' => 'GAK GAK',
                'email_verified_at' => null,
                'password' => bcrypt('gak@gmail.com')
            ]
        );

//        Bouncer::assign('admin')->to($users->first());
//
//        $others = User::factory(20)->create();
//        foreach ($others as $model) {
//            Bouncer::assign('regular')->to($model);
//        }
    }
}
