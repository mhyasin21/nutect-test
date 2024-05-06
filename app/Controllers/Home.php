<?php

namespace App\Controllers;
 

class Home extends BaseController
{

    public function __construct()
    {
        parent::cek_session();
    }
    
    public function index()
    {
        
			$data = $this->db->query('select * from users');
			$respon = ['sukses'=>false,'pesan'=> 'berhasil login '.$this->session_nama,'data'=>$data->getResult()];
            
			return $this->respond($respon);
    }
	
    public function profile(){

        $data['sidebar_active'] = "profile";
        $data['title'] = "profile";
        $data['session'] = [
            'nama'=>$this->session_nama,
            'level'=>$this->session_level
        ];
       
        

        return view('profile',$data);
    }
}