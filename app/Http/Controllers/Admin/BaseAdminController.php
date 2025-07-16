<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\BaseRepository;
use Illuminate\Http\Request;

abstract class BaseAdminController extends Controller
{
    protected $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Muestra el listado del recurso
     */
    public function index()
    {
        return $this->repository->index();
    }

    /**
     * Mustra el formulario para crear un nuevo recurso
     */
    public function create()
    {
        return $this->repository->create();
    }

    /**
     * Almacena el nuevo recurso creado
     */
    public function store(Request $request)
    {
        return $this->repository->store($request);
    }

    /**
     * Muestra un recurso en específico
     */
    public function show(int $id)
    {
        return $this->repository->show($id);
    }

    /**
     * Mustra el formulario para editar un nuevo recurso
     */
    public function edit(int $id)
    {
        return $this->repository->edit($id);
    }

    /**
     * Actualiza un recurso seleccionado en el almacenamiento
     */
    public function update(Request $request, int $id)
    {
        return $this->repository->update($request, $id);
    }

    /**
     * Elimina un recurso seleccionado en el almacenamiento
     */
    public function destroy(int $id)
    {
        return $this->repository->destroy($id);
    }
}
