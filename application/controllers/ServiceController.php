<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(['ServiceModel', 'CustomerModel', 'SparepartModel']);
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
    }

    public function index() {
        $data['title'] = 'Daftar Servis';
        $data['services'] = $this->ServiceModel->getAllWithCustomer();
        $this->load->view('service/index', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Servis Baru';
        $data['customers'] = $this->CustomerModel->getAll();
        $data['spareparts'] = $this->SparepartModel->getAll();
        $this->load->view('service/add', $data);
    }

    public function store() {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $service_data = array(
            'customer_id' => $this->input->post('customer_id'),
            'vehicle_brand' => $this->input->post('vehicle_brand'),
            'vehicle_model' => $this->input->post('vehicle_model'),
            'vehicle_year' => $this->input->post('vehicle_year'),
            'vehicle_number' => $this->input->post('vehicle_number'),
            'complaints' => $this->input->post('complaints'),
            'service_cost' => $this->input->post('service_cost'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        );

        $sparepart_data = array();
        $sparepart_ids = $this->input->post('sparepart_id');
        $quantities = $this->input->post('quantity');
        
        if ($sparepart_ids) {
            foreach ($sparepart_ids as $key => $sparepart_id) {
                if ($sparepart_id && $quantities[$key] > 0) {
                    // Get sparepart price
                    $sparepart = $this->SparepartModel->getById($sparepart_id);
                    $price = $sparepart->harga;
                    $subtotal = $price * $quantities[$key];
                    
                    $sparepart_data[] = array(
                        'sparepart_id' => $sparepart_id,
                        'quantity' => $quantities[$key],
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                }
            }
        }

        // Calculate total cost
        $parts_cost = 0;
        foreach ($sparepart_data as $item) {
            $parts_cost += $item['subtotal'];
        }
        
        $service_data['parts_cost'] = $parts_cost;
        $service_data['total_cost'] = $service_data['service_cost'] + $parts_cost;

        if ($this->ServiceModel->create_service($service_data, $sparepart_data)) {
            $this->session->set_flashdata('success', 'Data service berhasil ditambahkan');
            redirect('service');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menambahkan data service');
            redirect('service/add');
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Data Servis';
        $data['service'] = $this->ServiceModel->getById($id);
        
        if (!$data['service']) {
            $this->session->set_flashdata('error', 'Data servis tidak ditemukan');
            redirect('service');
        }

        $data['customers'] = $this->CustomerModel->getAll();
        $data['spareparts'] = $this->SparepartModel->getAll();
        $data['service_items'] = $this->ServiceModel->getServiceItems($id);
        $this->load->view('service/edit', $data);
    }

    public function update($id) {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        $service_data = array(
            'customer_id' => $this->input->post('customer_id'),
            'vehicle_brand' => $this->input->post('vehicle_brand'),
            'vehicle_model' => $this->input->post('vehicle_model'),
            'vehicle_year' => $this->input->post('vehicle_year'),
            'vehicle_number' => $this->input->post('vehicle_number'),
            'complaints' => $this->input->post('complaints'),
            'diagnosis' => $this->input->post('diagnosis'),
            'service_cost' => $this->input->post('service_cost'),
            'status' => $this->input->post('status'),
            'mechanic_notes' => $this->input->post('mechanic_notes'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($service_data['status'] == 'completed') {
            $service_data['completed_at'] = date('Y-m-d H:i:s');
        }

        $sparepart_data = array();
        $sparepart_ids = $this->input->post('sparepart_id');
        $quantities = $this->input->post('quantity');
        
        if ($sparepart_ids) {
            foreach ($sparepart_ids as $key => $sparepart_id) {
                if ($sparepart_id && $quantities[$key] > 0) {
                    // Get sparepart price
                    $sparepart = $this->SparepartModel->getById($sparepart_id);
                    $price = $sparepart->harga;
                    $subtotal = $price * $quantities[$key];
                    
                    $sparepart_data[] = array(
                        'sparepart_id' => $sparepart_id,
                        'quantity' => $quantities[$key],
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                }
            }
        }

        // Calculate total cost
        $parts_cost = 0;
        foreach ($sparepart_data as $item) {
            $parts_cost += $item['subtotal'];
        }
        
        $service_data['parts_cost'] = $parts_cost;
        $service_data['total_cost'] = $service_data['service_cost'] + $parts_cost;

        if ($this->ServiceModel->update_service($id, $service_data, $sparepart_data)) {
            $this->session->set_flashdata('success', 'Data service berhasil diperbarui');
            redirect('service');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui data service');
            redirect('service/edit/' . $id);
        }
    }

    public function delete($id) {
        $service = $this->ServiceModel->getById($id);
        
        if (!$service) {
            $this->session->set_flashdata('error', 'Data servis tidak ditemukan');
            redirect('service');
        }

        $this->db->trans_start();
        $this->ServiceModel->deleteServiceItems($id);
        $this->ServiceModel->delete($id);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus data servis');
        } else {
            $this->session->set_flashdata('success', 'Data servis berhasil dihapus');
        }
        redirect('service');
    }

    public function view($id) {
        $data['title'] = 'Detail Servis';
        $data['service'] = $this->ServiceModel->getById($id);
        
        if (!$data['service']) {
            $this->session->set_flashdata('error', 'Data servis tidak ditemukan');
            redirect('service');
        }

        $data['service_items'] = $this->ServiceModel->getServiceItems($id);
        $this->load->view('service/view', $data);
    }

    public function update_status($id) {
        $status = $this->input->post('status');
        $data = ['status' => $status];
        
        if ($status == 'completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        
        if ($this->ServiceModel->update($id, $data)) {
            $this->session->set_flashdata('success', 'Status service berhasil diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui status service');
        }
        redirect('service');
    }

    private function _validate() {
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('vehicle_brand', 'Merk Kendaraan', 'required');
        $this->form_validation->set_rules('vehicle_model', 'Model Kendaraan', 'required');
        $this->form_validation->set_rules('vehicle_year', 'Tahun Kendaraan', 'required|numeric');
        $this->form_validation->set_rules('vehicle_number', 'Nomor Kendaraan', 'required');
        $this->form_validation->set_rules('complaints', 'Keluhan', 'required');
        $this->form_validation->set_rules('service_cost', 'Biaya Service', 'required|numeric');
    }
}
