<?php
class AnggotaM extends CI_Model{
    function login($data){
        //cleaning query from XSS
        $id = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $id = $this->db->escape_str($id);
        //flush
        $this->db->flush_cache();
        //set query
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
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
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
    
    function getDaftarAnggota(){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('no_induk,nama,alamat');
        $this->db->from('anggota');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
    
    function getDataAnggota($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->from('anggota');
        $this->db->where('no_induk',$data);
        //execute query
        $query = $this->db->get();
        return $query->row();
    }
    
    function editAnggota($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        $this->db->where('no_induk',$data['no_induk']);
        if(!$this->db->update('anggota',$data)){
            $query=$this->db->error();
            return $query['code'];
        }else {
            $query='0';
            return $query;
        }
    }
    
    function hapusAnggota($no_induk){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->where('no_induk',$no_induk);
        //execute query
        $this->db->delete('anggota');
    }
}