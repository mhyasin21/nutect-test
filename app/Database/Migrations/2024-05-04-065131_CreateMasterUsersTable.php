<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment'=>true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'level' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');
       
    }

    public function down()
    {
        // Menghapus tabel jika rollback migrasi
        $this->forge->dropTable('users');
    }
}
