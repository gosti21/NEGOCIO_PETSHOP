<?php

namespace App\Livewire\Shop;

use App\Livewire\Forms\Shop\Shipping\CreateAddressForm;
use App\Livewire\Forms\Shop\Shipping\EditAddressForm;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShippingAddresses extends Component
{
    public $addresses;

    public $newAddress = false;

    public CreateAddressForm $createAddress;
    public EditAddressForm $editAddress;

    public function mount()
    {
        $this->addresses = Address::where('user_id', Auth::user()->id)->get();

        $this->createAddress->receiver_info = [
            'name' => Auth::user()->name,
            'last_name' => Auth::user()->last_name,
            'document_type' => Auth::user()->documents->document_type,
            'document_number' => Auth::user()->documents->document_number,
            'phone' => Auth::user()->phones->number,
        ];
    }

    public function store()
    {
        if(count($this->addresses) == 0){
            $this->dispatch('addressAdded');
        }
        $this->createAddress->save();
        $this->addresses = Address::where('user_id', Auth::user()->id)->get();
        $this->newAddress = false;
    }

    public function edit($id)
    {
        $address = Address::findOrFail($id);

        $this->editAddress->edit($address);
        $this->addresses = Address::where('user_id', Auth::user()->id)->get();
    }

    public function update()
    {
        $this->editAddress->update();
    }

    public function deleteAddress($id)
    {
        Address::findOrFail($id)->delete();
        $this->addresses = Address::where('user_id', Auth::user()->id)->get();

        if($this->addresses->where('default', true)->count() == 0 && $this->addresses->count() > 0){
            $this->addresses->first()->update(['default' => true]);
        }
        if(!count($this->addresses)){
            $this->dispatch('addressDelete');
        }
    }

    public function setDefaultAddress($id)
    {
        $this->addresses->each( function($address) use ($id) {
            $address->update([
                'default' => $address->id == $id
            ]);
        });
    }

    public function render()
    {
        return view('livewire.shop.shipping-addresses');
    }
}
