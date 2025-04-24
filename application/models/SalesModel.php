<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesModel extends CI_Model {
    private $table = 'sales';
    private $items_table = 'sales_items';

    public function getAll() {
        $this->db->select('sales.*, 
            customers.name as nama_customer,
            customers.phone as customer_phone,
            GROUP_CONCAT(
                CASE 
                    WHEN sales_items.item_type = "motor" 
                    THEN CONCAT("motor:", motors.merk, " ", motors.model, " ", motors.tahun, " (", motors.warna, ")")
                    WHEN sales_items.item_type = "sparepart" 
                    THEN CONCAT("sparepart:", spareparts.nama, " (", sales_items.quantity, " unit)")
                END
                SEPARATOR "||"
            ) as items_list,
            GROUP_CONCAT(sales_items.item_type) as item_types');
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id = sales.customer_id');
        $this->db->join('sales_items', 'sales_items.sale_id = sales.id', 'left');
        $this->db->join('motors', 'motors.id = sales_items.item_id AND sales_items.item_type = "motor"', 'left');
        $this->db->join('spareparts', 'spareparts.id = sales_items.item_id AND sales_items.item_type = "sparepart"', 'left');
        $this->db->group_by('sales.id');
        $this->db->order_by('sales.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function getById($id) {
        $this->db->select('sales.*, customers.name as customer_name');
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id = sales.customer_id');
        $this->db->where('sales.id', $id);
        return $this->db->get()->row();
    }

    public function getSaleWithDetails($id) {
        $this->db->select('sales.*, 
            customers.name as customer_name, 
            customers.phone as customer_phone, 
            customers.email as customer_email, 
            customers.address as customer_address,
            customers.identity_type as customer_identity_type,
            customers.identity_number as customer_identity_number');
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id = sales.customer_id');
        $this->db->where('sales.id', $id);
        return $this->db->get()->row();
    }

    public function getByCustomerId($customer_id) {
        $this->db->where('customer_id', $customer_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function getSalesItems($sale_id) 
    {
        $sql = "SELECT 
            sales_items.*,
            IF(sales_items.item_type = 'motor', 
               CONCAT(motors.merk, ' ', motors.model),
               spareparts.nama) as item_name,
            IF(sales_items.item_type = 'motor',
               motors.stok,
               spareparts.stok) as current_stock
        FROM sales_items
        LEFT JOIN motors ON motors.id = sales_items.item_id 
            AND sales_items.item_type = 'motor'
        LEFT JOIN spareparts ON spareparts.id = sales_items.item_id 
            AND sales_items.item_type = 'sparepart'
        WHERE sales_items.sale_id = ?";
        
        return $this->db->query($sql, array($sale_id))->result();
    }

    public function insertSalesItem($data) {
        return $this->db->insert($this->items_table, $data);
    }

    public function deleteSalesItems($sale_id) {
        return $this->db->delete($this->items_table, ['sale_id' => $sale_id]);
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
        $this->db->select('SUM(total_amount) as total_sales, COUNT(*) as total_transactions');
        $this->db->from($this->table);
        $this->db->where('status', 'completed');
        
        if ($start_date && $end_date) {
            $this->db->where('created_at >=', $start_date);
            $this->db->where('created_at <=', $end_date);
        }
        
        return $this->db->get()->row();
    }

    public function getTopSellingItems($limit = 10, $start_date = null, $end_date = null) {
        $this->db->select('
            sales_items.item_type,
            sales_items.item_id,
            CASE 
                WHEN sales_items.item_type = "motor" THEN CONCAT(motors.merk, " ", motors.model)
                WHEN sales_items.item_type = "sparepart" THEN spareparts.nama
            END as item_name,
            SUM(sales_items.quantity) as total_quantity,
            SUM(sales_items.subtotal) as total_sales
        ');
        $this->db->from($this->items_table);
        $this->db->join('sales', 'sales.id = sales_items.sale_id');
        $this->db->join('motors', 'motors.id = sales_items.item_id AND sales_items.item_type = "motor"', 'left');
        $this->db->join('spareparts', 'spareparts.id = sales_items.item_id AND sales_items.item_type = "sparepart"', 'left');
        $this->db->where('sales.status', 'completed');
        
        if ($start_date && $end_date) {
            $this->db->where('sales.created_at >=', $start_date);
            $this->db->where('sales.created_at <=', $end_date);
        }
        
        $this->db->group_by('sales_items.item_type, sales_items.item_id');
        $this->db->order_by('total_quantity', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    public function confirmPayment($id)
    {
        $data = [
            'status' => 'completed',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $id);
        return $this->db->update('sales', $data);
    }
} 
