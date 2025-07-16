<?php

namespace App\Repositories\Admin;

use App\Repositories\Admin\Contracts\RepositoryInterface;
use App\Traits\Admin\loadExtraData;
use App\Traits\Admin\resolvesRequests;
use App\Traits\Admin\sweetAlerts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseRepository implements RepositoryInterface
{
    use loadExtraData;
    use resolvesRequests;
    use sweetAlerts;

    protected $model;
    protected $relationships = [];
    protected $viewName;
    protected $extraData = [];
    protected $relationCheckerDestroy;


    public function __construct(Model $model, string $viewName, array $relationships = [], array $extraData = [])
    {
        $this->model = $model;
        $this->relationships = $relationships;
        $this->viewName = $viewName;
        $this->extraData = $extraData;
    }

    /**
     * Muestra el listado del recurso
     */
    public function index()
    {
        $query = $this->model;

        if(!empty($this->relationships)){
            $query = $query::with($this->relationships);
        }

        $data = $query->paginate();

        return view("admin.{$this->viewName}.index", compact('data'));
    }

    /**
     * Mustra el formulario para crear un nuevo recurso
     */
    public function create()
    {
        if(!empty($this->loadExtraData())){
            $data = $this->loadExtraData();
            return view("admin.{$this->viewName}.create", $data);
        }
        return view("admin.{$this->viewName}.create");
    }

    /**
     * Almacena el nuevo recurso creado
     */
    public function store(Request $request)
    {
        $validated = $this->validateStoreRequest($request);
        $this->model->create($validated);

        $this->alertGenerate1();

        return redirect()->route("admin.{$this->viewName}.create");
    }

    /**
     * Muestra un recurso en específico
     */
    public function show(int $id)
    {
        $data = $this->model::findOrFail($id);
        return view("admin.{$this->viewName}.show", compact('data'));
    }

    /**
     * Mustra el formulario para editar un nuevo recurso
     */
    public function edit(int $id)
    {
        $data = $this->model::findOrFail($id);
        if (!empty($this->loadExtraData())) {
            $extraData = $this->loadExtraData();
            return view("admin.{$this->viewName}.edit", array_merge(compact('data'), $extraData));
        }
        return view("admin.{$this->viewName}.edit", compact('data'));
    }

    /**
     * Actualiza un recurso seleccionado en el almacenamiento
     */
    public function update(Request $request, int $id)
    {
        $data = $this->model::findOrFail($id);
        $validated = $this->validateUpdateRequest($request);
        $data->update($validated);

        $this->alertGenerate1([
            'title' => '¡Registro actualizado!',
            'text' => 'El registro ha sido actualizado correctamente.',
        ]);

        return redirect()->route("admin.{$this->viewName}.edit", $id);

    }

    /**
     * Elimina un recurso seleccionado en el almacenamiento
     */
    public function destroy(int $id)
    {
        $data = $this->model::findOrFail($id);

        if (isset($this->relationCheckerDestroy) && call_user_func($this->relationCheckerDestroy, $data)) {
            
            $this->alertGenerate1([
                'icon' => 'error',
                'title' => '¡Ups!',
                'text' => "No se puede eliminar este registro porque tiene relaciones asociadas.",
            ]);
        
            return redirect()->route("admin.{$this->viewName}.index");
        }

        $data->delete();

        $this->alertGenerate1([
            'title' => '¡Registro eliminado!',
            'text' => "El registro ha sido eliminado correctamente",
        ]);

        return redirect()->route("admin.{$this->viewName}.index");
    }

    /**
     * Guardar la respuesta del callback, al verificar si el modelo esta presente en otro para eliminarlo
     */
    public function setRelationChecker(callable $checker)
    {
        $this->relationCheckerDestroy = $checker;
    }
}