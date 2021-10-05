<?php

namespace Database\Seeders;

use \App\Models\Nivel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class NivelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nivels')->insert([
            'name' => 'Pereba'
        ]);
        DB::table('nivels')->insert([
            'name' => 'Ruim'
        ]);
        DB::table('nivels')->insert([
            'name' => 'Normal'
        ]);
        DB::table('nivels')->insert([
            'name' => 'Bom'
        ]);
        DB::table('nivels')->insert([
            'name' => 'Craque'
        ]);
    }
}
