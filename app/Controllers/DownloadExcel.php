<?php

namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
 

class DownloadExcel extends BaseController
{

    public function __construct()
    {
        parent::cek_session();

        $this->kategory = new \App\Models\ProductCategory;
        $this->products = new \App\Models\ProductsModel;
    }
    
    public function index()
    {
        
			$data = $this->db->query('select * from users');
			$respon = ['sukses'=>false,'pesan'=> 'berhasil login '.$this->session_nama,'data'=>$data->getResult()];
            
			return $this->respond($respon);
    }
	
    public function products(){

        try {

            $sheet = new Spreadsheet();
          
            $Form = $this->request->getGet();
            $kategori = $Form['kategori'] ?? '';
            //menjalankan query
            $data = $this->products->select("PR.*,PC.nama_category nama_kategori")
            ->join('products_category as PC','PR.id_category=PC.id','inner');
            //FILER BY KATEGORY
            if($kategori != ''){
                $data = $data->where(['PR.id_category'=>$kategori]);
            }

            $data = $data->find();

           

            //MEMBUAT JUDUL TABLE
            $sheet->setActiveSheetIndex(0)->setCellValue('A1', 'DATA STOK');
            $sheet->getActiveSheet()->mergeCells('A1:F1');

            $posisi_tengah = array(
                'alignment' => array(
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ),
                'font'=>array(
                    'size'=>20
                )
            );
            $sheet->getActiveSheet()->getStyle("A1:F1")->applyFromArray($posisi_tengah)->getFont()->setBold( true );


            //ROW 3 = JUDUL TABLE
            $RJT = 3; //RJT row judul table

            //MEMBUAT JUDUL TABLE
            $sheet->setActiveSheetIndex(0)->setCellValue('A'.$RJT, 'No')
            ->setCellValue('B'.$RJT, 'Nama Produk')
            ->setCellValue('C'.$RJT, 'Ketegori Produk')
            ->setCellValue('D'.$RJT, 'Harga Barang')
            ->setCellValue('E'.$RJT, 'Harga Jual')
            ->setCellValue('F'.$RJT, 'Stok');

            $sheet->getActiveSheet()->getStyle('A'.$RJT.':F'.$RJT)->applyFromArray(
                array(
                    'fill' => array(
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => array('argb' => 'ad150a')
                    ),
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => array('argb' => '00000000'),
                        ),    
                    ),
                    'font' => array(
                        'color' => array('rgb' => 'FFFFFF'), // Warna putih
                    ),
                    'alignment' => array(
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ),
                )
            )->getFont()->setBold( true );

            //mengatur lebar column auto
            foreach(["A","B","C","D","E","F"] as $col) :
                $sheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            endforeach;
          

            
            //ROW 4 DST = DATA TABLE;
            $RDT = 4; //RDT = row data table 
            
            $no = 1;
            foreach($data as $row){
                $sheet->setActiveSheetIndex(0)->setCellValue('A'.$RDT, $no++)
                ->setCellValue('B'.$RDT, $row['nama_barang'])
                ->setCellValue('C'.$RDT, $row['nama_kategori'])
                ->setCellValue('D'.$RDT, $row['harga_beli'])
                ->setCellValue('E'.$RDT, $row['harga_jual'])
                ->setCellValue('F'.$RDT, $row['stok']);

                $sheet->getActiveSheet()->getStyle('A'.$RDT.':F'.$RDT)->applyFromArray(
                    array(
                        'borders' => array(
                            'allBorders' => array(
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => array('argb' => '00000000'),
                            ),    
                        )
                    )
                )->getFont()->setBold( false );

                // Mengatur format accounting pada sel A1
                $numberFormat = '#,##0';
                $sheet->getActiveSheet()->getStyle('D'.$RDT)->getNumberFormat()->setFormatCode($numberFormat);
                $sheet->getActiveSheet()->getStyle('E'.$RDT)->getNumberFormat()->setFormatCode($numberFormat);

              $RDT++;
    
            }

            

           // Menyimpan spreadsheet ke file
           

            $writer = new Xlsx($sheet);
            $filename = 'LAPORAN_DATA_STOK_'. date('Y-m-d-His');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');

          
        } catch (\Throwable $th) {
            return $th->getMessage();
        }



    }

}
