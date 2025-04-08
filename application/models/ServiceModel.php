<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceModel extends CI_Model {
    private $table = 'services';
    private $items_table = 'service_items';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAll() {
        return $this->db->get($this->table)->result();
    }

    public function getAllWithCustomer() {
        $this->db->select('services.*, customers.name as customer_name, customers.phone as customer_phone, customers.email as customer_email, customers.address as customer_address');
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id = services.customer_id');
        $this->db->order_by('services.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function getById($id) {
        $this->db->select('services.*, customers.name as customer_name, customers.phone as customer_phone, customers.email as customer_email, customers.address as customer_address');
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id = services.customer_id');
        $this->db->where('services.id', $id);
        return $this->db->get()->row();
    }

    public function getByCustomerId($customer_id) {
        return $this->db->get_where($this->table, ['customer_id' => $customer_id])->result();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function getServiceItems($service_id) {
        $this->db->select('service_items.*, spareparts.nama as sparepart_name');
        $this->db->from($this->items_table);
        $this->db->join('spareparts', 'spareparts.id = service_items.sparepart_id');
        $this->db->where('service_id', $service_id);
        return $this->db->get()->result();
    }

    public function insertServiceItem($data) {
        return $this->db->insert($this->items_table, $data);
    }

    public function deleteServiceItems($service_id) {
        return $this->db->delete($this->items_table, ['service_id' => $service_id]);
    }

    public function update_service_status($id, $status) {
        return $this->db->update($this->table, ['status' => $status], ['id' => $id]);
    }

    public function create_service($service_data, $sparepart_data) {
        $this->db->trans_start();
        
        // Generate service number
        $service_data['service_number'] = $this->generate_service_number();
        
        // Insert service data
        $this->db->insert($this->table, $service_data);
        $service_id = $this->db->insert_id();
        
        // Insert sparepart data
        if (!empty($sparepart_data)) {
            foreach ($sparepart_data as $item) {
                $item['service_id'] = $service_id;
                $this->db->insert($this->items_table, $item);
            }
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function update_service($id, $service_data, $sparepart_data) {
        $this->db->trans_start();
        
        // Update service data
        $this->db->update($this->table, $service_data, ['id' => $id]);
        
        // Delete existing sparepart data
        $this->db->delete($this->items_table, ['service_id' => $id]);
        
        // Insert new sparepart data
        if (!empty($sparepart_data)) {
            foreach ($sparepart_data as $item) {
                $item['service_id'] = $id;
                $this->db->insert($this->items_table, $item);
            }
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    private function generate_service_number() {
        $prefix = 'SRV' . date('Ymd');
        $this->db->select('service_number');
        $this->db->from($this->table);
        $this->db->like('service_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_number = intval(substr($result->service_number, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }
} 
