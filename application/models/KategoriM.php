<?php
class KategoriM extends CI_Model{
    function getDaftarKategori(){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('kode_klasifikasi,nama_kategori');
        $this->db->from('kategori');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
}