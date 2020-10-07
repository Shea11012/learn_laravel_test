<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(\App\Models\User::class)->times(10)->make();
        $now = \Carbon\Carbon::now();
        $data = [];
        foreach ($user as $u) {
            $data[] = [
                'name' => $u->name,
                'email' => $u->email,
                'email_verified_at' => $u->email_verified_at,
                'password' => $u->password,
                'remember_token' => $u->remember_token,
                'created_at' => $now->toDateTimeString(),
                'updated_at' => $now->toDateTimeString(),
            ];
        }

        \App\Models\User::insert($data);
    }
}
