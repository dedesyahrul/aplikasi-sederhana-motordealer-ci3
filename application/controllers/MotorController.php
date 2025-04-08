<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MotorController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('MotorModel');
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
    }

    public function index() {
        $data['title'] = 'Daftar Motor';
        $data['motors'] = $this->MotorModel->get_all();
        $this->load->view('motor/index', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Motor Baru';
        $this->load->view('motor/add', $data);
    }

    public function store() {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = [
                'merk' => $this->input->post('merk'),
                'model' => $this->input->post('model'),
                'tahun' => $this->input->post('tahun'),
                'warna' => $this->input->post('warna'),
                'harga' => $this->input->post('harga'),
                'stok' => $this->input->post('stok'),
                'deskripsi' => $this->input->post('deskripsi'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->MotorModel->insert($data)) {
                $this->session->set_flashdata('success', 'Data motor berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat menambahkan data');
            }
            redirect('motor');
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Data Motor';
        $data['motor'] = $this->MotorModel->getById($id);
        
        if (!$data['motor']) {
            $this->session->set_flashdata('error', 'Data motor tidak ditemukan');
            redirect('motor');
        }

        $this->load->view('motor/edit', $data);
    }

    public function update($id) {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'merk' => $this->input->post('merk'),
                'model' => $this->input->post('model'),
                'tahun' => $this->input->post('tahun'),
                'warna' => $this->input->post('warna'),
                'harga' => $this->input->post('harga'),
                'stok' => $this->input->post('stok'),
                'deskripsi' => $this->input->post('deskripsi'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->MotorModel->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data motor berhasil diperbarui');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui data');
            }
            redirect('motor');
        }
    }

    public function delete($id) {
        $motor = $this->MotorModel->getById($id);
        
        if (!$motor) {
            $this->session->set_flashdata('error', 'Data motor tidak ditemukan');
            redirect('motor');
        }

        if ($this->MotorModel->delete($id)) {
            $this->session->set_flashdata('success', 'Data motor berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus data');
        }
        redirect('motor');
    }

    public function updateStock($id) {
        $motor = $this->MotorModel->getById($id);
        $quantity = $this->input->post('quantity');
        
        if (!$motor) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(['status' => false, 'message' => 'Data motor tidak ditemukan']));
        }

        if ($motor->stok < $quantity) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Stok tidak mencukupi']));
        }

        if ($this->MotorModel->updateStock($id, $quantity)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'Stok berhasil diperbarui']));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Terjadi kesalahan saat memperbarui stok']));
        }
    }

    private function _validate() {
        $this->form_validation->set_rules('merk', 'Merk Motor', 'required|trim');
        $this->form_validation->set_rules('model', 'Model', 'required|trim');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric|greater_than[1900]|less_than_equal_to['.date('Y').']');
        $this->form_validation->set_rules('warna', 'Warna', 'required|trim');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric|greater_than_equal_to[0]');
    }
}
