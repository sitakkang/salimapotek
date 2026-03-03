<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pos extends CI_Controller {

    public $dir_v = 'pos/';
    public $dir_m = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->load->model($this->dir_m.'M_items');
        $this->load->model('M_pos');
        $this->load->model('M_orders');
    }

    // Halaman utama POS
    function index()
    {
        $data['css'] = array(
            'src/css/pos.css'
        );
        $data['js'] = array(
            'src/js/pos.js'
        );
        $data['panel'] = '<i class="fa fa-shopping-cart"></i> &nbsp;<b>Point of Sale</b>';
        $this->l_skin->main($this->dir_v.'main', $data);
    }

    // Get all items untuk ditampilkan di POS
    function get_items()
    {
        $items = $this->M_items->get_all_items();
        echo json_encode($items);
    }

    // Get item by ID
    function get_item($id)
    {
        $item = $this->M_items->get_item_by_id($id);
        echo json_encode($item);
    }

    // Get order by ID untuk payment di POS
    function get_order($order_id)
    {
        $order = $this->M_orders->get_order_by_id($order_id);
        if($order) {
            $items = $this->M_orders->get_order_items($order_id);
            echo json_encode(array(
                'success' => true,
                'order' => $order,
                'items' => $items
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ));
        }
    }

    // Sync cart changes (qty update / delete) ke database
    function update_cart()
    {
        $order_id = $this->input->post('order_id');
        $items    = json_decode($this->input->post('items'), true);

        if (!$order_id) {
            echo json_encode(['success' => false, 'message' => 'order_id tidak ditemukan']);
            return;
        }

        // Get current DB items keyed by order_item_id
        $db_items = $this->M_orders->get_order_items($order_id);
        $db_item_ids = array_map(function($r){ return (int)$r->order_item_id; }, $db_items);

        // Build set of item_ids still in cart (using order_item_id stored in cart)
        $cart_oi_ids = [];
        if ($items) {
            foreach ($items as $item) {
                if (!empty($item['order_item_id'])) {
                    $oi_id = (int)$item['order_item_id'];
                    $cart_oi_ids[] = $oi_id;
                    // Update qty & subtotal
                    $this->M_orders->update_order_item_qty(
                        $oi_id,
                        (int)$item['qty'],
                        (float)$item['subtotal']
                    );
                }
            }
        }

        // Delete items removed from cart
        foreach ($db_item_ids as $oi_id) {
            if (!in_array($oi_id, $cart_oi_ids)) {
                $this->M_orders->delete_order_item($oi_id);
            }
        }

        // Recalculate order totals
        $this->M_orders->recalculate_order_total($order_id);

        echo json_encode(['success' => true]);
    }

    // Generate nomor invoice
    function generate_invoice()
    {
        $invoice = $this->M_pos->generate_invoice_number();
        echo json_encode(array('invoice' => $invoice));
    }

    // Simpan transaksi
    function save_transaction()
    {
        // Validasi input
        $items = json_decode($this->input->post('items'), true);
        
        if(empty($items)){
            echo json_encode(array('status' => 0, 'message' => 'Tidak ada item yang dipilih!'));
            return;
        }

        // Start database transaction
        $this->db->trans_start();
        
        // Generate fresh invoice number to avoid duplicates
        $invoice_number = $this->M_pos->generate_invoice_number();

        // Data transaksi
        $transaction_data = array(
            'no_invoice' => $invoice_number,
            'tanggal' => date('Y-m-d H:i:s'),
            'total_item' => $this->input->post('total_item'),
            'subtotal' => $this->input->post('subtotal'),
            'diskon' => $this->input->post('diskon') ? $this->input->post('diskon') : 0,
            'pajak' => $this->input->post('pajak') ? $this->input->post('pajak') : 0,
            'total_bayar' => $this->input->post('total_bayar'),
            'jumlah_bayar' => $this->input->post('jumlah_bayar'),
            'kembalian' => $this->input->post('kembalian'),
            'jenis_pembayaran' => $this->input->post('jenis_pembayaran'),
            'tipe_order' => $this->input->post('tipe_order'),
            'kasir_id' => $this->session->userdata('sess_id'),
            'kasir_name' => $this->session->userdata('sess_name'),
            'status' => 'COMPLETED',
            'catatan' => $this->input->post('catatan'),
            'created_at' => date('Y-m-d H:i:s')
        );

        // Insert transaksi
        $transaction_id = $this->M_pos->insert_transaction($transaction_data);

        if($transaction_id){
            // Insert detail items
            foreach($items as $item){
                $detail_data = array(
                    'id_transaction' => $transaction_id,
                    'id_item' => $item['id'],
                    'kodeitem' => $item['kodeitem'],
                    'namaitem' => $item['namaitem'],
                    'harga_satuan' => $item['harga'],
                    'quantity' => $item['qty'],
                    'diskon_item' => isset($item['diskon']) ? $item['diskon'] : 0,
                    'subtotal' => $item['subtotal']
                );
                $this->M_pos->insert_transaction_detail($detail_data);
            }
        }
        
        // Complete database transaction
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('status' => 0, 'message' => 'Gagal menyimpan transaksi!'));
        } else {
            echo json_encode(array(
                'status' => 1, 
                'message' => 'Transaksi berhasil disimpan!',
                'transaction_id' => $transaction_id,
                'invoice' => $invoice_number
            ));
        }
    }

    // Cetak struk
    function print_receipt($transaction_id)
    {
        $data['transaction'] = $this->M_pos->get_transaction_by_id($transaction_id);
        $data['details'] = $this->M_pos->get_transaction_details($transaction_id);
        $this->load->view($this->dir_v.'receipt', $data);
    }

    // History transaksi
    function history()
    {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css'
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'src/js/pos_history.js'
        );
        $data['panel'] = '<i class="fa fa-history"></i> &nbsp;<b>Riwayat Transaksi</b>';
        $this->l_skin->main($this->dir_v.'history', $data);
    }

    // Get history data untuk datatables
    function get_history()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $transactions = $this->M_pos->get_all_transactions($start_date, $end_date);

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = array();
        $i = 1;
        foreach($transactions as $trx) {
            $data[] = array(
                "DT_RowId" => $trx->id_transaction,
                "0" => $i++,
                "1" => $trx->no_invoice,
                "2" => date('d/m/Y H:i', strtotime($trx->tanggal)),
                "3" => $trx->total_item,
                "4" => 'Rp ' . number_format($trx->total_bayar, 0, ',', '.'),
                "5" => $trx->jenis_pembayaran,
                "6" => $trx->tipe_order,
                "7" => $trx->kasir_name,
                "8" => '<span class="badge badge-success">'.$trx->status.'</span>'
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($transactions),
            "recordsFiltered" => count($transactions),
            "data" => $data
        );
        echo json_encode($output);
    }

    // Get transaction detail for modal
    function get_transaction_detail($transaction_id)
    {
        $transaction = $this->M_pos->get_transaction_by_id($transaction_id);
        $details = $this->M_pos->get_transaction_details($transaction_id);
        
        $html = '<div class="transaction-detail">';
        
        // Header Info
        $html .= '<div class="row mb-3">';
        $html .= '<div class="col-md-6">';
        $html .= '<table class="table table-sm table-borderless">';
        $html .= '<tr><td width="120"><strong>Invoice:</strong></td><td>'.$transaction->no_invoice.'</td></tr>';
        $html .= '<tr><td><strong>Tanggal:</strong></td><td>'.date('d/m/Y H:i:s', strtotime($transaction->tanggal)).'</td></tr>';
        $html .= '<tr><td><strong>Kasir:</strong></td><td>'.$transaction->kasir_name.'</td></tr>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '<div class="col-md-6">';
        $html .= '<table class="table table-sm table-borderless">';
        $html .= '<tr><td width="120"><strong>Pembayaran:</strong></td><td><span class="badge badge-primary">'.$transaction->jenis_pembayaran.'</span></td></tr>';
        $html .= '<tr><td><strong>Tipe Order:</strong></td><td><span class="badge badge-info">'.$transaction->tipe_order.'</span></td></tr>';
        $html .= '<tr><td><strong>Status:</strong></td><td><span class="badge badge-success">'.$transaction->status.'</span></td></tr>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Items Table
        $html .= '<h6 class="mb-2">Detail Items:</h6>';
        $html .= '<table class="table table-sm table-bordered">';
        $html .= '<thead class="thead-light"><tr><th>No</th><th>Kode</th><th>Nama Item</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>';
        $html .= '<tbody>';
        $no = 1;
        foreach($details as $item) {
            $html .= '<tr>';
            $html .= '<td>'.$no++.'</td>';
            $html .= '<td>'.$item->kodeitem.'</td>';
            $html .= '<td>'.$item->namaitem.'</td>';
            $html .= '<td>Rp '.number_format($item->harga_satuan, 0, ',', '.').'</td>';
            $html .= '<td>'.$item->quantity.'</td>';
            $html .= '<td>Rp '.number_format($item->subtotal, 0, ',', '.').'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        
        // Summary
        $html .= '<div class="row mt-3">';
        $html .= '<div class="col-md-6 offset-md-6">';
        $html .= '<table class="table table-sm">';
        $html .= '<tr><td>Subtotal:</td><td class="text-right">Rp '.number_format($transaction->subtotal, 0, ',', '.').'</td></tr>';
        if($transaction->diskon > 0) {
            $html .= '<tr><td>Diskon:</td><td class="text-right">Rp '.number_format($transaction->diskon, 0, ',', '.').'</td></tr>';
        }
        if($transaction->pajak > 0) {
            $html .= '<tr><td>Pajak:</td><td class="text-right">Rp '.number_format($transaction->pajak, 0, ',', '.').'</td></tr>';
        }
        $html .= '<tr class="table-success"><td><strong>TOTAL:</strong></td><td class="text-right"><strong>Rp '.number_format($transaction->total_bayar, 0, ',', '.').'</strong></td></tr>';
        
        if($transaction->jenis_pembayaran == 'CASH') {
            $html .= '<tr><td>Bayar:</td><td class="text-right">Rp '.number_format($transaction->jumlah_bayar, 0, ',', '.').'</td></tr>';
            $html .= '<tr><td>Kembalian:</td><td class="text-right">Rp '.number_format($transaction->kembalian, 0, ',', '.').'</td></tr>';
        }
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</div>';
        
        if(!empty($transaction->catatan)) {
            $html .= '<div class="alert alert-info mt-2"><strong>Catatan:</strong><br>'.$transaction->catatan.'</div>';
        }
        
        // Action Buttons
        $html .= '<div class="text-right mt-3">';
        $html .= '<a href="'.site_url('pos/print_receipt/'.$transaction_id).'" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Cetak Struk</a>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        echo $html;
    }

    /**
     * Get unpaid orders untuk dipilih di POS
     */
    function get_unpaid_orders()
    {
        $orders = $this->M_orders->get_unpaid_orders();
        echo json_encode([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Load order data ketika dipilih
     */
    function load_order($order_id)
    {
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
                'message' => 'Order tidak ditemukan'
            ]);
        }
    }

    /**
     * Simpan transaksi dari order (support split bill)
     */
    function save_transaction_from_order()
    {
        $order_id = $this->input->post('order_id');
        $items = json_decode($this->input->post('items'), true);
        
        if(empty($items)){
            echo json_encode(array('status' => 0, 'message' => 'Tidak ada item yang dipilih!'));
            return;
        }

        // Get order info
        $order = $this->M_orders->get_order_by_id($order_id);
        if(!$order) {
            echo json_encode(array('status' => 0, 'message' => 'Order tidak ditemukan!'));
            return;
        }

        // Start database transaction
        $this->db->trans_start();
        
        // Generate fresh invoice number to avoid duplicates
        $invoice_number = $this->M_pos->generate_invoice_number();

        // Check if this is split bill
        $is_split = $this->input->post('is_split') == '1';
        $split_number = 1;
        $split_total = 1;

        if($is_split) {
            // Count existing splits for this order
            $existing_splits = $this->M_pos->count_splits_by_order($order_id);
            $split_number = $existing_splits + 1;
            $split_total = $this->input->post('split_total') ? $this->input->post('split_total') : 1;
        }

        // Data transaksi
        $transaction_data = array(
            'order_id' => $order_id,
            'split_number' => $is_split ? $split_number : null,
            'split_total' => $is_split ? $split_total : null,
            'no_invoice' => $invoice_number,
            'tanggal' => date('Y-m-d H:i:s'),
            'total_item' => $this->input->post('total_item'),
            'subtotal' => $this->input->post('subtotal'),
            'diskon' => $this->input->post('diskon') ? $this->input->post('diskon') : 0,
            'pajak' => $this->input->post('pajak') ? $this->input->post('pajak') : 0,
            'total_bayar' => $this->input->post('total_bayar'),
            'jumlah_bayar' => $this->input->post('jumlah_bayar'),
            'kembalian' => $this->input->post('kembalian'),
            'jenis_pembayaran' => $this->input->post('jenis_pembayaran'),
            'tipe_order' => $order->order_type,
            'kasir_id' => $this->session->userdata('sess_id'),
            'kasir_name' => $this->session->userdata('sess_name'),
            'status' => 'COMPLETED',
            'catatan' => $this->input->post('catatan'),
            'created_at' => date('Y-m-d H:i:s')
        );

        // Insert transaksi
        $transaction_id = $this->M_pos->insert_transaction($transaction_data);

        if($transaction_id){
            // Insert detail items
            foreach($items as $item){
                $detail_data = array(
                    'id_transaction' => $transaction_id,
                    'id_item' => $item['id'],
                    'kodeitem' => $item['kodeitem'],
                    'namaitem' => $item['namaitem'],
                    'harga_satuan' => $item['harga'],
                    'quantity' => $item['qty'],
                    'diskon_item' => isset($item['diskon']) ? $item['diskon'] : 0,
                    'subtotal' => $item['subtotal']
                );
                $this->M_pos->insert_transaction_detail($detail_data);
            }

            // Check if this completes the order payment
            if(!$is_split) {
                // Single payment - mark order as PAID
                $this->M_orders->mark_order_as_paid($order_id);
            } else {
                // Check if all splits are paid
                $total_splits_paid = $this->M_pos->count_splits_by_order($order_id);
                if($total_splits_paid >= $split_total) {
                    $this->M_orders->mark_order_as_paid($order_id);
                }
            }
        }
        
        // Complete database transaction
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('status' => 0, 'message' => 'Gagal menyimpan transaksi!'));
        } else {
            echo json_encode(array(
                'status' => 1, 
                'message' => 'Transaksi berhasil disimpan!',
                'transaction_id' => $transaction_id,
                'invoice' => $invoice_number
            ));
        }
    }

}
