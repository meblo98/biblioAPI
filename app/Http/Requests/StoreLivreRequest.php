<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StoreLivreRequest extends FormRequest
{
   
    public function rules(): array
    {
        return [
           "isbn"=>["required","string","unique:livre,isbn"],
           "titre"=>["required","string","max:255"],
           "auteur"=>["required","string","max:255"],
           "categorie_id"=>["required","exists:categories,id"],
           "date_publication"=>["required","date","before:now"],
           "quantité"=>["required","interger","min:1"],
           "image"=>["required","image","mimes:jpeg,png,jpg,","max:2048"]
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            ['success' => false, 'errors' => $validator->errors()],
            422
        ));
    }
    
}
