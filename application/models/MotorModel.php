<?php

class MotorModel extends CI_Model {
    private $table = 'motors';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Helper function untuk membersihkan string
    private function stripString($str) {
        return trim(strip_tags($str));
    }

    public function get_all($limit = null, $offset = null, $search = null, $sort = null, $order = 'asc') {
        if ($search) {
            $this->db->group_start()
                     ->like('merk', $search)
                     ->or_like('model', $search)
                     ->or_like('warna', $search)
                     ->group_end();
        }

        if ($sort) {
            $this->db->order_by($sort, $order);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get($this->table)->result();
    }

    public function get_available_motors() {
        $this->db->where('stok >', 0);
        $this->db->order_by('merk', 'asc');
        $this->db->order_by('model', 'asc');
        return $this->db->get($this->table)->result();
    }

    public function count_all($search = null) {
        if ($search) {
            $this->db->group_start()
                     ->like('merk', $search)
                     ->or_like('model', $search)
                     ->or_like('warna', $search)
                     ->group_end();
        }

        return $this->db->count_all_results($this->table);
    }

    public function getById($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data) {
        // Membersihkan string untuk field yang relevan
        if (isset($data['merk'])) {
            $data['merk'] = $this->stripString($data['merk']);
        }
        if (isset($data['model'])) {
            $data['model'] = $this->stripString($data['model']);
        }
        if (isset($data['warna'])) {
            $data['warna'] = $this->stripString($data['warna']);
        }
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        // Membersihkan string untuk field yang relevan
        if (isset($data['merk'])) {
            $data['merk'] = $this->stripString($data['merk']);
        }
        if (isset($data['model'])) {
            $data['model'] = $this->stripString($data['model']);
        }
        if (isset($data['warna'])) {
            $data['warna'] = $this->stripString($data['warna']);
        }
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function updateStock($id, $quantity) {
        $this->db->set('stok', 'stok - ' . $quantity, FALSE);
        $this->db->where('id', $id);
        return $this->db->update($this->table);
    }
} 
