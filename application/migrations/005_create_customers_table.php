<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_customers_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'identity_type' => [
                'type' => 'ENUM',
                'constraint' => ['ktp', 'sim', 'passport'],
                'default' => 'ktp'
            ],
            'identity_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
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
        $this->dbforge->create_table('customers');

        // Add foreign key constraints to previous tables
        $this->db->query('ALTER TABLE sales ADD CONSTRAINT fk_sales_customer FOREIGN KEY (customer_id) REFERENCES customers(id)');
        $this->db->query('ALTER TABLE services ADD CONSTRAINT fk_services_customer FOREIGN KEY (customer_id) REFERENCES customers(id)');
    }

    public function down() {
        $this->dbforge->drop_table('customers');
    }
} 
