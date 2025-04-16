<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['SalesModel', 'MotorModel', 'CustomerModel']);
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
    }

    public function index() {
        $data['title'] = 'Data Penjualan';
        $data['sales'] = $this->SalesModel->getAll();
        $this->load->view('sales/index', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Penjualan';
        $data['motors'] = $this->MotorModel->get_available_motors();
        $data['customers'] = $this->CustomerModel->getAll();
        
        if (empty($data['motors'])) {
            $this->session->set_flashdata('error', 'Tidak ada motor yang tersedia untuk dijual');
            redirect('sales');
        }
        
        $this->load->view('sales/add', $data);
    }

    public function store() {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $motor_id = $this->input->post('motor_id');
        $motor = $this->MotorModel->getById($motor_id);
        $harga_jual = $this->input->post('harga_jual');

        if (!$motor || $motor->stok <= 0) {
            $this->session->set_flashdata('error', 'Motor tidak tersedia atau stok habis');
            redirect('sales/add');
            return;
        }

        $this->db->trans_start();

        // Insert data penjualan
        $sale_data = [
            'invoice_number' => $this->_generate_invoice_number(),
            'customer_id' => $this->input->post('customer_id'),
            'total_amount' => $harga_jual,
            'payment_method' => $this->input->post('metode_pembayaran'),
            'status' => $this->input->post('metode_pembayaran') == 'cash' ? 'completed' : 'pending',
            'notes' => $this->input->post('keterangan'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $sale_id = $this->SalesModel->insert($sale_data);
        
        if ($sale_id) {
            // Insert item penjualan
            $item_data = [
                'sale_id' => $sale_id,
                'item_type' => 'motor',
                'item_id' => $motor_id,
                'quantity' => 1,
                'price' => $harga_jual,
                'subtotal' => $harga_jual,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->SalesModel->insertSalesItem($item_data);
            
            // Update stok motor
            $this->MotorModel->updateStock($motor_id, -1); // Kurangi stok
            
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan data penjualan');
                redirect('sales/add');
            } else {
                $this->session->set_flashdata('success', 'Data penjualan berhasil ditambahkan');
                redirect('sales');
            }
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan data penjualan');
            redirect('sales/add');
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Penjualan';
        $data['sale'] = $this->SalesModel->getSaleWithDetails($id);
        
        if (!$data['sale']) {
            $this->session->set_flashdata('error', 'Data penjualan tidak ditemukan');
            redirect('sales');
        }

        $data['sale_items'] = $this->SalesModel->getSalesItems($id);
        $data['motors'] = $this->MotorModel->get_all();
        $data['customers'] = $this->CustomerModel->getAll();
        $this->load->view('sales/edit', $data);
    }

    public function update($id) {
        $this->_validate();

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        $sale = $this->SalesModel->getById($id);
        if (!$sale) {
            $this->session->set_flashdata('error', 'Data penjualan tidak ditemukan');
            redirect('sales');
        }

        $data = [
            'customer_id' => $this->input->post('customer_id'),
            'total_amount' => $this->input->post('harga_jual'),
            'payment_method' => $this->input->post('metode_pembayaran'),
            'notes' => $this->input->post('keterangan'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_start();

        if ($this->SalesModel->update($id, $data)) {
            // Update sales item
            $this->SalesModel->deleteSalesItems($id);
            
            $item_data = [
                'sale_id' => $id,
                'item_type' => 'motor',
                'item_id' => $this->input->post('motor_id'),
                'quantity' => 1,
                'price' => $this->input->post('harga_jual'),
                'subtotal' => $this->input->post('harga_jual'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->SalesModel->insertSalesItem($item_data);
            
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui data penjualan');
            } else {
                $this->session->set_flashdata('success', 'Data penjualan berhasil diperbarui');
            }
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui data penjualan');
        }
        redirect('sales');
    }

    public function delete($id) {
        $sale = $this->SalesModel->getById($id);
        
        if (!$sale) {
            $this->session->set_flashdata('error', 'Data penjualan tidak ditemukan');
            redirect('sales');
        }

        $this->db->trans_start();
        
        // Delete sales items first
        $this->SalesModel->deleteSalesItems($id);
        
        // Then delete the sale
        if ($this->SalesModel->delete($id)) {
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus data penjualan');
            } else {
                $this->session->set_flashdata('success', 'Data penjualan berhasil dihapus');
            }
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menghapus data penjualan');
        }
        redirect('sales');
    }

    public function view($id) {
        $data['title'] = 'Detail Penjualan';
        $data['sale'] = $this->SalesModel->getSaleWithDetails($id);
        
        if (!$data['sale']) {
            show_404();
        }

        // Get customer data
        $data['customer'] = $this->CustomerModel->getById($data['sale']->customer_id);
        
        // Get sales items
        $data['sale_items'] = $this->SalesModel->getSalesItems($id);
        
        $this->load->view('sales/view', $data);
    }

    private function _validate() {
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('motor_id', 'Motor', 'required');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required');
    }

    private function _generate_invoice_number() {
        $prefix = 'INV';
        $date = date('Ymd');
        $last_invoice = $this->db->select('invoice_number')
                                ->from('sales')
                                ->like('invoice_number', $prefix . $date, 'after')
                                ->order_by('id', 'DESC')
                                ->limit(1)
                                ->get()
                                ->row();

        if ($last_invoice) {
            $last_number = substr($last_invoice->invoice_number, -4);
            $next_number = str_pad((int)$last_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_number = '0001';
        }

        return $prefix . $date . $next_number;
    }
}
