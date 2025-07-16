<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\CoverRepository;
use Illuminate\Http\Request;

class CoverController extends BaseAdminController
{
    protected $coverRepository;

    public function __construct(CoverRepository $coverRepository)
    {
        parent::__construct($coverRepository);
        $this->coverRepository = $coverRepository;
    }

    public function index()
    {
        $covers = $this->coverRepository->index();
        return view('admin.covers.index', compact('covers'));
    }

    public function store(Request $request)
    {
        $this->coverRepository->store($request);
        return redirect()->route('admin.covers.create');
    }

    public function update(Request $request, int $id)
    {
        $this->coverRepository->update($request, $id);
        return redirect()->route('admin.covers.edit', $id);
    }

    public function destroy(int $id)
    {
        $this->coverRepository->destroy($id);
        $covers = $this->coverRepository->index();
        return redirect()->route('admin.covers.index')->with('covers', $covers);
    }
}
