<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

    public $dir_v = 'orders/';

    public function __construct() {
        parent::__construct();
        $this->load->model('M_orders');
        $this->load->model('admin/M_items');
        $this->m_auth->check_login();
    }

    /**
     * Halaman utama - Daftar semua pesanan
     */
    public function index() {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css'
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'lib/sweetalert/sweetalert2.all.min.js',
            'src/js/orders_list.js'
        );
        $data['panel'] = '<i class="fa fa-list"></i> &nbsp;<b>Manajemen Pesanan</b>';
        $this->l_skin->main($this->dir_v.'list', $data);
    }

    /**
     * Halaman buat pesanan baru (Waiter App)
     */
    public function create() {
        $data['css'] = array();
        $data['js'] = array(
            'lib/sweetalert/sweetalert2.all.min.js',
            'src/js/orders_create.js'
        );
        $data['panel'] = '<i class="fa fa-plus"></i> &nbsp;<b>Buat Pesanan Baru</b>';
        $data['tables'] = $this->M_orders->get_available_tables();
        $this->l_skin->main($this->dir_v.'create', $data);
    }

    /**
     * Get items untuk order (AJAX)
     */
    public function get_items() {
        $search = $this->input->get('search');
        $items = $this->M_items->get_all_items($search);
        
        echo json_encode([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Get available tables (AJAX)
     */
    public function get_tables() {
        $tables = $this->M_orders->get_available_tables();
        echo json_encode([
            'success' => true,
            'data' => $tables
        ]);
    }

    /**
     * Generate order number
     */
    public function generate_order_number() {
        $order_number = $this->M_orders->generate_order_number();
        echo json_encode([
            'success' => true,
            'order_number' => $order_number
        ]);
    }

    /**
     * Save new order
     */
    public function save_order() {
        // Validate input
        $order_type = $this->input->post('order_type');
        $table_id = $this->input->post('table_id');
        $customer_name = $this->input->post('customer_name');
        $customer_phone = $this->input->post('customer_phone');
        $items = json_decode($this->input->post('items'), true);
        $notes = $this->input->post('notes');

        // Validation
        if (empty($order_type)) {
            echo json_encode(['success' => false, 'message' => 'Pilih jenis order (Dine In / Takeaway)']);
            return;
        }

        // Allow POS to create dine-in orders without table selection
        if ($order_type == 'DINE_IN' && empty($table_id)) {
            $table_id = null;
        }

        if (empty($items) || count($items) == 0) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada item dalam pesanan']);
            return;
        }

        // Start database transaction
        $this->db->trans_start();

        // Get user info
        $user = (object)[
            'id_user' => $this->session->userdata('sess_id'),
            'name' => $this->session->userdata('sess_name')
        ];
        
        // Generate fresh order number to avoid duplicates
        $order_number = $this->M_orders->generate_order_number();
        
        // Get table info if dine in
        $table_number = null;
        if ($order_type == 'DINE_IN' && !empty($table_id)) {
            $table = $this->M_orders->get_table_by_id($table_id);
            $table_number = $table ? $table->no_meja : null;
        }

        // Calculate total
        $total_items = 0;
        $total_amount = 0;
        foreach ($items as $item) {
            $total_items += $item['quantity'];
            $total_amount += $item['subtotal'];
        }

        $order_data = [
            'order_number' => $order_number,
            'table_id' => $order_type == 'DINE_IN' ? $table_id : null,
            'table_number' => $table_number,
            'order_type' => $order_type,
            'customer_name' => $customer_name,
            'customer_phone' => $customer_phone,
            'status' => 'PROCESSING',
            'total_items' => $total_items,
            'total_amount' => $total_amount,
            'created_by' => $user->id_user,
            'notes' => $notes
        ];

        // Save order
        $order_id = $this->M_orders->insert_order($order_data);

        if ($order_id) {
            // Save order items
            foreach ($items as $item) {
                $item_data = [
                    'order_id' => $order_id,
                    'item_id' => $item['item_id'],
                    'kodeitem' => $item['kodeitem'],
                    'namaitem' => $item['namaitem'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'status' => 'PROCESSING',
                    'notes' => isset($item['notes']) ? $item['notes'] : null
                ];
                $this->M_orders->insert_order_item($item_data);
            }

            // Update table status if dine in
            if ($order_type == 'DINE_IN' && !empty($table_id)) {
                $this->M_orders->update_table_status($table_id, 'occupied');
            }
        }

        // Complete database transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan pesanan'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'order_id' => $order_id,
                'order_number' => $order_number
            ]);
        }
    }

    /**
     * Get orders list (AJAX for DataTables)
     */
    public function get_orders() {
        $status_filter = $this->input->get('status');
        $orders = $this->M_orders->get_orders($status_filter);
        
        echo json_encode([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Get order detail
     */
    public function get_order_detail($order_id) {
        $order = $this->M_orders->get_order_by_id($order_id);
        $items = $this->M_orders->get_order_items($order_id);
        
        if ($order) {
            echo json_encode([
                'success' => true,
                'order' => $order,
                'items' => $items
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ]);
        }
    }

    /**
     * Update order status
     */
    public function update_status() {
        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');

        $valid_statuses = ['PROCESSING', 'PAID', 'CANCEL'];
        
        if (!in_array($status, $valid_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
            return;
        }

        $result = $this->M_orders->update_order_status($order_id, $status);
        
        if ($result) {
            // If cancelled, free the table
            if ($status == 'CANCEL') {
                $order = $this->M_orders->get_order_by_id($order_id);
                if ($order && $order->table_id) {
                    $this->M_orders->update_table_status($order->table_id, 'available');
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal update status pesanan'
            ]);
        }
    }

    /**
     * Update item status in order
     */
    public function update_item_status() {
        $order_item_id = $this->input->post('order_item_id');
        $status = $this->input->post('status');

        $valid_statuses = ['PROCESSING', 'PAID', 'CANCEL'];
        
        if (!in_array($status, $valid_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
            return;
        }

        $result = $this->M_orders->update_order_item_status($order_item_id, $status);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Status item berhasil diupdate'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal update status item'
            ]);
        }
    }

    /**
     * Cancel order
     */
    public function cancel_order() {
        $order_id = $this->input->post('order_id');

        if (empty($order_id) || !is_numeric($order_id)) {
            echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
            return;
        }

        $order = $this->M_orders->get_order_by_id($order_id);

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan']);
            return;
        }

        // Pastikan pesanan belum dibayar
        if ($order->status === 'PAID') {
            echo json_encode(['success' => false, 'message' => 'Pesanan sudah dibayar, tidak dapat dibatalkan']);
            return;
        }

        // Pastikan pesanan belum dibatalkan sebelumnya
        if ($order->status === 'CANCEL') {
            echo json_encode(['success' => false, 'message' => 'Pesanan sudah dibatalkan sebelumnya']);
            return;
        }

        $this->db->trans_start();

        $result = $this->M_orders->update_order_status($order_id, 'CANCEL');

        // Bebaskan meja jika dine in
        if ($order->table_id) {
            $this->M_orders->update_table_status($order->table_id, 'available');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$result) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan, silakan coba lagi'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Pesanan ' . $order->order_number . ' berhasil dibatalkan'
            ]);
        }
    }

    /**
     * Process payment for order
     */
    public function process_payment() {
        $order_id = $this->input->post('order_id');
        $payment_method = $this->input->post('payment_method');
        $amount_paid = $this->input->post('amount_paid');
        
        $order = $this->M_orders->get_order_by_id($order_id);
        
        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan']);
            return;
        }

        if ($order->status == 'PAID') {
            echo json_encode(['success' => false, 'message' => 'Pesanan sudah dibayar']);
            return;
        }

        if ($order->status == 'CANCEL') {
            echo json_encode(['success' => false, 'message' => 'Pesanan sudah dibatalkan']);
            return;
        }

        // Start transaction
        $this->db->trans_start();

        // Update order status to PAID
        $result = $this->M_orders->update_order_status($order_id, 'PAID');
        
        if ($result) {
            // Free the table if dine in
            if ($order->table_id) {
                $this->M_orders->update_table_status($order->table_id, 'available');
            }

            // Calculate change
            $change = $amount_paid - $order->total_amount;
        }

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$result) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memproses pembayaran'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses',
                'order_number' => $order->order_number,
                'total' => $order->total_amount,
                'paid' => $amount_paid,
                'change' => $change
            ]);
        }
    }

    /**
     * View order detail page
     */
    public function view($order_id) {
        $data['skin'] = $this->l_skin->get();
        $data['title'] = 'Detail Pesanan';
        $data['order'] = $this->M_orders->get_order_by_id($order_id);
        $data['items'] = $this->M_orders->get_order_items($order_id);
        
        if (!$data['order']) {
            redirect('orders');
        }
        
        $this->load->view('orders/view', $data);
    }
}
