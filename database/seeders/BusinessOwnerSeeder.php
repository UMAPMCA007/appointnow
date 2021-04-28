<?php

namespace Database\Seeders;

use App\Models\BusinessOwner;
use Illuminate\Database\Seeder;

class BusinessOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessOwner::factory()->create();
    }
}
