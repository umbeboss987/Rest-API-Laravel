<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    function addAddress (Request $req ){
        $user_id = auth()->user()->id;
       
        $req->validate([
          'address' => 'required|min:2',
          'telephone_number' => 'required',
          'surname' => 'required|min:2',
          'postal_code' => 'required',
          'city' => 'required|min:2',
          'name' => 'required|min:2'
        ]);

        $address = new Address();
        $address->user_id = $user_id;
        $address->address = $req->input('address');
        $address->telephone_number = $req->input('telephone_number');
        $address->name = $req->input('name');
        $address->surname = $req->input('surname');
        $address->postal_code = $req->input('postal_code');
        $address->city = $req->input('city');
        $address->save();
        return response(null, 201)->header('location', 'http://localhost:8000/api/rest/address/'. $address->id);
    }


    function getUserAddress (){
      $user_id = auth()->user()->id;
      $accountDetails = Address::select()->where('user_id', $user_id)->get();
      return response()->json($accountDetails);
    }

    function updateAddress (Request $req, $address_id){
      $input = $req->all();
      $account = Address::find($address_id);
      if($account){
        if (isset($input['name'])) $account->name = $input['name'];
        if (isset($input['surname'])) $account->surname = $input['surname'];
        if (isset($input['telephone_number'])) $account->telephone_number = $input['telephone_number'];
        if (isset($input['city'])) $account->city = $input['city'];
        if (isset($input['postal_code'])) $account->postal_code = $input['postal_code'];
        if (isset($input['address'])) $account->address = $input['address'];
        $account->save();      
        return response()->noContent();
      }else{
        return response()->noContent();

      }
    }
  
  
    function getAddressById ($address_id){
      $user_id = auth()->user()->id;
      $address = Address::where('user_id', $user_id)->where('id', $address_id)->first();
      return response()->json($address, 200);
    }
}
