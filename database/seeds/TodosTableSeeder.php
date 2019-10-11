<?php

use Illuminate\Database\Seeder;

class TodosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 2)->create()->each(function ($user) {
            for ($i = 0; $i < 5; $i++) {
                $user->todos()->save(factory(App\Todo::class)->make());
            }
        });
    }
}
