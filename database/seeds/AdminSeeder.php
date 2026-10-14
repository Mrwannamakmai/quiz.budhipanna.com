<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        \App\User::updateOrCreate(
            ['email' => 'v-quiz@v-quiz.in'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('V#QuIZ69'),
                'role'     => 'superadmin',
                'email_verified_at' => now(),
            ]
        );

        echo "Superadmin created/updated: v-quiz@v-quiz.in / V#QuIZ69\n";
    }
}
