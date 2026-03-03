<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_pos extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    // Generate nomor invoice otomatis
    function generate_invoice_number()
    {
        $date = date('Ymd');
        $prefix = 'INV-' . $date . '-';
        
        // Loop to ensure unique invoice number
        $max_attempts = 10;
        for ($attempt = 0; $attempt < $max_attempts; $attempt++) {
            // Get last invoice today
            $this->db->select('no_invoice');
            $this->db->from('pos_transactions');
            $this->db->like('no_invoice', $prefix, 'after');
            $this->db->order_by('id_transaction', 'DESC');
            $this->db->limit(1);
            $query = $this->db->get();
            
            if($query->num_rows() > 0){
                $last_invoice = $query->row()->no_invoice;
                $last_number = (int)substr($last_invoice, -4);
                $new_number = $last_number + 1;
            } else {
                $new_number = 1;
            }
            
            $invoice_number = $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
            
            // Check if this invoice number already exists
            $this->db->where('no_invoice', $invoice_number);
            $check = $this->db->get('pos_transactions');
            
            if ($check->num_rows() == 0) {
                // Invoice number is unique, return it
                return $invoice_number;
            }
            
            // If exists, sleep briefly and retry
            usleep(100000); // 100ms
        }
        
        // Fallback: add timestamp to ensure uniqueness
        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT) . '-' . time();
    }

    // Insert transaksi baru
    function insert_transaction($data)
    {
        $this->db->insert('pos_transactions', $data);
        return $this->db->insert_id();
    }

    // Insert detail transaksi
    function insert_transaction_detail($data)
    {
        return $this->db->insert('pos_transaction_details', $data);
    }

    // Get transaction by ID
    function get_transaction_by_id($id)
    {
        $this->db->where('id_transaction', $id);
        $query = $this->db->get('pos_transactions');
        return $query->row();
    }

    // Get transaction details
    function get_transaction_details($transaction_id)
    {
        $this->db->where('id_transaction', $transaction_id);
        $this->db->order_by('id_detail', 'ASC');
        $query = $this->db->get('pos_transaction_details');
        return $query->result();
    }

    // Get all transactions
    function get_all_transactions($start_date = null, $end_date = null)
    {
        if($start_date && $end_date){
            $this->db->where('DATE(tanggal) >=', $start_date);
            $this->db->where('DATE(tanggal) <=', $end_date);
        }
        $this->db->order_by('id_transaction', 'DESC');
        $query = $this->db->get('pos_transactions');
        return $query->result();
    }

    // Get transactions by date
    function get_transactions_by_date($date)
    {
        $this->db->where('DATE(tanggal)', $date);
        $this->db->order_by('id_transaction', 'DESC');
        $query = $this->db->get('pos_transactions');
        return $query->result();
    }

    // Get transactions by invoice
    function get_transaction_by_invoice($invoice)
    {
        $this->db->where('no_invoice', $invoice);
        $query = $this->db->get('pos_transactions');
        return $query->row();
    }

    // Update transaction
    function update_transaction($id, $data)
    {
        $this->db->where('id_transaction', $id);
        return $this->db->update('pos_transactions', $data);
    }

    // Cancel transaction
    function cancel_transaction($id)
    {
        $data = array('status' => 'CANCELLED');
        return $this->update_transaction($id, $data);
    }

    // Get daily report
    function get_daily_report($date = null)
    {
        if(!$date) $date = date('Y-m-d');
        
        $this->db->select('
            COUNT(*) as total_transaksi,
            SUM(total_item) as total_item_terjual,
            SUM(total_bayar) as total_pendapatan,
            SUM(CASE WHEN jenis_pembayaran = "CASH" THEN total_bayar ELSE 0 END) as cash,
            SUM(CASE WHEN jenis_pembayaran = "QRIS" THEN total_bayar ELSE 0 END) as qris,
            SUM(CASE WHEN jenis_pembayaran = "TRANSFER" THEN total_bayar ELSE 0 END) as transfer,
            SUM(CASE WHEN tipe_order = "DINE_IN" THEN total_bayar ELSE 0 END) as dine_in,
            SUM(CASE WHEN tipe_order = "TAKEAWAY" THEN total_bayar ELSE 0 END) as takeaway
        ');
        $this->db->where('DATE(tanggal)', $date);
        $this->db->where('status', 'COMPLETED');
        $query = $this->db->get('pos_transactions');
        return $query->row();
    }

    // Get top selling items
    function get_top_selling_items($limit = 10, $start_date = null, $end_date = null)
    {
        $this->db->select('
            pos_transaction_details.namaitem,
            pos_transaction_details.kodeitem,
            SUM(pos_transaction_details.quantity) as total_qty,
            SUM(pos_transaction_details.subtotal) as total_revenue
        ');
        $this->db->join('pos_transactions', 'pos_transactions.id_transaction = pos_transaction_details.id_transaction');
        $this->db->where('pos_transactions.status', 'COMPLETED');
        
        if($start_date && $end_date){
            $this->db->where('DATE(pos_transactions.tanggal) >=', $start_date);
            $this->db->where('DATE(pos_transactions.tanggal) <=', $end_date);
        }
        
        $this->db->group_by('pos_transaction_details.id_item');
        $this->db->order_by('total_qty', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('pos_transaction_details');
        return $query->result();
    }

    // Get tables
    function get_all_tables()
    {
        $this->db->order_by('no_meja', 'ASC');
        $query = $this->db->get('pos_tables');
        return $query->result();
    }

    // Update table status
    function update_table_status($id, $status)
    {
        $data = array('status' => $status);
        $this->db->where('id_table', $id);
        return $this->db->update('pos_tables', $data);
    }

    // Count split bills for an order
    function count_splits_by_order($order_id)
    {
        $this->db->where('order_id', $order_id);
        $this->db->where('order_id IS NOT NULL');
        return $this->db->count_all_results('pos_transactions');
    }
}
