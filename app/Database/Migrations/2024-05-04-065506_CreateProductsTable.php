<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
       
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment'=>true
            ],
            'image' => [
                'type' => 'TEXT', 
                'null'=>false               
            ],
            'id_category' => [
                'type' => 'INT', 
                'null'=>false               
            ],
            'nama_barang' => [
                'type' => 'VARCHAR', 
                'null'=>false               
            ],
            'harga_beli' => [
                'type' => 'INT', 
                'null'=>false               
            ],
            'harga_jual' => [
                'type' => 'INT', 
                'null'=>false               
            ],
            'stok' => [
                'type' => 'INT', 
                'null'=>false               
            ],
            'id_users' => [
                'type' => 'INT', 
                'null'=>false               
            ]
        ]);

        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_category', 'products_category', 'id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('id_users', 'users', 'id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable('products');

       

      
    }

    public function down()
    {
        // Menghapus tabel jika rollback migrasi
        $this->forge->dropTable('products');
    }
}
