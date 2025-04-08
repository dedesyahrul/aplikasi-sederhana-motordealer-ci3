<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_motors_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'merk' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'tahun' => [
                'type' => 'INT',
                'constraint' => 4
            ],
            'warna' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'harga' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'stok' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('motors');
    }

    public function down() {
        $this->dbforge->drop_table('motors');
    }
} 
