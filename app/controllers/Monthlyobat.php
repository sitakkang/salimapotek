<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monthlyobat extends CI_Controller {

    public $dir_v = 'monthlyobat/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_monthlyobat');
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
            'src/js/admin/monthlyobat.js',
        );
        $data['panel'] = '<i class="fa fa-chart-bar"></i> &nbsp;<b>Laporan Obat Bulanan</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source
     */
    public function table() {
        $year  = intval($this->input->get('year'));
        $month = intval($this->input->get('month'));
        $mode  = $this->input->get('mode'); // 'monthly' or 'weekly'

        if (!$year) $year = date('Y');
        if (!$month) $month = date('n');

        $rows = $this->M_monthlyobat->get_report($year, $month, $mode);

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $data[] = array(
                'DT_RowId'  => $i,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->periode ?? ''),
                '2'         => htmlspecialchars($row->trans_obat_name ?? ''),
                '3'         => htmlspecialchars($row->trans_obat_satuan ?? ''),
                '4'         => intval($row->total_qty),
                '5'         => 'Rp ' . number_format($row->total_price, 0, ',', '.'),
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
     * Download Excel
     */
    public function download_excel() {
        $year  = intval($this->input->get('year'));
        $month = intval($this->input->get('month'));
        $mode  = $this->input->get('mode');

        if (!$year) $year = date('Y');
        if (!$month) $month = date('n');

        $rows = $this->M_monthlyobat->get_report($year, $month, $mode);

        $label = ($mode === 'weekly') ? 'Mingguan' : 'Bulanan';
        $filename = 'Laporan_Obat_' . $label . '_' . $year . '_' . $month . '.xls';

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
        echo '<th>Periode</th>';
        echo '<th>Nama Obat</th>';
        echo '<th>Satuan</th>';
        echo '<th>Total Qty</th>';
        echo '<th>Total Harga</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $no = 1;
        $grand_total = 0;

        foreach ($rows->result() as $row) {
            $grand_total += intval($row->total_price);
            echo '<tr>';
            echo '<td align="center">' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($row->periode ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row->trans_obat_name ?? '') . '</td>';
            echo '<td align="center">' . htmlspecialchars($row->trans_obat_satuan ?? '') . '</td>';
            echo '<td align="center">' . intval($row->total_qty) . '</td>';
            echo '<td align="right">' . number_format($row->total_price, 0, ',', '.') . '</td>';
            echo '</tr>';
        }

        echo '<tr style="font-weight:700;">';
        echo '<td colspan="5" align="right">Grand Total</td>';
        echo '<td align="right">' . number_format($grand_total, 0, ',', '.') . '</td>';
        echo '</tr>';

        echo '</tbody></table>';
        echo '</body></html>';
    }
}
