<?php

namespace App\Livewire\Admin\Options;

use App\Livewire\Forms\Admin\Options\CreateOptionForm;
use App\Models\Feature;
use App\Models\Option;
use App\Traits\Admin\sweetAlerts;
use Livewire\Attributes\On;
use Livewire\Component;

class ManageOptions extends Component
{
    use sweetAlerts;
    
    public CreateOptionForm $newOption;

    public $options;

    public function mount()
    {
        $this->options = Option::with('features')->get();
    }
    
    #[On('featureAdded')]
    public function updateFeatureList()
    {
        $this->options = Option::with('features')->get();
    }

    public function updatedNewOptionType()
    {
        $this->newOption->updatedNewOptionType();
    }

    public function addFeature()
    {
        $this->newOption->addFeature();
    }

    /**
     * Elimina los features del modal
     */
    public function removeFeature($index)
    {
        $this->newOption->removeFeature($index);
    }

    /**
     * Elimina el feature de la vista
     */
    public function deleteFeature(Feature $feature)
    {
        $feature->delete();
        $this->options = Option::with('features')->get();
    }

    /**
     * Elimina la opcion de la vista
     */
    public function deleteOption(Option $option)
    {
        $option->delete();
        $this->options = Option::with('features')->get();
    }

    public function storeOption()
    {

        try{
            $this->newOption->save();
    
            $this->options = Option::with('features')->get();
    
            $this->alertGenerate2();

        }catch(\Illuminate\Validation\ValidationException $e){
            $this->alertGenerate2([
                'icon'  => 'error',
                'title' => '¡Error!',
                'text'  => 'El formulario contiene errores.',
            ]);
            throw $e;
            
        } catch (\Exception $e) {
            $this->alertGenerate2([
                'icon'  => 'error',
                'title' => '¡Error!',
                'text'  => 'Ocurrió un error inesperado al guardar la opción.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
