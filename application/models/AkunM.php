<?php
class AkunM extends CI_Model{
    function login($data){
        //cleaning query from XSS
        $id = $this->security->xss_clean($data['id']);
        $password = $this->security->xss_clean($data['password']);
        //cleaning query from SQL injection
        $id = $this->db->escape_str($id);
        $password = $this->db->escape_str($password);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->where('id',$id);
        $this->db->where('password',$password);
        $this->db->from('Akun');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
    
    function tambahAkun($data){
        //cleaning query from XSS
        $data=$this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data=$this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //execute query
        if(!$this->db->replace('akun',$data)){
            $query=$this->db->error();
            return $query['code'];
        }else {
            $query='0';
            return $query;
        }
    }
    
    function editPassword($data){
        //cleaning query from XSS
        $id = $this->security->xss_clean($data['id']);
        $password = $this->security->xss_clean($data['password']);
        //cleaning query from SQL injection
        $id = $this->db->escape_str($id);
        $password = $this->db->escape_str($password);
        //set query
        $this->db->flush_cache();
        $data_update = array(
            'password' => $password
        );
        $this->db->where('id',$id);
        //execute query
        $query = $this->db->update('akun',$data_update);
    }
    
    function resetPassword($id){
        //cleaning query from XSS
        $id = $this->security->xss_clean($id);
        //cleaning query from SQL injection
        $id = $this->db->escape_str($id);
        //flush cache
        $this->db->flush_cache();
        
        //menyamakan id dan password
        $password=array (
            'password'=>$id
        );
        
        //set query
        $this->db->where('id',$id);
        //execute query
        $query = $this->db->update('akun',$password);
    }
    
    function hapusAkun($id){
        //cleaning query from XSS
        $id = $this->security->xss_clean($id);
        //cleaning query from SQL injection
        $id = $this->db->escape_str($id);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->where('id',$id);
        //execute query
        $this->db->delete('akun');
    }
}