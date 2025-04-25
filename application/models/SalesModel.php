<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesModel extends CI_Model {
    private $table = 'sales';
    private $items_table = 'sales_items';
    private $cache_time = 300; // 5 menit cache

    public function __construct() {
        parent::__construct();
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }

    public function getAll() {
        $cache_key = 'sales_all_' . date('Y-m-d_H');
        
        if (!$result = $this->cache->get($cache_key)) {
            // Get sales with customer info
            $this->db->select('
                s.id,
                s.invoice_number,
                DATE_FORMAT(s.created_at, "%d %M %Y") as created_at,
                s.total_amount,
                s.payment_method,
                s.status,
                TRIM(c.name) as nama_customer,
                TRIM(c.phone) as customer_phone'
            );
            $this->db->from($this->table . ' s');
            $this->db->join('customers c', 'c.id = s.customer_id');
            $this->db->order_by('s.created_at', 'DESC');
            $sales = $this->db->get()->result();

            // Konversi nama bulan ke bahasa Indonesia
            foreach ($sales as $sale) {
                $sale->created_at = $this->_formatTanggalIndonesia($sale->created_at);
            }

            if (!empty($sales)) {
                $sale_ids = array_column($sales, 'id');
                
                // Get all motor items in single query
                $this->db->select('
                    si.sale_id,
                    si.quantity,
                    TRIM(CONCAT(m.merk, " ", m.model, " (", m.tahun, ")")) as nama_item,
                    "motor" as item_type,
                    TRIM(m.merk) as merk,
                    TRIM(m.model) as model,
                    m.tahun,
                    TRIM(m.warna) as warna'
                );
                $this->db->from('sales_items si');
                $this->db->join('motors m', 'm.id = si.item_id');
                $this->db->where('si.item_type', 'motor');
                $this->db->where_in('si.sale_id', $sale_ids);
                $motor_items = $this->db->get()->result();

                // Get all sparepart items in single query
                $this->db->select('
                    si.sale_id,
                    si.quantity,
                    TRIM(sp.nama) as nama_item,
                    "sparepart" as item_type'
                );
                $this->db->from('sales_items si');
                $this->db->join('spareparts sp', 'sp.id = si.item_id');
                $this->db->where('si.item_type', 'sparepart');
                $this->db->where_in('si.sale_id', $sale_ids);
                $sparepart_items = $this->db->get()->result();

                // Index items by sale_id for faster lookup
                $items_by_sale = [];
                foreach ($motor_items as $item) {
                    $items_by_sale[$item->sale_id][] = $item;
                }
                foreach ($sparepart_items as $item) {
                    $items_by_sale[$item->sale_id][] = $item;
                }

                // Combine data efficiently
                foreach ($sales as $sale) {
                    $sale->items = [];
                    $sale->item_types = [];

                    if (isset($items_by_sale[$sale->id])) {
                        foreach ($items_by_sale[$sale->id] as $item) {
                            if ($item->item_type === 'motor') {
                                $sale->items[] = (object)[
                                    'item_type' => 'motor',
                                    'quantity' => $item->quantity,
                                    'nama_item' => $item->nama_item,
                                    'merk' => $item->merk,
                                    'model' => $item->model,
                                    'tahun' => $item->tahun,
                                    'warna' => $item->warna
                                ];
                                if (!in_array('motor', $sale->item_types)) {
                                    $sale->item_types[] = 'motor';
                                }
                            } else {
                                $sale->items[] = (object)[
                                    'item_type' => 'sparepart',
                                    'quantity' => $item->quantity,
                                    'nama_item' => $item->nama_item
                                ];
                                if (!in_array('sparepart', $sale->item_types)) {
                                    $sale->item_types[] = 'sparepart';
                                }
                            }
                        }
                    }
                }
            }

            $result = $sales;
            $this->cache->save($cache_key, $result, $this->cache_time);
        }
        
        return $result;
    }

    public function getById($id) {
        $cache_key = 'sale_' . $id;
        
        if (!$result = $this->cache->get($cache_key)) {
            $this->db->select('
                s.id,
                s.invoice_number,
                s.created_at,
                s.updated_at,
                s.total_amount,
                s.payment_method,
                s.status,
                c.name as customer_name'
            );
            $this->db->from($this->table . ' s');
            $this->db->join('customers c', 'c.id = s.customer_id');
            $this->db->where('s.id', $id);
            
            $result = $this->db->get()->row();
            $this->cache->save($cache_key, $result, $this->cache_time);
        }
        
        return $result;
    }

    public function getSaleWithDetails($id) {
        $cache_key = 'sale_details_' . $id;
        
        if (!$result = $this->cache->get($cache_key)) {
            $this->db->select('
                s.id,
                s.invoice_number,
                s.created_at,
                s.updated_at,
                s.total_amount,
                s.payment_method,
                s.status,
                s.notes,
                s.customer_id,
                c.name as customer_name,
                c.phone as customer_phone,
                c.email as customer_email,
                c.address as customer_address,
                c.identity_type,
                c.identity_number'
            );
            $this->db->from($this->table . ' s');
            $this->db->join('customers c', 'c.id = s.customer_id');
            $this->db->where('s.id', $id);
            
            $result = $this->db->get()->row();
            $this->cache->save($cache_key, $result, $this->cache_time);
        }
        
        return $result;
    }

    public function getByCustomerId($customer_id) {
        $this->db->where('customer_id', $customer_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function insert($data) {
        $this->db->trans_start();
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            $this->_clearCache();
            return $insert_id;
        }
        return false;
    }

    public function update($id, $data) {
        $this->db->trans_start();
        $result = $this->db->update($this->table, $data, ['id' => $id]);
        $this->db->trans_complete();
        
        if ($this->db->trans_status() && $result) {
            $this->_clearCache();
            return true;
        }
        return false;
    }

    public function delete($id) {
        $this->db->trans_start();
        $this->deleteSalesItems($id);
        $result = $this->db->delete($this->table, ['id' => $id]);
        $this->db->trans_complete();
        
        if ($this->db->trans_status() && $result) {
            $this->_clearCache();
            return true;
        }
        return false;
    }

    public function getSalesItems($sale_id) {
        $cache_key = 'sale_items_' . $sale_id;
        
        if (!$result = $this->cache->get($cache_key)) {
            $items = [];

            // Get motor items
            $this->db->select('
                si.id,
                si.sale_id,
                si.item_id,
                si.item_type,
                si.quantity,
                si.price,
                si.subtotal,
                CONCAT(m.merk, " ", m.model, " ", m.tahun) as item_name,
                m.merk,
                m.model,
                m.tahun,
                m.warna'
            );
            $this->db->from($this->items_table . ' si');
            $this->db->join('motors m', 'm.id = si.item_id');
            $this->db->where('si.sale_id', $sale_id);
            $this->db->where('si.item_type', 'motor');
            $motor_items = $this->db->get()->result();

            // Get sparepart items
            $this->db->select('
                si.id,
                si.sale_id,
                si.item_id,
                si.item_type,
                si.quantity,
                si.price,
                si.subtotal,
                sp.nama as item_name'
            );
            $this->db->from($this->items_table . ' si');
            $this->db->join('spareparts sp', 'sp.id = si.item_id');
            $this->db->where('si.sale_id', $sale_id);
            $this->db->where('si.item_type', 'sparepart');
            $sparepart_items = $this->db->get()->result();

            $result = array_merge($motor_items, $sparepart_items);
            $this->cache->save($cache_key, $result, $this->cache_time);
        }
        
        return $result;
    }

    public function insertSalesItem($data) {
        $result = $this->db->insert($this->items_table, $data);
        if ($result) {
            $this->_clearCache();
        }
        return $result;
    }

    public function deleteSalesItems($sale_id) {
        $result = $this->db->delete($this->items_table, ['sale_id' => $sale_id]);
        if ($result) {
            $this->_clearCache();
        }
        return $result;
    }

    public function getLastInvoiceNumber() {
        $this->db->select('invoice_number');
        $this->db->from($this->table);
        $this->db->where('invoice_number LIKE', 'INV' . date('Ymd') . '%');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get()->row();
        
        if ($result) {
            return substr($result->invoice_number, -4);
        }
        return '0000';
    }

    public function getSalesSummary($start_date = null, $end_date = null) {
        $cache_key = 'sales_summary_' . ($start_date ?? 'all') . '_' . ($end_date ?? 'all');
        
        if (!$result = $this->cache->get($cache_key)) {
            $this->db->select('
                COUNT(id) as total_transactions,
                SUM(total_amount) as total_sales'
            );
            $this->db->from($this->table);
            $this->db->where('status', 'completed');
            
            if ($start_date && $end_date) {
                $this->db->where('created_at >=', $start_date);
                $this->db->where('created_at <=', $end_date);
            }
            
            $result = $this->db->get()->row();
            $this->cache->save($cache_key, $result, $this->cache_time);
        }
        
        return $result;
    }

    public function getTopSellingItems($limit = 10, $start_date = null, $end_date = null) {
        $cache_key = 'top_selling_' . $limit . '_' . ($start_date ?? 'all') . '_' . ($end_date ?? 'all');
        
        if (!$result = $this->cache->get($cache_key)) {
            $this->db->select('
                si.item_type,
                si.item_id,
                CASE 
                    WHEN si.item_type = "motor" THEN CONCAT(m.merk, " ", m.model)
                    ELSE sp.nama 
                END as item_name,
                SUM(si.quantity) as total_quantity,
                SUM(si.subtotal) as total_sales'
            );
            $this->db->from($this->items_table . ' si');
            $this->db->join('sales s', 's.id = si.sale_id');
            $this->db->join('motors m', 'm.id = si.item_id AND si.item_type = "motor"');
            $this->db->join('spareparts sp', 'sp.id = si.item_id AND si.item_type = "sparepart"');
            $this->db->where('s.status', 'completed');
            
            if ($start_date && $end_date) {
                $this->db->where('s.created_at >=', $start_date);
                $this->db->where('s.created_at <=', $end_date);
            }
            
            $this->db->group_by('si.item_type, si.item_id');
            $this->db->order_by('total_quantity', 'DESC');
            $this->db->limit($limit);
            
            $result = $this->db->get()->result();
            $this->cache->save($cache_key, $result, $this->cache_time);
        }
        
        return $result;
    }

    public function confirmPayment($id) {
        $this->db->trans_start();
        $data = [
            'status' => 'completed',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $id);
        $result = $this->db->update($this->table, $data);
        $this->db->trans_complete();
        
        if ($this->db->trans_status() && $result) {
            $this->_clearCache();
            return true;
        }
        return false;
    }

    private function _clearCache() {
        $this->cache->delete('sales_all_' . date('Y-m-d_H'));
        // Bersihkan cache lain yang mungkin terpengaruh
        $this->cache->clean();
    }

    private function _formatTanggalIndonesia($tanggal) {
        $bulan = array(
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        );

        return strtr($tanggal, $bulan);
    }
} 
