<?php

use App\Ad;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        factory(Ad::class, 25)->create();
    }
}
