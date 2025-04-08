<?php

class SparepartController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('SparepartModel');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['spareparts'] = $this->SparepartModel->getAll();
        $this->load->view('sparepart/index', $data);
    }

    public function add() {
        $this->load->view('sparepart/add');
    }

    public function store() {
        $this->form_validation->set_rules('nama', 'Nama Sparepart', 'required');
        $this->form_validation->set_rules('kode', 'Kode', 'required|is_unique[spareparts.kode]');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('sparepart/add');
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'kode' => $this->input->post('kode'),
                'kategori' => $this->input->post('kategori'),
                'harga' => $this->input->post('harga'),
                'stok' => $this->input->post('stok'),
                'deskripsi' => $this->input->post('deskripsi')
            );

            $this->SparepartModel->insert($data);
            $this->session->set_flashdata('success', 'Data sparepart berhasil ditambahkan');
            redirect('sparepart');
        }
    }

    public function edit($id) {
        $data['sparepart'] = $this->SparepartModel->getById($id);
        if (!$data['sparepart']) {
            show_404();
        }
        $this->load->view('sparepart/edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('nama', 'Nama Sparepart', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['sparepart'] = $this->SparepartModel->getById($id);
            $this->load->view('sparepart/edit', $data);
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'kategori' => $this->input->post('kategori'),
                'harga' => $this->input->post('harga'),
                'stok' => $this->input->post('stok'),
                'deskripsi' => $this->input->post('deskripsi')
            );

            $this->SparepartModel->update($id, $data);
            $this->session->set_flashdata('success', 'Data sparepart berhasil diperbarui');
            redirect('sparepart');
        }
    }

    public function delete($id) {
        $sparepart = $this->SparepartModel->getById($id);
        if (!$sparepart) {
            show_404();
        }

        $this->SparepartModel->delete($id);
        $this->session->set_flashdata('success', 'Data sparepart berhasil dihapus');
        redirect('sparepart');
    }

    public function updateStock($id, $quantity) {
        $sparepart = $this->SparepartModel->getById($id);
        if (!$sparepart) {
            return $this->output->set_content_type('application/json')
                              ->set_status_header(404)
                              ->set_output(json_encode(['message' => 'Sparepart tidak ditemukan']));
        }

        $this->SparepartModel->updateStock($id, $quantity);
        return $this->output->set_content_type('application/json')
                           ->set_output(json_encode(['message' => 'Stok berhasil diperbarui']));
    }
}
