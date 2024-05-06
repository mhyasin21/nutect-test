<?php

namespace App\Controllers;
 

class Products extends BaseController
{

    public function __construct()
    {
        parent::cek_session();

        $this->kategory = new \App\Models\ProductCategory;
        $this->products = new \App\Models\ProductsModel;
    }
    
    public function index()
    {
          
        $breadcrumb = [
            [
                "label"=>"Daftar Product",
                "class"=> "active"
            ]
        ];

        $option_kategori = $this->kategory->findAll();

        
      

        $data['sidebar_active'] = "products";
        $data['title'] = "Daftar produk";

        $data['option_kategori'] = $option_kategori;
        $data['breadcrumb'] = $breadcrumb;

        return view('product/home',$data);
    }
	
	public function add()
    {
    
        $breadcrumb = [
            [
                "label"=>"Daftar Product",
                "class"=> ""
            ],
            [
                "label"=>"Tambah Produk",
                "class"=> "active"
            ]
        ];


        $option_kategori = $this->kategory->findAll();

        $data['sidebar_active'] = "products";
        $data['title'] = "Tambah produk";
        $data['option_kategori'] = $option_kategori;
        $data['Username'] = $this->session_nama;
        $data['breadcrumb'] = $breadcrumb;

       

        return view('product/add',$data);
    }

	public function edit($id)
    {

        $product = $this->products->where(['id'=>$id])->first();

        $breadcrumb = [
            [
                "label"=>"Daftar Product",
                "class"=> ""
            ],
            [
                "label"=>"Edit Produk",
                "class"=> ""
            ],
            [
                "label"=>$product['nama_barang'],
                "class"=> "active"
            ]
        ];


        $option_kategori = $this->kategory->findAll();

        $data['sidebar_active'] = "products";
        $data['title'] = "Edit Product";
        $data['option_kategori'] = $option_kategori;
        $data['product'] = $product;
        $data['Username'] = $this->session_nama;
        $data['breadcrumb'] = $breadcrumb;

       

        return view('product/edit',$data);
    }


    public function proses_add(){
    
        try {
                //Tangkap request POST
                $Form = $this->request->getPost();

                //ubah format string RP.1.000.000 menjadi number 1000000
                $Form['harga_beli'] = preg_replace('/[^\d]/', '', $Form['harga_beli']);
                $Form['harga_jual'] = preg_replace('/[^\d]/', '', $Form['harga_jual']);
                $Form['stok_barang'] = preg_replace('/[^\d]/', '', $Form['stok_barang']);
                $Form['nama_barang'] = htmlspecialchars($Form['nama_barang']);

                //validasi Form kosong / tidak sesuai format
                $validasi_form = $this->products->validasi_insert($Form);

                if($validasi_form['status'] == false){
                    return $this->respond($validasi_form,200);
                }
                
                //validasi gambar
                $validasi_gambar = $this->products->validasi_gambar($this->request->getFile('gambar'));

                //jika gagal mengirim meneruskan response dari proses validasi
                if($validasi_gambar['status'] == false){
                    return $this->respond($validasi_gambar,200);
                }
                
                $path = $validasi_gambar['data']['path'];

                $data_insert = [
                    "id_category"=>$Form['kategori'],
                    "nama_barang"=>$Form['nama_barang'],
                    "harga_jual"=>$Form['harga_jual'],
                    "harga_beli"=>$Form['harga_beli'],
                    "stok"=>$Form['stok_barang'],
                    "image"=>$path,
                    "id_users"=>$this->session_id,
                ];

                //proses insert;
                $this->products->insert($data_insert);

                return $this->respond(['status'=>true,'message'=>'Berhasil insert data'],200);
           
        } catch (\Throwable $th) {
            return $this->respond(['status'=>false,'message'=>$th->getMessage(),'new_token'=>csrf_hash()],200);
        }
    
    }


    public function proses_edit($id){
    
        try {
                //Tangkap request POST
                $Form = $this->request->getPost();

                
                //ubah format string RP.1.000.000 menjadi number 1000000
                $Form['id'] = $id;
                $Form['harga_beli'] = preg_replace('/[^\d]/', '', $Form['harga_beli']);
                $Form['harga_jual'] = preg_replace('/[^\d]/', '', $Form['harga_jual']);
                $Form['stok_barang'] = preg_replace('/[^\d]/', '', $Form['stok_barang']);
                $Form['nama_barang'] = htmlspecialchars($Form['nama_barang']);



                //validasi Form kosong / tidak sesuai format
                $validasi_form = $this->products->validasi_update($Form);

                if($validasi_form['status'] == false){
                    return $this->respond($validasi_form,200);
                }
                

                
                if($this->request->getFile('gambar')->isValid()){
                     //validasi gambar
                     $validasi_gambar = $this->products->validasi_gambar($this->request->getFile('gambar'));
                      //jika gagal mengirim meneruskan response dari proses validasi
                    if($validasi_gambar['status'] == false){
                        return $this->respond($validasi_gambar,200);
                    }
                    
                    $path = $validasi_gambar['data']['path'];
                    $data_insert['path'] = $path ;
                }
               

               

                $data_update = [
                    "id_category"=>$Form['kategori'],
                    "nama_barang"=>$Form['nama_barang'],
                    "harga_jual"=>$Form['harga_jual'],
                    "harga_beli"=>$Form['harga_beli'],
                    "stok"=>$Form['stok_barang']
                ];

                //proses insert;
                $this->products->where('id',$id)->set($data_update)->update();

                return $this->respond(['status'=>true,'message'=>'Berhasil update data'],200);
           
        } catch (\Throwable $th) {
            return $this->respond(['status'=>false,'message'=>$th->getMessage(),'new_token'=>csrf_hash()],200);
        }
    
    }

    public function listing_product(){
        try {

            $Form = $this->request->getGet();
            $limit = 10;
            $page = $Form['page'] ?? 1;
            $offset = ($page-1)*$limit;

            $key = $Form['key'] ?? '';
            $kategori = $Form['kategori'] ?? '';

            //menjalankan query
            $data = $this->products->select("PR.*,PC.nama_category nama_kategori")
            ->join('products_category as PC','PR.id_category=PC.id','inner')
            ->like('LOWER("PR".nama_barang)',strtolower($key),'both');
            //FILER BY KATEGORY
            if($kategori != ''){
                $data = $data->where(['PR.id_category'=>$kategori]);
            }

            $data = $data->limit($limit)->offset($offset)->find();
            //batas menjalankan query

            //mengambil jumlah halaman, untuk menentukan pagginh.
            $total_data = $this->products->CountAll();
            //atur pagging.
            $total_page = ceil($total_data/$limit); //ceil untuk membulatkan ke atas, misalkan hasil pembagian 6.1 maka akan dibulatkan menjadi 7

            $res = [
                'status'=>true,
                'message'=>'berhasil mengambil data',
                'data'=>$data,
                'pagging'=>[
                    'total_page'=>$total_page,
                    'data_info'=> 'Show '.count($data).' from '.$total_data
                ]
            ];

            return $this->respond($res,200);

            
        } catch (\Throwable $th) {
            return $this->respond(['status'=>false,'message'=>$th->getMessage(),'new_token'=>csrf_hash()],200);
        }
    }

    public function proses_hapus($id){
        try {

            $this->products->delete($id);

            return $this->respond(['status'=>true,'message'=>"berhasil hapus",'new_token'=>csrf_hash()],200);
        } catch (\Throwable $th) {
            return $this->respond(['status'=>false,'message'=>$th->getMessage(),'new_token'=>csrf_hash()],200);
        }
    }
}
