<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['SalesModel', 'MotorModel', 'CustomerModel', 'SparepartModel']);
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
    }

    public function index() {
        $data['title'] = 'Data Penjualan';
        $data['sales'] = $this->SalesModel->getAll();
        
        // Format status untuk tampilan
        if (!empty($data['sales'])) {
            foreach ($data['sales'] as &$sale) {
                $sale->status_label = $this->_get_status_label($sale->status);
                $sale->status_class = $this->_get_status_class($sale->status);
                
                // Format items sudah ditangani di model
                if (!empty($sale->items)) {
                    foreach ($sale->items as &$item) {
                        $item->display_name = $item->nama_item;
                    }
                }
            }
        }
        
        $this->load->view('sales/index', $data);
    }

    private function _get_status_label($status) {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'completed' => 'Lunas',
            'cancelled' => 'Dibatalkan'
        ];
        return isset($labels[strtolower($status)]) ? $labels[strtolower($status)] : 'Tidak Diketahui';
    }

    private function _get_status_class($status) {
        $classes = [
            'pending' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];
        return isset($classes[strtolower($status)]) ? $classes[strtolower($status)] : 'secondary';
    }

    public function add() {
        $data['title'] = 'Tambah Penjualan';
        $data['motors'] = $this->MotorModel->get_available_motors();
        $data['spareparts'] = $this->SparepartModel->get_available_spareparts();
        $data['customers'] = $this->CustomerModel->getAll();
        
        // Debug log
        log_message('debug', 'Spareparts data: ' . print_r($data['spareparts'], true));
        
        if (empty($data['motors']) && empty($data['spareparts'])) {
            $this->session->set_flashdata('error', 'Tidak ada item yang tersedia untuk dijual');
            redirect('sales');
        }
        
        $this->load->view('sales/add', $data);
    }

    public function store() {
        // Validasi input
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('items[]', 'Items', 'required');
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('sales/add');
            return;
        }

        $sale_type = $this->input->post('sale_type');
        $items = $this->input->post('items');
        $quantities = $this->input->post('quantities');
        $total_amount = 0;

        // Validasi stok dan hitung total
        foreach ($items as $key => $item) {
            list($type, $id) = explode('_', $item);
            $quantity = intval($quantities[$key]);

            if ($quantity <= 0) {
                $this->session->set_flashdata('error', 'Jumlah item harus lebih dari 0');
                redirect('sales/add');
                return;
            }

            if ($type == 'motor') {
                $motor = $this->MotorModel->getById($id);
                if (!$motor || $motor->stok < $quantity) {
                    $this->session->set_flashdata('error', 'Stok motor tidak mencukupi');
                    redirect('sales/add');
                    return;
                }
                $total_amount += $motor->harga * $quantity;
            } else {
                $sparepart = $this->SparepartModel->getById($id);
                if (!$sparepart || $sparepart->stok < $quantity) {
                    $this->session->set_flashdata('error', 'Stok sparepart tidak mencukupi');
                    redirect('sales/add');
                    return;
                }
                $total_amount += $sparepart->harga * $quantity;
            }
        }

        $this->db->trans_start();

        try {
            // Insert data penjualan
            $sale_data = [
                'invoice_number' => $this->_generate_invoice_number(),
                'customer_id' => $this->input->post('customer_id'),
                'total_amount' => $total_amount,
                'payment_method' => $this->input->post('metode_pembayaran'),
                'status' => $this->input->post('metode_pembayaran') == 'cash' ? 'completed' : 'pending',
                'notes' => $this->input->post('keterangan'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $sale_id = $this->SalesModel->insert($sale_data);
            
            if ($sale_id) {
                // Insert items penjualan
                foreach ($items as $key => $item) {
                    list($type, $id) = explode('_', $item);
                    $quantity = intval($quantities[$key]);
                    
                    if ($type == 'motor') {
                        $motor = $this->MotorModel->getById($id);
                        $item_data = [
                            'sale_id' => $sale_id,
                            'item_type' => 'motor',
                            'item_id' => $id,
                            'quantity' => $quantity,
                            'price' => $motor->harga,
                            'subtotal' => $motor->harga * $quantity,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        if (!$this->SalesModel->insertSalesItem($item_data)) {
                            throw new Exception('Gagal menyimpan item motor');
                        }
                        if (!$this->MotorModel->updateStock($id, $quantity)) {
                            throw new Exception('Gagal mengupdate stok motor');
                        }
                    } else {
                        $sparepart = $this->SparepartModel->getById($id);
                        $item_data = [
                            'sale_id' => $sale_id,
                            'item_type' => 'sparepart',
                            'item_id' => $id,
                            'quantity' => $quantity,
                            'price' => $sparepart->harga,
                            'subtotal' => $sparepart->harga * $quantity,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        if (!$this->SalesModel->insertSalesItem($item_data)) {
                            throw new Exception('Gagal menyimpan item sparepart');
                        }
                        if (!$this->SparepartModel->updateStock($id, $quantity, false)) {
                            throw new Exception('Gagal mengupdate stok sparepart');
                        }
                    }
                }
                
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Terjadi kesalahan dalam transaksi database');
                }

                $this->session->set_flashdata('success', 'Data penjualan berhasil ditambahkan');
                redirect('sales');
            } else {
                throw new Exception('Gagal menyimpan data penjualan');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', $e->getMessage());
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

        // Get sale items
        $data['sale_items'] = $this->SalesModel->getSalesItems($id);
        
        // Format items untuk tampilan
        foreach ($data['sale_items'] as &$item) {
            $item->display_name = $item->item_name;
            if ($item->item_type === 'motor') {
                $motor = $this->MotorModel->getById($item->item_id);
                if ($motor) {
                    $item->motor_details = $motor;
                }
            } else {
                $sparepart = $this->SparepartModel->getById($item->item_id);
                if ($sparepart) {
                    $item->sparepart_details = $sparepart;
                }
            }
        }

        // Get available items for dropdown
        $data['motors'] = $this->MotorModel->get_available_motors();
        $data['spareparts'] = $this->SparepartModel->get_available_spareparts();
        $data['customers'] = $this->CustomerModel->getAll();
        
        // Debug log untuk memastikan data
        log_message('debug', 'Sale data: ' . print_r($data['sale'], true));
        
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

    public function confirm_payment($id) {
        $sale = $this->SalesModel->getById($id);
        
        if (!$sale) {
            $this->session->set_flashdata('error', 'Data penjualan tidak ditemukan');
            redirect('sales');
        }

        if ($sale->status !== 'pending') {
            $this->session->set_flashdata('error', 'Status penjualan tidak dapat dikonfirmasi');
            redirect('sales');
        }

        if ($this->SalesModel->confirmPayment($id)) {
            // Tambahkan log pembayaran jika diperlukan
            $this->session->set_flashdata('success', 'Pembayaran berhasil dikonfirmasi');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengkonfirmasi pembayaran');
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

        // Get sales items with formatted display
        $data['sale_items'] = $this->SalesModel->getSalesItems($id);
        
        // Format items untuk tampilan tidak perlu lagi karena sudah ada nama_item
        foreach ($data['sale_items'] as &$item) {
            $item->display_name = $item->item_name;
            if ($item->item_type === 'motor') {
                $item->details = (object)[
                    'merk' => $item->merk ?? '-',
                    'model' => $item->model ?? '-',
                    'tahun' => $item->tahun ?? '-',
                    'warna' => $item->warna ?? '-'
                ];
            } else {
                $sparepart = $this->SparepartModel->getById($item->item_id);
                if ($sparepart) {
                    $item->details = (object)[
                        'kode' => $sparepart->kode,
                        'kategori' => $sparepart->kategori
                    ];
                }
            }
        }
        
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
        
        // Optimasi query invoice
        $this->db->select('SUBSTRING(invoice_number, -4) as last_number', false)
                 ->from('sales')
                 ->where('DATE(created_at)', date('Y-m-d'))
                 ->order_by('id', 'DESC')
                 ->limit(1);
        
        $last_invoice = $this->db->get()->row();
        
        if ($last_invoice) {
            $next_number = str_pad((int)$last_invoice->last_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_number = '0001';
        }

        return $prefix . $date . $next_number;
    }
}
