<?php
class AnggotaM extends CI_Model{
    function login($data){
        //cleaning query from XSS
        $id = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $id = $this->db->escape_str($id);
        //set query
        $this->db->flush_cache();
        $this->db->select('nama');
        $this->db->where('no_induk',$id);
        $this->db->from('Anggota');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
    function tambahAnggota($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        $data = $this->db->escape_str($data);
        $this->db->flush_cache();
        
        //insert data ke db 'anggota'
        if(!$this->db->insert('anggota',$data)){
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