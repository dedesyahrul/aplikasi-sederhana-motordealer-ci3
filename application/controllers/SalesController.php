<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('SalesModel');
        $this->load->model('MotorModel');
        $this->load->model('CustomerModel');
    }

    public function index() {
        $data['sales'] = $this->SalesModel->getAll();
        $this->load->view('sales/index', $data);
    }

    public function add() {
        $data['motors'] = $this->MotorModel->get_available_motors();
        $data['customers'] = $this->CustomerModel->get_all_customers();
        $this->load->view('sales/add', $data);
    }

    public function store() {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $data = array(
            'motor_id' => $this->input->post('motor_id'),
            'customer_id' => $this->input->post('customer_id'),
            'tanggal_jual' => $this->input->post('tanggal_jual'),
            'harga_jual' => $this->input->post('harga_jual'),
            'metode_pembayaran' => $this->input->post('metode_pembayaran'),
            'keterangan' => $this->input->post('keterangan')
        );

        if ($this->SalesModel->create_sale($data)) {
            $this->session->set_flashdata('success', 'Data penjualan berhasil ditambahkan');
            redirect('sales');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menambahkan data penjualan');
            redirect('sales/add');
        }
    }

    public function view($id) {
        $data['sale'] = $this->SalesModel->get_sale_by_id($id);
        if (!$data['sale']) {
            show_404();
        }
        $this->load->view('sales/view', $data);
    }

    public function edit($id) {
        $data['sale'] = $this->SalesModel->get_sale_by_id($id);
        if (!$data['sale']) {
            show_404();
        }
        $data['motors'] = $this->MotorModel->get_all_motors();
        $data['customers'] = $this->CustomerModel->get_all_customers();
        $this->load->view('sales/edit', $data);
    }

    public function update($id) {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        $data = array(
            'motor_id' => $this->input->post('motor_id'),
            'customer_id' => $this->input->post('customer_id'),
            'tanggal_jual' => $this->input->post('tanggal_jual'),
            'harga_jual' => $this->input->post('harga_jual'),
            'metode_pembayaran' => $this->input->post('metode_pembayaran'),
            'keterangan' => $this->input->post('keterangan')
        );

        if ($this->SalesModel->update_sale($id, $data)) {
            $this->session->set_flashdata('success', 'Data penjualan berhasil diperbarui');
            redirect('sales');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui data penjualan');
            redirect('sales/edit/' . $id);
        }
    }

    public function delete($id) {
        if ($this->SalesModel->delete_sale($id)) {
            $this->session->set_flashdata('success', 'Data penjualan berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus data penjualan');
        }
        redirect('sales');
    }

    private function _validate() {
        $this->form_validation->set_rules('motor_id', 'Motor', 'required');
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('tanggal_jual', 'Tanggal Jual', 'required');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required');
    }
}
