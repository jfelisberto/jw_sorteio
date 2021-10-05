<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Juliano Felisberto',
            'email' => 'usuario1@sorteio.com',
            'admin' => 1,
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Julio Cesar',
            'email' => 'usuario2@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Róger Guedes',
            'email' => 'usuario3@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Cássio Ramos',
            'email' => 'usuario4@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'João Victor',
            'email' => 'usuario5@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Fabio Santos',
            'email' => 'usuario6@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Gil',
            'email' => 'usuario7@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Victor Cantillo',
            'email' => 'usuario8@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Renato Augusto',
            'email' => 'usuario9@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Giuliano de Paula',
            'email' => 'usuario10@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Willian Borges',
            'email' => 'usuario11@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Gabriel Pereira',
            'email' => 'usuario12@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Fagner Lemos',
            'email' => 'usuario13@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Gabriel Giroto',
            'email' => 'usuario14@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Roni Medeiros',
            'email' => 'usuario15@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'João Xavier',
            'email' => 'usuario16@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Du Queiroz',
            'email' => 'usuario17@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Raul Gustavo',
            'email' => 'usuario18@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Léo Santos',
            'email' => 'usuario19@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Lucas Pitton',
            'email' => 'usuario20@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Carlos Miguel',
            'email' => 'usuario21@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Matheus Donelli',
            'email' => 'usuario22@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Caique França',
            'email' => 'usuario23@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Anderson Talisca',
            'email' => 'usuario24@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Roberto Carlos',
            'email' => 'usuario25@sorteio.com',
            'created_at' => NOW(),
            'password' => Hash::make('senha@123'),
        ]);
    }
}
