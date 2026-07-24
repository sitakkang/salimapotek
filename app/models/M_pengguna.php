<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_pengguna extends CI_Model {

    protected $table = 'conf_users';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all users with level name
     */
    public function get_all() {
        return $this->db->query(
            'SELECT a.id_user, a.fullname, a.username, a.nip, a.last_login, a.status, a.level,
                    b.name
             FROM conf_users a
             
             JOIN conf_level b ON a.level = b.id_level
             WHERE a.id_user != 1
             ORDER BY a.id_user'
        );
    }

    /**
     * Get single user by ID
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id_user' => intval($id)))->row();
    }

    /**
     * Get all levels for dropdown
     */
    public function get_level() {
        return $this->db->query('SELECT * FROM conf_level where id_level!=1 ORDER BY id_level')->result();
    }

    /**
     * Insert new user
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update user
     */
    public function update($id, $data) {
        $this->db->where('id_user', intval($id));
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete user
     */
    public function delete($id) {
        $this->db->where('id_user', intval($id));
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}
