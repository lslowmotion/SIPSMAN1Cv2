<?php
class PustakaM extends CI_Model{
    function getDaftarPustaka(){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('judul,pengarang,sampul');
        $this->db->from('pustaka');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
}