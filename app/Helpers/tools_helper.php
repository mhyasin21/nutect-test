<?php

function __construct() {}

function cek_sisa_detik(){
    $dateString = "2024-03-03 13:48:00";

    // Waktu sekarang
    $now = new DateTime();

    // Waktu target
    $targetTime = new DateTime($dateString);

    // Hitung selisih waktu dalam detik
    $timeDiff = $targetTime->getTimestamp() - $now->getTimestamp();

    return $timeDiff;
}



function format_rupiah($angka)
{
	$rupiah = number_format($angka, 0, ',', '.');
	return 'Rp.'.$rupiah;
}

function make_auto_code($table, $column, $prefix, $sprintf)
{
	$LengthPrefix = strlen($prefix);
	$db = db_connect();
	$builder = $db->table('no_urut')
		->select('no_urut')
		->where(
			[
				'nama_table' => $table,
				'prefix' => $prefix
			]
		);
	$query = $builder->get();

	//cek apakah ada
	if (count($query->getResultArray()) < 1) {
		//jika hasil 0 maka akan dibuatkan kode 1
		$code = 1;
	} else {
		$MaxCode = $query->getRow()->no_urut;
		$code = $MaxCode + 1;
	}

	$Result = $prefix . sprintf($sprintf, $code);
	return ['code' => $Result, 'nomor' => $code,'column'=>$column,'table'=>$table,'prefix'=>$prefix];
}

function update_nomor_urut($table, $column, $prefix, $nomor)
{
	$db = db_connect();
	$cek = $db
		->table('no_urut')
		->where(
			[
				'nama_table' => $table,
				'prefix' => $prefix,
				'column' => $column,
			]
		)
		->get()
		->getResultArray();

	if (count($cek) < 1) {
		$db
			->table('no_urut')
			->set([
				'no_urut' => $nomor,
				'nama_table' => $table,
				'prefix' => $prefix,
				'column' => $column,
			])
			->insert();
	} else {
		$db
			->table('no_urut')
			->where(
				[
					'nama_table' => $table,
					'prefix' => $prefix,
					'column' => $column,
				]
			)
			->set(['no_urut' => $nomor])
			->update();
	}
}

function log_activity($data){
    $db = db_connect();

    $insert = [
        'id_register'=>$data[0],
        'keterangan'=>$data[1],
        'id_user'=>$data[2],
		'old_data'=>isset($data[3]) ? $data[3] : '',
		'new_data'=>isset($data[4]) ? $data[4] : '',
        'tanggal_buat'=>date('Y-m-d'),
        'time_buat'=>date('H:i:s'),
    ];

    $db->table('register_log')->insert($insert);
}

function upload_file_base64($file_name, $base64,$format_allow)

{
	$FCPATH = FCPATH;
	$baseFile64 = explode(',', $base64);
	$formats = explode('/', $baseFile64[0]);
	$format = explode(';', $formats[1]);

	if($format[0] !=$format_allow){
		return ['status'=>false,'message'=>'Format file harus .'.$format_allow];
	}

	$nameGambar = $file_name . '.' . $format[0];
	// Tampung Foto pake 64
	$foto = base64_decode($baseFile64[1]);
	// Pindah File poto
	file_put_contents($FCPATH . $nameGambar, $foto);
	
	return ['status'=>true,'message'=>'success','data'=>['link'=>$nameGambar]]; 

}