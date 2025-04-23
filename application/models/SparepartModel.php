<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SparepartModel extends CI_Model {
    private $table = 'spareparts';

    public function getAll() {
        $this->db->order_by('nama', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_available_spareparts() {
        $this->db->where('stok >', 0);
        $this->db->order_by('nama', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function getById($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function getByKode($kode) {
        return $this->db->get_where($this->table, ['kode' => $kode])->row();
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

    public function updateStock($id, $quantity, $is_addition = false) {
        $sparepart = $this->getById($id);
        if (!$sparepart) {
            return false;
        }

        // Validasi quantity
        if (!is_numeric($quantity) || $quantity <= 0) {
            return false;
        }

        $new_stock = $is_addition ? 
            ($sparepart->stok + $quantity) : 
            ($sparepart->stok - $quantity);

        // Validasi stok tidak boleh negatif
        if ($new_stock < 0) {
            return false;
        }

        return $this->update($id, [
            'stok' => $new_stock,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function search($keyword) {
        $this->db->like('kode', $keyword);
        $this->db->or_like('nama', $keyword);
        $this->db->or_like('kategori', $keyword);
        return $this->db->get($this->table)->result();
    }

    public function getByCategory($category) {
        return $this->db->get_where($this->table, ['kategori' => $category])->result();
    }

    public function getLowStock($threshold = 5) {
        $this->db->where('stok <=', $threshold);
        $this->db->order_by('stok', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function getUsageHistory($id, $start_date = null, $end_date = null) {
        $this->db->select('service_items.*, services.service_number, services.created_at, customers.name as customer_name');
        $this->db->from('service_items');
        $this->db->join('services', 'services.id = service_items.service_id');
        $this->db->join('customers', 'customers.id = services.customer_id');
        $this->db->where('service_items.sparepart_id', $id);
        
        if ($start_date && $end_date) {
            $this->db->where('services.created_at >=', $start_date);
            $this->db->where('services.created_at <=', $end_date);
        }
        
        $this->db->order_by('services.created_at', 'DESC');
        return $this->db->get()->result();
    }
} 
