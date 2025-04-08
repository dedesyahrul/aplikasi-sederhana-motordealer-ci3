<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_sales_table extends CI_Migration {

    public function up() {
        // Create sales table
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'invoice_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => TRUE
            ],
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'cancelled'],
                'default' => 'pending'
            ],
            'notes' => [
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
        $this->dbforge->create_table('sales');

        // Create sales_items table
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'sale_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'item_type' => [
                'type' => 'ENUM',
                'constraint' => ['motor', 'sparepart'],
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('sales_items');

        // Add foreign key constraints
        $this->db->query('ALTER TABLE sales_items ADD CONSTRAINT fk_sales_items_sale FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE');
    }

    public function down() {
        $this->dbforge->drop_table('sales_items');
        $this->dbforge->drop_table('sales');
    }
} 
