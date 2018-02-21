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
    
    function getJumlahAnggota(){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->from('anggota');
        //execute query
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function getDaftarAnggota($panjang_data,$mulai_data,$kolom_urut,$urutan){
        //cleaning query from XSS
        $panjang_data = $this->security->xss_clean($panjang_data);
        $mulai_data = $this->security->xss_clean($mulai_data);
        $kolom_urut = $this->security->xss_clean($kolom_urut);
        $urutan = $this->security->xss_clean($urutan);
        //cleaning query from SQL injection
        $panjang_data = $this->db->escape_str($panjang_data);
        $mulai_data = $this->db->escape_str($mulai_data);
        $kolom_urut = $this->db->escape_str($kolom_urut);
        $urutan = $this->db->escape_str($urutan);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('no_induk,nama,alamat');
        $this->db->from('anggota');
        $this->db->limit($panjang_data,$mulai_data);
        $this->db->order_by($kolom_urut,$urutan);
        
        //execute query
        $query = $this->db->get();
        
        return $query->result();
       
    }
    
    function getDaftarAnggotabySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan){
        //cleaning query from XSS
        $panjang_data = $this->security->xss_clean($panjang_data);
        $mulai_data = $this->security->xss_clean($mulai_data);
        $kolom_urut = $this->security->xss_clean($kolom_urut);
        $urutan = $this->security->xss_clean($urutan);
        $search = $this->security->xss_clean($search);
        //cleaning query from SQL injection
        $panjang_data = $this->db->escape_str($panjang_data);
        $mulai_data = $this->db->escape_str($mulai_data);
        $kolom_urut = $this->db->escape_str($kolom_urut);
        $urutan = $this->db->escape_str($urutan);
        $search = $this->db->escape_str($search);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('no_induk,nama,alamat');
        $this->db->from('anggota');
        $this->db->like('no_induk',$search);
        $this->db->or_like('nama',$search);
        $this->db->or_like('alamat',$search);
        $this->db->limit($panjang_data,$mulai_data);
        $this->db->order_by($kolom_urut,$urutan);
        
        //execute query       
        $query = $this->db->get();
        
        //return data sebagai array jumlah data dan data
        $data = array(
            'jumlah' => $query->num_rows(),
            'data' => $query->result()
        );
        
        return $data;
        
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
        //cleaning query from XSS
        $no_induk = $this->security->xss_clean($no_induk);
        //cleaning query from SQL injection
        $no_induk = $this->db->escape_str($no_induk);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->where('no_induk',$no_induk);
        //execute query
        $this->db->delete('anggota');
    }
}