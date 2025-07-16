<?php

namespace App\Repositories\Admin;

use App\Models\ShippingCompany;
use App\Traits\Admin\sweetAlerts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingCompanyRepository extends BaseRepository
{
    use sweetAlerts;

    public function __construct(ShippingCompany $model)
    {
        parent::__construct($model, 'shipping-companies');
    }

    public function store(Request $request)
    {
        $this->validateStoreRequest($request);
        DB::beginTransaction();
        try {
            $shippingCompany = ShippingCompany::create([
                'name' => $request->name,
                'email' => $request->email,
                'district' => $request->district,
                'street' => $request->street,
                'reference' => $request->reference,
            ]);
            $shippingCompany->phones()->create([
                'number' => $request->phone,
            ]);

            DB::commit();

            $this->alertGenerate1([
                'title' => '¡Registro creado!',
                'text' => 'El registro ha sido creado correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            $this->alertGenerate1([
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => "Hubo un problema al actualizar el registro.",
            ]);
        }
    }

    public function update(Request $request, int $id)
    {
        $shippingCompany = $this->model::findOrFail($id);
        $this->validateUpdateRequest($request);

        DB::beginTransaction();
        try{
            $shippingCompany->update([
                'name' => $request->name,
                'email' => $request->email,
                'district' => $request->district,
                'street' => $request->street,
                'reference' => $request->reference,
            ]);
            $shippingCompany->phones()->update([
                'number' => $request->phone,
            ]);

            DB::commit();

            $this->alertGenerate1([
                'title' => '¡Registro actualizado!',
                'text' => 'El registro ha sido actualizado correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            $this->alertGenerate1([
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => "Hubo un problema al crear el registro.",
            ]);
        }
        
    }

    public function destroy(int $id)
    {
        $shippingCompany = $this->model::findOrFail($id);

        DB::beginTransaction();
        try{
            DB::commit();
            $shippingCompany->phones->delete();
            $shippingCompany->delete();
            
            $this->alertGenerate1([
                'title' => '¡Registro eliminado!',
                'text' => 'El registro ha sido eliminado correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            $this->alertGenerate1([
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => "Hubo un problema al eliminar el registro.",
            ]);
        }
    }
}