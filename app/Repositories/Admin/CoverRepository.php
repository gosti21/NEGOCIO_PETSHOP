<?php

namespace App\Repositories\Admin;

use App\Models\Cover;
use App\Repositories\Admin\BaseRepository;
use App\Traits\Admin\resolvesRequests;
use App\Traits\Admin\sweetAlerts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        $covers = Cover::with('images')->orderBy('order')->get();
        Log::info('Portadas obtenidas: ' . json_encode($covers));
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

            if ($request->hasFile('image')) {
                $imgPath = $request->file('image')->store('covers');
                Log::info('Imagen almacenada en: ' . $imgPath);

                $cover->images()->create([
                    'path' => $imgPath
                ]);
            } else {
                Log::warning('No se subió imagen al crear portada.');
            }

            DB::commit();

            $this->alertGenerate1([
                'title' => 'Portada creada!',
                'text' => 'La portada ha sido creada correctamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error al crear portada: ' . $e->getMessage());

            $this->alertGenerate1([
                'title' => "¡Error!",
                'text' => "Hubo un problema al crear la portada.",
                'icon' => "error",
            ]);
        }
    }

    public function update(Request $request, int $id)
    {
        $this->validateUpdateRequest($request);

        $cover = Cover::findOrFail($id);

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $image = $cover->images->first();

                if ($image) {
                    Storage::delete($image->path);
                    $newPath = $request->file('image')->store('covers');
                    $image->update(['path' => $newPath]);
                    Log::info("Imagen actualizada para portada ID $id: $newPath");
                } else {
                    $newPath = $request->file('image')->store('covers');
                    $cover->images()->create(['path' => $newPath]);
                    Log::info("Imagen agregada a portada ID $id: $newPath");
                }
            }

            $cover->update([
                'title' => $request->title,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                'is_active' => $request->is_active ?? 0,
            ]);

            DB::commit();

            $this->alertGenerate1([
                'title' => 'Portada actualizada!',
                'text' => 'La portada ha sido actualizada correctamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error al actualizar portada: ' . $e->getMessage());

            $this->alertGenerate1([
                'title' => "¡Error!",
                'text' => "Hubo un problema al actualizar la portada.",
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

            if ($image) {
                Storage::delete($image->path);
                $image->delete();
                Log::info("Imagen eliminada: {$image->path}");
            }

            $cover->delete();

            DB::commit();

            $this->alertGenerate1([
                'title' => '¡Registro eliminado!',
                'text' => "El registro ha sido eliminado correctamente",
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error al eliminar portada: ' . $e->getMessage());

            $this->alertGenerate1([
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => "Hubo un problema al eliminar el registro.",
            ]);
        }
    }
}
