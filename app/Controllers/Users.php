<?php

namespace App\Controllers;

use \App\Models\UserModels;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{

    public function __construct()
	{
      // parent::cek_session_before();

        $this->UsersModel = new UserModels();
    
    }

    public function index()
    {

           $data['content'] = "login";
           $data['title'] = "Login";
           return view('login',$data);
    }
	
	public function proses_login()
    {
        try {
            $Form = $this->request->getPost();
            
            //Cek Form yang dikirim
            $validasi = $this->UsersModel->validasi_form_login($Form);
            if($validasi['status'] == false){
                //Jika validasi masih gagal kirim respon gagal
                return $this->respond($validasi,200);
            }
            //LOLOS VALIDASI
            //PENGECEKAN DATA KE DATABASE
            $email = $Form['email'];
            $password = hash("SHA512",md5($Form['password']));

            $cek = $this->UsersModel
            ->where(
                [           
                    "email"=>$Form['email'],
                    "password"=>$password
                ]
            )->find();

            if(count($cek) < 1){
                return $this->respond(['status'=>false,'message'=>'username / password salah','new_token'=>csrf_hash()],200);
            }else{


                unset($cek[0]['password']);
                $payload = $cek[0];
                $token = JWT::encode($payload, $this->key, 'HS256');


                //simpan session
                $this->response->setCookie('SESSION_LOGIN', $token, time());
            }


            return $this->respond(['status'=>true,'message'=>'gagal'],200);
            
        } catch (\Throwable $th) {
            return $this->respond(['status'=>false,'message'=>$th->getMessage(),'new_token'=>csrf_hash()],200);
        }
    }


    public function logout()
    {
        delete_cookie("SESSION_LOGIN");
        header('Refresh: 0;');
    }
}