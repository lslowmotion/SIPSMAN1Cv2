<?php
class PustakaM extends CI_Model{
    function getDaftarPustaka(){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('judul,pengarang,sampul,jumlah,dipinjam,nomor_panggil');
        $this->db->from('pustaka');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
    
    function getDaftarPustakabyKategori($data){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('judul,pengarang,sampul,jumlah,dipinjam,nomor_panggil');
        $this->db->where('kode_klasifikasi',$data);
        $this->db->from('pustaka');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
    
    function tambahPustaka($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //insert data ke db 'pustaka'
        if(!$this->db->insert('pustaka',$data)){
            //throw exception if failed
            $query=$this->db->error();
            return $query['code'];
        }else {
            //success
            $query='0';
            return $query;
        }
    }
    
    function getJudulbyNomorPanggil($data){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('judul');
        $this->db->where('nomor_panggil',$data);
        $this->db->from('pustaka');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
    
}