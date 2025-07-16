<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Api\SortRepository;
use Illuminate\Http\Request;

class SortController extends Controller
{
    protected $sortRepository;

    public function __construct(SortRepository $sortRepository)
    {
        $this->sortRepository = $sortRepository;
    }

    public function orderCover(Request $request)
    {
        $this->sortRepository->orderCover($request);
    }
}
