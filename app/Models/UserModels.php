<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModels extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    protected $allowedFields = ['username', 'email', 'password'];

    public function validasi_form_login($Form){
       

            $rules =
            [
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email wajib diisi'
                        'valid_email' =>'Format email tidak valid'
                    ],
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password wajib disi',
                    ],
                ]
            ];
    
            $this->validation->setRules($rules);
    
            if (!$this->validation->run((array) $Form)) {
                $errors = $this->validation->getErrors();
                foreach ($errors as $err) {
                    return ['status'=>false,'message'=>ucfirst($err),'new_token'=>csrf_hash()];
                }   
            }
    
            return ['status'=>true];
    
        
    }
}
