<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchen extends CI_Controller {

    public $dir_v = 'kitchen/';

    public function __construct() {
        parent::__construct();
        $this->load->model('M_orders');
        $this->m_auth->check_login();
    }

    /**
     * Kitchen Display - Tampilan untuk dapur
     */
    public function index() {
        $data['css'] = array();
        $data['js'] = array(
            'lib/sweetalert/sweetalert2.all.min.js'
        );
        $data['panel'] = '<i class="fa fa-fire"></i> &nbsp;<b>Kitchen Display</b>';
        $this->l_skin->main($this->dir_v.'display', $data);
    }

    /**
     * Get pending orders for kitchen (AJAX)
     */
    public function get_pending_orders() {
        try {
            $orders = $this->M_orders->get_pending_orders();
            
            // Debug: Log query result
            log_message('debug', 'Kitchen Orders Count: ' . count($orders));
            log_message('debug', 'Last Query: ' . $this->db->last_query());
            
            // Get items for each order
            if (is_array($orders) && count($orders) > 0) {
                foreach ($orders as $order) {
                    $items = $this->M_orders->get_order_items($order->order_id);
                    $order->items = $items ? $items : array();
                    log_message('debug', 'Order #' . $order->order_id . ' has ' . count($order->items) . ' items');
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => $orders,
                'count' => count($orders),
                'debug_query' => $this->db->last_query()
            ]);
        } catch (Exception $e) {
            log_message('error', 'Kitchen get_pending_orders error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => [],
                'debug_query' => $this->db->last_query()
            ]);
        }
    }
    
    /**
     * Debug method - check raw orders data
     */
    public function debug_orders() {
        // Get all orders
        $this->db->select('*');
        $this->db->from('orders');
        $this->db->where_in('status', ['PENDING', 'PREPARING', 'READY']);
        $query = $this->db->get();
        
        echo '<h3>Debug Orders</h3>';
        echo '<p>Total Orders: ' . $query->num_rows() . '</p>';
        echo '<p>SQL: ' . $this->db->last_query() . '</p>';
        echo '<pre>';
        print_r($query->result());
        echo '</pre>';
        
        // Check conf_users table
        $this->db->select('*');
        $this->db->from('conf_users');
        $users = $this->db->get();
        
        echo '<h3>Debug Users</h3>';
        echo '<p>Total Users: ' . $users->num_rows() . '</p>';
        echo '<pre>';
        print_r($users->result());
        echo '</pre>';
    }

    /**
     * Update order status from kitchen
     */
    public function update_status() {
        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');

        // Validate input
        if (empty($order_id)) {
            echo json_encode(['success' => false, 'message' => 'Order ID tidak boleh kosong']);
            return;
        }

        if (empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Status tidak boleh kosong']);
            return;
        }

        $valid_statuses = ['PREPARING', 'READY'];
        
        if (!in_array($status, $valid_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
            return;
        }

        // Start transaction
        $this->db->trans_start();

        $result = $this->M_orders->update_order_status($order_id, $status);
        
        if ($result) {
            // Also update all items in the order
            $items = $this->M_orders->get_order_items($order_id);
            foreach ($items as $item) {
                if ($item->status != 'CANCELLED') {
                    $this->M_orders->update_order_item_status($item->order_item_id, $status);
                }
            }
        }

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$result) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal update status'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Status berhasil diupdate ke ' . $status
            ]);
        }
    }

    /**
     * Update individual item status
     */
    public function update_item_status() {
        $order_item_id = $this->input->post('order_item_id');
        $status = $this->input->post('status');

        // Validate input
        if (empty($order_item_id)) {
            echo json_encode(['success' => false, 'message' => 'Order Item ID tidak boleh kosong']);
            return;
        }

        if (empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Status tidak boleh kosong']);
            return;
        }

        $valid_statuses = ['PREPARING', 'READY'];
        
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
}
