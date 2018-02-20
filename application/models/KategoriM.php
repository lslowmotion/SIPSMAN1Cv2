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
    
    function tambahKategori($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //insert data ke db 'kategori'
        if(!$this->db->insert('kategori',$data)){
            //throw exception if failed
            $query=$this->db->error();
            return $query['code'];
        }else {
            //success
            $query='0';
            return $query;
        }
    }
}