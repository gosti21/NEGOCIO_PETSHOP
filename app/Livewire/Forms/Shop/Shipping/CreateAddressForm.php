<?php

namespace App\Livewire\Forms\Shop\Shipping;

use App\Enums\TypeOfDocuments;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateAddressForm extends Form
{
    public $province = '';
    public $district = '';
    public $street = '';
    public $reference = '';
    public $receiver = 1;
    public $receiver_info = [];
    public $default = false;

    public function rules()
    {
        return [
            'province' => 'required|string|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/|between:3,60',
            'district' => 'required|string|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/|between:3,60',
            'street' => 'required|string',
            'reference' => 'required|string',
            'receiver' => 'required|in:1,2',
            'receiver_info' => 'required|array',
            'receiver_info.name' => 'required|string|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/|between:3,80',
            'receiver_info.last_name' => 'required|string|regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/|between:3,60',
            'receiver_info.document_type' => [
                'required',
                'string',
                new Enum(TypeOfDocuments::class)
            ],
            'receiver_info.document_number' => 'required|string|max:20',
            'receiver_info.phone' => 'required|integer|digits:9',
        ];
    }

    public function validationAttributes()
    {
        return [
            'reference' => 'referencia',
            'receiver_info.name' => 'nombres',
            'receiver_info.last_name' => 'apellidos',
            'receiver_info.document_type' => 'tipo de documento',
            'receiver_info.document_number' => 'N° de documento',
            'receiver_info.phone' => 'teléfono',
        ];
    }

    public function save()
    {
        $this->validate();

        if(Auth::user()->addresses->count() == 0)
        {
            $this->default = true;
        }

        Address::create([
            'province' => $this->province,
            'district' => $this->district,
            'street' => $this->street,
            'reference' => $this->reference,
            'receiver' => $this->receiver,
            'receiver_info' => $this->receiver_info,
            'default' => $this->default,
            'user_id' => Auth::user()->id,
        ]);

        $this->reset();
        $this->receiver_info = [
            'name' => Auth::user()->name,
            'last_name' => Auth::user()->last_name,
            'document_type' => Auth::user()->documents->document_type,
            'document_number' => Auth::user()->documents->document_number,
            'phone' => Auth::user()->phones->number,
        ];
    }
}
