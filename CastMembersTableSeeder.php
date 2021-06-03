<?php

use App\Models\CastMembers;
use Illuminate\Database\Seeder;

class CastMembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CastMembers::class, 100)->create();
    }
}
