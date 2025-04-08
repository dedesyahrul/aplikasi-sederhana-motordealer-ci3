<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_services_table extends CI_Migration {

    public function up() {
        // Create services table
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'service_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => TRUE
            ],
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'vehicle_brand' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'vehicle_model' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'vehicle_year' => [
                'type' => 'INT',
                'constraint' => 4
            ],
            'vehicle_number' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'complaints' => [
                'type' => 'TEXT'
            ],
            'diagnosis' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'service_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0
            ],
            'parts_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0
            ],
            'total_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed', 'cancelled'],
                'default' => 'pending'
            ],
            'mechanic_notes' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'completed_at' => [
                'type' => 'DATETIME',
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
        $this->dbforge->create_table('services');

        // Create service_items table for parts used
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'sparepart_id' => [
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
        $this->dbforge->create_table('service_items');

        // Add foreign key constraints
        $this->db->query('ALTER TABLE service_items ADD CONSTRAINT fk_service_items_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE service_items ADD CONSTRAINT fk_service_items_sparepart FOREIGN KEY (sparepart_id) REFERENCES spareparts(id)');
    }

    public function down() {
        $this->dbforge->drop_table('service_items');
        $this->dbforge->drop_table('services');
    }
} 
