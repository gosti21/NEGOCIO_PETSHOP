<?php

namespace App\Livewire\Forms\Shop\Shipping;

use App\Enums\TypeOfDocuments;
use App\Models\Address;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EditAddressForm extends Form
{
    public $id;
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

    public function edit($address)
    {
        $this->id = $address->id;
        $this->province = $address->province;
        $this->district = $address->district;
        $this->street = $address->street;
        $this->reference = $address->reference;
        $this->receiver = $address->receiver;
        $this->receiver_info = $address->receiver_info;
        $this->default = $address->default;
    }

    public function update()
    {
        $this->validate();

        $address = Address::findOrFail($this->id);

        $address->update([
            'province' => $this->province,
            'district' => $this->district,
            'street' => $this->street,
            'reference' => $this->reference,
            'receiver' => $this->receiver,
            'receiver_info' => $this->receiver_info,
            'default' => $this->default,
        ]);

        $this->reset();
    }
}
