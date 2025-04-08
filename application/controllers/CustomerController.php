<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('CustomerModel');
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
    }

    public function index() {
        $data['title'] = 'Daftar Pelanggan';
        $data['customers'] = $this->CustomerModel->getAll();
        $this->load->view('customer/index', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Pelanggan Baru';
        $this->load->view('customer/add', $data);
    }

    public function store() {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = [
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'identity_type' => $this->input->post('identity_type'),
                'identity_number' => $this->input->post('identity_number'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->CustomerModel->insert($data)) {
                $this->session->set_flashdata('success', 'Data pelanggan berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat menambahkan data');
            }
            redirect('customer');
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Data Pelanggan';
        $data['customer'] = $this->CustomerModel->getById($id);
        
        if (!$data['customer']) {
            $this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan');
            redirect('customer');
        }

        $this->load->view('customer/edit', $data);
    }

    public function update($id) {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'identity_type' => $this->input->post('identity_type'),
                'identity_number' => $this->input->post('identity_number'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->CustomerModel->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data pelanggan berhasil diperbarui');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui data');
            }
            redirect('customer');
        }
    }

    public function delete($id) {
        $customer = $this->CustomerModel->getById($id);
        
        if (!$customer) {
            $this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan');
            redirect('customer');
        }

        if ($this->CustomerModel->delete($id)) {
            $this->session->set_flashdata('success', 'Data pelanggan berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus data');
        }
        redirect('customer');
    }

    public function view($id) {
        $data['title'] = 'Detail Pelanggan';
        $data['customer'] = $this->CustomerModel->getById($id);
        
        if (!$data['customer']) {
            $this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan');
            redirect('customer');
        }

        // Get customer's purchase history
        $this->load->model('SalesModel');
        $data['sales'] = $this->SalesModel->getByCustomerId($id);

        // Get customer's service history
        $this->load->model('ServiceModel');
        $data['services'] = $this->ServiceModel->getByCustomerId($id);

        $this->load->view('customer/view', $data);
    }

    public function search() {
        $keyword = $this->input->get('keyword');
        $customers = $this->CustomerModel->search($keyword);
        
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($customers));
    }

    private function _validate() {
        $this->form_validation->set_rules('name', 'Nama Pelanggan', 'required|trim');
        $this->form_validation->set_rules('phone', 'Nomor Telepon', 'required|trim|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('identity_type', 'Jenis Identitas', 'required|in_list[ktp,sim,passport]');
        $this->form_validation->set_rules('identity_number', 'Nomor Identitas', 'required|trim|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('address', 'Alamat', 'required|trim');
    }
}
