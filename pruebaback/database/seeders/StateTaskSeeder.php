<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('state_task')->insert([
            [
                'name'=>'Pendiente'
            ],
            [
                'name'=>'En progreso'
            ],
            [
                'name'=>'Completada'
            ],
        ]);
    }
}
