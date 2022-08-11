<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthValidation
 *
 * @author coder
 */
namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class UsersValidation {
    
    public function validation_rules($request, $key){
        $validations = [
            "edit" => [
                'user' => 'required',
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'middlename' => 'required|string',
                'username' => 'required|string|unique:users',
                'phone' => 'required|string|min:11|unique:users',
            ]
        ];
        
        return Validator::make($request->all(), $validations[$key]);
    }

}
