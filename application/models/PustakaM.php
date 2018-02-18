<?php
class PustakaM extends CI_Model{
    function getDaftarPustaka(){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('judul,pengarang,sampul,ketersediaan');
        $this->db->from('pustaka');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
    
    function getDaftarPustakabyKategori($data){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('judul,pengarang,sampul,ketersediaan');
        $this->db->where('kode_klasifikasi',$data);
        $this->db->from('pustaka');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
}