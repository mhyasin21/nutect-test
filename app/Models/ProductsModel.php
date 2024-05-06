<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model
{
    protected $table            = 'products as PR';
    protected $primaryKey       = 'id';

    protected $allowedFields = ['image','id_category','nama_barang','harga_beli','harga_jual','stok','id_users'];

    function validasi_insert($Form){
    
        
        $rules =
        [
            
            'kategori' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ketegori wajib diisi',
                ],
            ],
            'nama_barang' => [
                'rules' => 'required|string|is_unique[products.nama_barang]',
                'errors' => [
                    'required' => 'nama barang wajib disi',
                    'string'=>'nama tidak boleh mengandung simbol',
                    'is_unique'=>'Nama barang sudah ada'
                ],
            ],
            'harga_beli' => [
                'rules' => 'required|alpha_numeric',
                'errors' => [
                    'required' => 'harga beli Wajib diisi',
                    'alpha_numeric'=>'harga beli harus angka'
                ],
            ],
            'harga_jual' => [
                'rules' => 'required|alpha_numeric',
                'errors' => [
                    'required' => 'harga jual Wajib diisi',
                    'alpha_numeric'=>'harga jual harus angka'
                ],
            ],
            'stok_barang' => [
                'rules' => 'required|alpha_numeric',
                'errors' => [
                    'required' => 'stok barang Wajib diisi',
                    'alpha_numeric'=>'Stock barang harus angka'
                ],
            ]
        ];

        $this->validation->setRules($rules);

        if (!$this->validation->run((array) $Form)) {
            $errors = $this->validation->getErrors();
            foreach ($errors as $err) {
                return ['status'=>false,'message'=>ucfirst($err),'new_token'=>csrf_hash(),'data'=>$Form];
            }   
        }

        return ['status'=>true];
    }

    function validasi_update($Form){
        

        
        $rules =
        [
            
            'kategori' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ketegori wajib diisi',
                ],
            ],
            'nama_barang' => [
                'rules' => 'required|string|is_unique[products.nama_barang,id,'.$Form['id'].']',
                'errors' => [
                    'required' => 'nama barang wajib disi',
                    'string'=>'nama tidak boleh mengandung simbol',
                    'is_unique'=>'Nama barang sudah ada'
                ],
            ],
            'harga_beli' => [
                'rules' => 'required|alpha_numeric',
                'errors' => [
                    'required' => 'harga beli Wajib diisi',
                    'alpha_numeric'=>'harga beli harus angka'
                ],
            ],
            'harga_jual' => [
                'rules' => 'required|alpha_numeric',
                'errors' => [
                    'required' => 'harga jual Wajib diisi',
                    'alpha_numeric'=>'harga jual harus angka'
                ],
            ],
            'stok_barang' => [
                'rules' => 'required|alpha_numeric',
                'errors' => [
                    'required' => 'stok barang Wajib diisi',
                    'alpha_numeric'=>'Stock barang harus angka'
                ],
            ]
        ];

        $this->validation->setRules($rules);

        if (!$this->validation->run((array) $Form)) {
            $errors = $this->validation->getErrors();
            foreach ($errors as $err) {
                return ['status'=>false,'message'=>ucfirst($err),'new_token'=>csrf_hash(),'data'=>$Form];
            }   
        }

        return ['status'=>true];
    }

    function validasi_gambar($file){
      
                if (!$file->isValid()) {
                    //throw new \RuntimeException($file->getErrorString().'('.$file->getError().')');
                    return ['status'=>false,'message'=>$file->getErrorString().'('.$file->getError().')','new_token'=>csrf_hash()];
                }
    
                $allowedTypes = ['jpg','png'];
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileType = $file->getExtension();
                $fileMimeType = $file->getMimeType();
    
                if ($file->getSize() > 102400) {
                    return ['status'=>false,'message'=>'Ukuran file terlalu besar. Maksimal 100 KB','new_token'=>csrf_hash()];
                }
    
                if (!in_array($fileType, $allowedTypes) || !in_array($fileMimeType, $allowedMimeTypes)) {
                    return ['status'=>false,'message'=>'Foramt file salah, Format yang diizinkan hanya JPG dan PNG','new_token'=>csrf_hash()];
                }
                
                $directory = "uploads";
                $fileName = $file->getRandomName();
                $file->move($directory, $fileName);

                $data = [
                    'path'=>$directory.'/'.$fileName
                ];

                return ['status'=>true,'data'=>$data];
    }


}
