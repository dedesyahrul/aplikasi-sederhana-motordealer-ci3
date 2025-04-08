<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryModel extends CI_Model {
    private $table = 'categories';

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

    public function getCategoryWithItemCount() {
        $this->db->select('categories.*, COUNT(spareparts.id) as item_count');
        $this->db->from($this->table);
        $this->db->join('spareparts', 'spareparts.kategori = categories.name', 'left');
        $this->db->group_by('categories.id');
        $this->db->order_by('categories.name', 'ASC');
        return $this->db->get()->result();
    }
} 
