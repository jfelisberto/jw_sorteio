<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->id) {
            return view('dashboard');
        } else {
            $galery = [
                [
                    'url' => asset('galery/campo_society.jpg'),
                    'alt' => 'Campo societe',
                    'active' => 'active'
                ],
                [
                    'url' => asset('galery/campo_societey_arena_gavea.jpg'),
                    'alt' => 'Arena da Gavea',
                    'active' => false
                ],
                [
                    'url' => asset('galery/campo_societey_sao_goncalo_do_rio_abaixo.jpg'),
                    'alt' => 'Arena São Goncalo do Rio Abaixo',
                    'active' => false
                ],
                [
                    'url' => asset('galery/campo_societey_seitoco_moromizato.jpg'),
                    'alt' => 'Arena Seitoco Moromizato',
                    'active' => false
                ],
                [
                    'url' => asset('galery/campo_society_arena_do_pai.jpeg'),
                    'alt' => 'Arena do Pai',
                    'active' => false
                ],
                [
                    'url' => asset('galery/campo_society_montanha_club.jpeg'),
                    'alt' => 'Arena Montanha Club',
                    'active' => false
                ]
            ];
            return view('home', compact(['galery']));
        }
    }
}
