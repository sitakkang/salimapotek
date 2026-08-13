<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportobat extends CI_Controller {

    public $dir_v = 'reportobat/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_reportobat');
    }

    public function index() {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css',
            'lib/datatables/fixedColumns.bootstrap.min.css',
            'lib/datepicker/datepicker.min.css',
            'lib/select/component-chosen.min.css',
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'lib/datatables/dataTables.fixedColumns.min.js',
            'lib/sweetalert/sweetalert2.all.min.js',
            'lib/datepicker/datepicker.min.js',
            'lib/select/chosen.jquery.min.js',
            'lib/mask/jquery.mask.min.js',
            'src/js/admin/reportobat.js',
        );
        $data['panel'] = '<i class="fa fa-pills"></i> &nbsp;<b>Laporan Obat</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source — filter by date range & obat name
     */
    public function table() {
        $date_from = trim($this->input->get('date_from') ?? '');
        $date_to   = trim($this->input->get('date_to') ?? '');
        $obat_name = trim($this->input->get('obat_name') ?? '');

        if (empty($date_from) || empty($date_to)) {
            echo json_encode(array(
                'draw'            => intval($this->input->get('draw')),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => array(),
            ));
            exit();
        }

        $date_from_db = $this->format_date_db($date_from);
        $date_to_db   = $this->format_date_db($date_to);

        $rows = $this->M_reportobat->get_report($date_from_db, $date_to_db, $obat_name);

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $data[] = array(
                'DT_RowId'   => $row->id_trans_obat,
                '0'          => $i++,
                '1'          => htmlspecialchars($row->trans_obat_name ?? ''),
                '2'          => htmlspecialchars($row->trans_obat_satuan ?? ''),
                '3'          => intval($row->trans_obat_qty),
                '4'          => htmlspecialchars($row->trans_obat_dosis ?: '-'),
                '5'          => 'Rp ' . number_format($row->trans_obat_price, 0, ',', '.'),
                '6'          => 'Rp ' . number_format($row->trans_obat_total_price, 0, ',', '.'),
                '7'          => htmlspecialchars($row->patient_name ?? ''),
                '8'          => htmlspecialchars($row->trans_patient_code ?? ''),
                '9'          => !empty($row->trans_doc) ? date('d/m/Y', strtotime($row->trans_doc)) : '-',
            );
        }

        echo json_encode(array(
            'draw'            => $draw,
            'recordsTotal'    => $rows->num_rows(),
            'recordsFiltered' => $rows->num_rows(),
            'data'            => $data,
        ));
        exit();
    }

    /**
     * Ambil daftar nama obat unik untuk autocomplete
     */
    public function obat_list() {
        $rows = $this->M_reportobat->get_distinct_obat();
        $list = array();
        foreach ($rows->result() as $row) {
            $list[] = $row->trans_obat_name;
        }
        echo json_encode($list);
    }

    /**
     * Download Excel — filter sama dengan table()
     */
    public function download_excel() {
        $date_from = trim($this->input->get('date_from') ?? '');
        $date_to   = trim($this->input->get('date_to') ?? '');
        $obat_name = trim($this->input->get('obat_name') ?? '');

        if (empty($date_from) || empty($date_to)) {
            show_404();
        }

        $date_from_db = $this->format_date_db($date_from);
        $date_to_db   = $this->format_date_db($date_to);

        $rows = $this->M_reportobat->get_report($date_from_db, $date_to_db, $obat_name);

        $filename = 'Laporan_Obat_' . $date_from . '_' . $date_to . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
              xmlns:x="urn:schemas-microsoft-com:office:excel"
              xmlns="http://www.w3.org/TR/REC-html40">
              <head><meta charset="UTF-8">
              <style>td { mso-number-format:"\@"; }</style>
              </head><body>';
        echo '<table border="1">';
        echo '<thead>';
        echo '<tr style="background:#2d6a4f;color:#fff;">';
        echo '<th>No</th>';
        echo '<th>Nama Obat</th>';
        echo '<th>Satuan</th>';
        echo '<th>Qty</th>';
        echo '<th>Dosis</th>';
        echo '<th>Harga</th>';
        echo '<th>Total Harga</th>';
        echo '<th>Pasien</th>';
        echo '<th>No. RM</th>';
        echo '<th>Tanggal</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $no = 1;
        $grand_total = 0;

        foreach ($rows->result() as $row) {
            $total = intval($row->trans_obat_total_price);
            $grand_total += $total;
            echo '<tr>';
            echo '<td align="center">' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($row->trans_obat_name ?? '') . '</td>';
            echo '<td align="center">' . htmlspecialchars($row->trans_obat_satuan ?? '') . '</td>';
            echo '<td align="center">' . intval($row->trans_obat_qty) . '</td>';
            echo '<td>' . htmlspecialchars($row->trans_obat_dosis ?: '-') . '</td>';
            echo '<td align="right">' . number_format($row->trans_obat_price, 0, ',', '.') . '</td>';
            echo '<td align="right">' . number_format($total, 0, ',', '.') . '</td>';
            echo '<td>' . htmlspecialchars($row->patient_name ?? '') . '</td>';
            echo '<td align="center">' . htmlspecialchars($row->trans_patient_code ?? '') . '</td>';
            echo '<td align="center">' . (!empty($row->trans_doc) ? date('d/m/Y', strtotime($row->trans_doc)) : '-') . '</td>';
            echo '</tr>';
        }

        echo '<tr style="font-weight:700;">';
        echo '<td colspan="6" align="right">Grand Total</td>';
        echo '<td align="right">' . number_format($grand_total, 0, ',', '.') . '</td>';
        echo '<td colspan="3"></td>';
        echo '</tr>';

        echo '</tbody></table>';
        echo '</body></html>';
    }

    private function format_date_db($date) {
        if (empty($date)) return null;
        $clean = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($clean));
    }
}
