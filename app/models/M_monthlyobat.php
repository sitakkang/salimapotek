<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_monthlyobat extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get aggregated report by month or week
     */
    public function get_report($year, $month, $mode = 'monthly') {
        if ($mode === 'weekly') {
            // Minggu dalam bulan
            $sql = "SELECT 
                        CONCAT('Minggu ke-', CEILING(DAY(C.trans_doc) / 7), ' ', ?) AS periode,
                        A.trans_obat_name,
                        A.trans_obat_satuan,
                        SUM(A.trans_obat_qty) AS total_qty,
                        SUM(A.trans_obat_total_price) AS total_price
                    FROM trans_obat A
                    LEFT JOIN trans_medical_record B ON A.medical_record_id = B.id_medical_record
                    LEFT JOIN trans_visit C ON B.visit_id = C.id_visit
                    WHERE A.trans_obat_status = 1
                      AND C.trans_status = 1
                      AND YEAR(C.trans_doc) = ?
                      AND MONTH(C.trans_doc) = ?
                    GROUP BY periode, A.trans_obat_name, A.trans_obat_satuan
                    ORDER BY periode, A.trans_obat_name";
            $params = array($this->month_name($month), $year, $month);
        } else {
            // Per bulan
            $sql = "SELECT 
                        ? AS periode,
                        A.trans_obat_name,
                        A.trans_obat_satuan,
                        SUM(A.trans_obat_qty) AS total_qty,
                        SUM(A.trans_obat_total_price) AS total_price
                    FROM trans_obat A
                    LEFT JOIN trans_medical_record B ON A.medical_record_id = B.id_medical_record
                    LEFT JOIN trans_visit C ON B.visit_id = C.id_visit
                    WHERE A.trans_obat_status = 1
                      AND C.trans_status = 1
                      AND YEAR(C.trans_doc) = ?
                      AND MONTH(C.trans_doc) = ?
                    GROUP BY A.trans_obat_name, A.trans_obat_satuan
                    ORDER BY A.trans_obat_name";
            $params = array($this->month_name($month) . ' ' . $year, $year, $month);
        }

        return $this->db->query($sql, $params);
    }

    private function month_name($n) {
        $map = array(
            1  => 'Januari',   2  => 'Februari', 3  => 'Maret',
            4  => 'April',     5  => 'Mei',      6  => 'Juni',
            7  => 'Juli',      8  => 'Agustus',  9  => 'September',
            10 => 'Oktober',   11 => 'November', 12 => 'Desember',
        );
        return isset($map[$n]) ? $map[$n] : '';
    }
}
