<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MotorController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('MotorModel');
    }

    public function index() {
        $page = $this->input->get('page') ?? 1;
        $limit = $this->input->get('limit') ?? 10;
        $search = $this->input->get('search');
        $sort = $this->input->get('sort');
        $order = $this->input->get('order') ?? 'asc';

        $offset = ($page - 1) * $limit;
        
        $total = $this->MotorModel->count_all($search);
        $motors = $this->MotorModel->get_all($limit, $offset, $search, $sort, $order);

        $this->send_response([
            'motors' => $motors,
            'meta' => [
                'page' => (int)$page,
                'limit' => (int)$limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
    }

    public function show($id) {
        $motor = $this->MotorModel->getById($id);
        
        if (!$motor) {
            $this->send_error('Motor not found', 404);
        }

        $this->send_response(['motor' => $motor]);
    }

    public function store() {
        $json = json_decode(file_get_contents('php://input'), true);
        $this->load->library('form_validation');

        $_POST = $json; // untuk mendukung form validation dengan JSON input
        
        $this->form_validation->set_rules('merk', 'Merk', 'required');
        $this->form_validation->set_rules('model', 'Model', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric');
        $this->form_validation->set_rules('warna', 'Warna', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->send_error(validation_errors());
        }

        $data = [
            'merk' => $json['merk'],
            'model' => $json['model'],
            'tahun' => $json['tahun'],
            'warna' => $json['warna'],
            'harga' => $json['harga'],
            'stok' => $json['stok'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->MotorModel->insert($data)) {
            $this->send_response(['motor' => $data], 'Motor created successfully');
        } else {
            $this->send_error('Failed to create motor');
        }
    }

    public function update($id) {
        $motor = $this->MotorModel->getById($id);
        
        if (!$motor) {
            $this->send_error('Motor not found', 404);
        }

        $json = json_decode(file_get_contents('php://input'), true);
        $this->load->library('form_validation');

        $_POST = $json; // untuk mendukung form validation dengan JSON input

        $this->form_validation->set_rules('merk', 'Merk', 'required');
        $this->form_validation->set_rules('model', 'Model', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric');
        $this->form_validation->set_rules('warna', 'Warna', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->send_error(validation_errors());
        }

        $data = [
            'merk' => $json['merk'],
            'model' => $json['model'],
            'tahun' => $json['tahun'],
            'warna' => $json['warna'],
            'harga' => $json['harga'],
            'stok' => $json['stok'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->MotorModel->update($id, $data)) {
            $this->send_response(['motor' => $data], 'Motor updated successfully');
        } else {
            $this->send_error('Failed to update motor');
        }
    }

    public function delete($id) {
        $motor = $this->MotorModel->getById($id);
        
        if (!$motor) {
            $this->send_error('Motor not found', 404);
        }

        if ($this->MotorModel->delete($id)) {
            $this->send_response(null, 'Motor deleted successfully');
        } else {
            $this->send_error('Failed to delete motor');
        }
    }
} 
