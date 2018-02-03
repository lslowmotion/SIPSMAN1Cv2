<?php
class AkunM extends CI_Model{
    function login($data){
        //cleaning query from XSS
        $id = $this->security->xss_clean($data['id']);
        $password = $this->security->xss_clean($data['password']);
        //cleaning query from SQL injection
        $id = $this->db->escape_str($id);
        $password = $this->db->escape_str($password);
        $this->db->flush_cache();
        $this->db->where('id',$id);
        $this->db->where('password',$password);
        $this->db->from('Akun');
        //execute query
        $query = $this->db->get();
        return $query->result();
    }
    function tambahAkun($data){
        $data=$this->security->xss_clean($data);
        $data=$this->db->escape_str($data);
        $this->db->flush_cache();
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
        $password=array (
            'password'=>$password
        );
        $this->db->where('id',$id);
        //execute query
        $query = $this->db->update('akun',$password);
    }
    function resetPassword($id){
        $this->db->flush_cache();
        $password=array (
            'password'=>$id
        );
        $this->db->where('id',$id);
        //execute query
        $query = $this->db->update('akun',$password);
    }
}