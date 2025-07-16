<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Repositories\Shop\WelcomeRepository;

class WelcomeController extends Controller
{

    protected $welcomeRepository;

    public function __construct(WelcomeRepository $welcomeRepository)
    {
        $this->welcomeRepository = $welcomeRepository;
    }

    public function index()
    {
        //data contiene el retorno de covers y lastProducts
        $data = $this->welcomeRepository->index();

        return view('welcome', $data);
    }
}
