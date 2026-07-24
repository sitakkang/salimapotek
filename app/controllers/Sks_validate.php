<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sks_validate extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_sks');
    }

    /**
     * Validasi QR Code SKS — endpoint publik, tanpa login
     * URL: /sks_validate/verify/{encrypted_data}
     */
    public function verify($encrypted = '') {
        if (empty($encrypted)) {
            show_404();
        }

        // Dekripsi: base64 decode, format: {id}|{date}|{type}
        $decoded = base64_decode($encrypted);
        $parts   = explode('|', $decoded);
        $id      = isset($parts[0]) ? intval($parts[0]) : 0;
        $type    = isset($parts[2]) ? $parts[2] : 'SKS';

        if ($id <= 0) {
            show_404();
        }

        if ($type === 'SKBS') {
            $data['row'] = $this->db->get_where('trans_skbs', array('id_skbs' => $id))->row();
            if (!$data['row']) show_404();
            $this->load->view('skbs/verify', $data);
        } elseif ($type === 'SKMB') {
            $this->load->model('M_skmb');
            $data['row'] = $this->M_skmb->get_skmb_by_id($id);
            if (!$data['row']) show_404();
            $this->load->view('skmb/verify', $data);
        } else {
            $data['row'] = $this->M_sks->get_sks_by_id($id);
            if (!$data['row']) show_404();
            $this->load->view('sks_validate/verify', $data);
        }
    }
}
