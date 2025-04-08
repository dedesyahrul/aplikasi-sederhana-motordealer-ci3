<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerModel extends CI_Model {
    private $table = 'customers';

    public function getAll() {
        $this->db->order_by('name', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function getById($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function search($keyword) {
        $this->db->like('name', $keyword);
        $this->db->or_like('phone', $keyword);
        $this->db->or_like('email', $keyword);
        $this->db->or_like('identity_number', $keyword);
        return $this->db->get($this->table)->result();
    }

    public function getCustomerHistory($id) {
        // Get sales history
        $this->db->select('sales.*, SUM(sales_items.quantity) as total_items');
        $this->db->from('sales');
        $this->db->join('sales_items', 'sales.id = sales_items.sale_id');
        $this->db->where('customer_id', $id);
        $this->db->group_by('sales.id');
        $sales = $this->db->get()->result();

        // Get service history
        $this->db->select('*');
        $this->db->from('services');
        $this->db->where('customer_id', $id);
        $this->db->order_by('created_at', 'DESC');
        $services = $this->db->get()->result();

        return [
            'sales' => $sales,
            'services' => $services
        ];
    }
} 
