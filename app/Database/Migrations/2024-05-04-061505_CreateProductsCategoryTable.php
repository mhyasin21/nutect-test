<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsCategoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment'=>true
            ],
            'nama_category' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('products_category');
    }

    public function down()
    {
        // Menghapus tabel jika rollback migrasi
        $this->forge->dropTable('products_category');
    }
}
