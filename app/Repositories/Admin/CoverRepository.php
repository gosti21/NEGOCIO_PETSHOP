<?php

namespace App\Repositories\Admin;

use App\Models\Cover;
use App\Repositories\Admin\BaseRepository;
use App\Traits\Admin\resolvesRequests;
use App\Traits\Admin\sweetAlerts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CoverRepository extends BaseRepository
{
    use resolvesRequests;
    use sweetAlerts;

    public function __construct(Cover $model)
    {
        parent::__construct($model, 'covers');
    }

    public function index()
    {
        $covers = Cover::with('images')->orderby('order')->get();
        return $covers;
    }

    public function store(Request $request)
    {
        $this->validateStoreRequest($request);

        DB::beginTransaction();
        try {
            $cover = Cover::create([
                'title' => $request->title,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at
            ]);

            $img = Storage::put('covers', $request->image);

            $cover->images()->create([
                'path' => $img
            ]);

            DB::commit();

            $this->alertGenerate1([
                'title' => 'Portada creada!',
                'text' => 'La portada ha sido creada correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            $this->alertGenerate1([
                'title' => "¡Error!",
                'text' => "Hubo un problema al eliminar el registro.",
                'icon' => "error",
            ]);
        }
    }

    public function update(Request $request, int $id)
    {
        $cover = Cover::findOrFail($id);
        $this->validateUpdateRequest($request);

        DB::beginTransaction();
        try{

            if($request->image){
                $image = $cover->images->first();
                if ($image && $image->path) {

                    Storage::delete($cover->images->first()->path);
                    $img = $request->image->store('covers');

                    $cover->images()->update([
                        'path' => $img
                    ]);
                }
            }

            $cover->update([
                'title' => $request->title,
                'start_at' => $request->start_at,
                'is_active' => $request->is_active
            ]);

            DB::commit();

            $this->alertGenerate1([
                'title' => 'Portada actualizada!',
                'text' => 'La portada ha sido actualizada correctamente.',
            ]);

        } catch(\Exception $e){
            DB::rollback();

            $this->alertGenerate1([
                'title' => "¡Error!",
                'text' => "Hubo un problema al eliminar el registro.",
                'icon' => "error",
            ]);
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {

            $cover = Cover::findOrFail($id);

            $image = $cover->images->first();

            Storage::delete($image->path);
            $image->delete();

            $cover->delete();

            DB::commit();

            $this->alertGenerate1([
                'title' => '¡Registro eliminado!',
                'text' => "El registro ha sido eliminado correctamente",
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            $this->alertGenerate1([
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => "$e",
            ]);
        }
    }
}