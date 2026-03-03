<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_orders extends CI_Model {

    /**
     * Generate order number: ORD-YYYYMMDD-XXXX
     */
    public function generate_order_number() {
        $date = date('Ymd');
        $prefix = "ORD-{$date}-";
        
        // Loop to ensure unique order number
        $max_attempts = 10;
        for ($attempt = 0; $attempt < $max_attempts; $attempt++) {
            // Get last order number for today
            $this->db->select('order_number');
            $this->db->from('orders');
            $this->db->like('order_number', $prefix, 'after');
            $this->db->order_by('order_id', 'DESC');
            $this->db->limit(1);
            $query = $this->db->get();
            
            if ($query->num_rows() > 0) {
                $last_number = $query->row()->order_number;
                $last_sequence = intval(substr($last_number, -4));
                $new_sequence = $last_sequence + 1;
            } else {
                $new_sequence = 1;
            }
            
            $order_number = $prefix . str_pad($new_sequence, 4, '0', STR_PAD_LEFT);
            
            // Check if this order number already exists
            $this->db->where('order_number', $order_number);
            $check = $this->db->get('orders');
            
            if ($check->num_rows() == 0) {
                // Order number is unique, return it
                return $order_number;
            }
            
            // If exists, sleep briefly and retry
            usleep(100000); // 100ms
        }
        
        // Fallback: add timestamp to ensure uniqueness
        return $prefix . str_pad($new_sequence, 4, '0', STR_PAD_LEFT) . '-' . time();
    }

    /**
     * Insert new order
     */
    public function insert_order($data) {
        $this->db->insert('orders', $data);
        return $this->db->insert_id();
    }

    /**
     * Insert order item
     */
    public function insert_order_item($data) {
        $this->db->insert('order_items', $data);
        return $this->db->insert_id();
    }

    /**
     * Get all orders with optional status filter
     */
    public function get_orders($status = null) {
        $this->db->select('orders.*, conf_users.fullname as created_by_name');
        $this->db->from('orders');
        $this->db->join('conf_users', 'conf_users.id_user = orders.created_by', 'left');
        
        if ($status && $status != 'ALL') {
            $this->db->where('orders.status', $status);
        }
        
        $this->db->order_by('orders.created_at', 'DESC');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Get order by ID
     */
    public function get_order_by_id($order_id) {
        $this->db->select('orders.*, conf_users.fullname as created_by_name');
        $this->db->from('orders');
        $this->db->join('conf_users', 'conf_users.id_user = orders.created_by', 'left');
        $this->db->where('orders.order_id', $order_id);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * Get order by order number
     */
    public function get_order_by_number($order_number) {
        $this->db->select('orders.*, conf_users.fullname as created_by_name');
        $this->db->from('orders');
        $this->db->join('conf_users', 'conf_users.id_user = orders.created_by', 'left');
        $this->db->where('orders.order_number', $order_number);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * Get order items
     */
    public function get_order_items($order_id) {
        $this->db->select('*');
        $this->db->from('order_items');
        $this->db->where('order_id', $order_id);
        $this->db->order_by('order_item_id', 'ASC');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Update order item quantity and subtotal
     */
    public function update_order_item_qty($order_item_id, $quantity, $subtotal) {
        $this->db->where('order_item_id', $order_item_id);
        return $this->db->update('order_items', [
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ]);
    }

    /**
     * Delete a single order item
     */
    public function delete_order_item($order_item_id) {
        $this->db->where('order_item_id', $order_item_id);
        return $this->db->delete('order_items');
    }

    /**
     * Recalculate and update order totals from its items
     */
    public function recalculate_order_total($order_id) {
        $this->db->select_sum('quantity', 'total_items');
        $this->db->select_sum('subtotal', 'total_amount');
        $this->db->where('order_id', $order_id);
        $row = $this->db->get('order_items')->row();
        $this->db->where('order_id', $order_id);
        return $this->db->update('orders', [
            'total_items'  => $row->total_items  ?? 0,
            'total_amount' => $row->total_amount ?? 0
        ]);
    }
    public function update_order_status($order_id, $status) {
        $this->db->where('order_id', $order_id);
        return $this->db->update('orders', ['status' => $status]);
    }

    /**
     * Update order item status
     */
    public function update_order_item_status($order_item_id, $status) {
        $this->db->where('order_item_id', $order_item_id);
        return $this->db->update('order_items', ['status' => $status]);
    }

    /**
     * Get available tables
     */
    public function get_available_tables() {
        $this->db->select('*');
        $this->db->from('pos_tables');
        $this->db->where('status', 'AVAILABLE');
        $this->db->order_by('no_meja', 'ASC');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Get all tables
     */
    public function get_all_tables() {
        $this->db->select('*');
        $this->db->from('pos_tables');
        $this->db->order_by('no_meja', 'ASC');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Get table by ID
     */
    public function get_table_by_id($table_id) {
        $this->db->where('id_table', $table_id);
        $query = $this->db->get('pos_tables');
        return $query->row();
    }

    /**
     * Update table status
     */
    public function update_table_status($table_id, $status) {
        $this->db->where('id_table', $table_id);
        return $this->db->update('pos_tables', ['status' => strtoupper($status)]);
    }

    /**
     * Get pending orders (for kitchen display)
     */
    public function get_pending_orders() {
        // Simple query without JOIN first
        $this->db->select('orders.*');
        $this->db->from('orders');
        $this->db->where_in('orders.status', array('PROCESSING'));
        $this->db->order_by('orders.created_at', 'ASC');
        
        $query = $this->db->get();
        
        // If result found, try to add user info
        if ($query->num_rows() > 0) {
            $orders = $query->result();
            
            // Add user info if available
            foreach ($orders as $order) {
                if (!empty($order->created_by)) {
                    $user = $this->db->get_where('conf_users', array('id_user' => $order->created_by))->row();
                    $order->created_by_name = $user ? $user->fullname : 'Unknown';
                } else {
                    $order->created_by_name = 'System';
                }
            }
            
            return $orders;
        }
        
        return array();
    }

    /**
     * Get unpaid orders (for POS payment)
     */
    public function get_unpaid_orders() {
        $this->db->select('orders.*');
        $this->db->from('orders');
        $this->db->where_in('orders.status', ['PROCESSING']);
        $this->db->order_by('orders.created_at', 'DESC');
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * Mark order as paid
     */
    public function mark_order_as_paid($order_id) {
        $this->db->where('order_id', $order_id);
        $result = $this->db->update('orders', ['status' => 'PAID']);
        
        if ($result) {
            // Free the table
            $order = $this->get_order_by_id($order_id);
            if ($order && $order->table_id) {
                $this->update_table_status($order->table_id, 'available');
            }
        }
        
        return $result;
    }

    /**
     * Get today's statistics
     */
    public function get_today_stats() {
        $today = date('Y-m-d');
        
        // Total orders
        $this->db->where('DATE(created_at)', $today);
        $total_orders = $this->db->count_all_results('orders');
        
        // Pesanan aktif (belum bayar)
        $this->db->where('DATE(created_at)', $today);
        $this->db->where_in('status', ['PROCESSING']);
        $pending_orders = $this->db->count_all_results('orders');
        
        // Completed orders
        $this->db->where('DATE(created_at)', $today);
        $this->db->where('status', 'PAID');
        $completed_orders = $this->db->count_all_results('orders');
        
        // Total revenue from paid orders
        $this->db->select_sum('total_amount');
        $this->db->where('DATE(created_at)', $today);
        $this->db->where('status', 'PAID');
        $query = $this->db->get('orders');
        $revenue = $query->row()->total_amount ?? 0;
        
        return [
            'total_orders' => $total_orders,
            'pending_orders' => $pending_orders,
            'completed_orders' => $completed_orders,
            'revenue' => $revenue
        ];
    }

    /**
     * Get order count by status
     */
    public function get_order_count_by_status() {
        $this->db->select('status, COUNT(*) as count');
        $this->db->from('orders');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $this->db->group_by('status');
        $query = $this->db->get();
        
        $result = [];
        foreach ($query->result() as $row) {
            $result[$row->status] = $row->count;
        }
        
        return $result;
    }
}
