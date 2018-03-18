<?php
class KunjunganM extends CI_Model{
    function getJumlahKunjungan($no_induk,$tanggal_kunjungan){
        //flush
        $this->db->flush_cache();
        //set query
        
        //filter no induk
        if(!empty($no_induk)){
            $this->db->where('no_induk',$no_induk);
        }
        
        //filter kunjungan
        if(!empty($tanggal_kunjungan)){
            $this->db->where('tanggal_kunjungan',$tanggal_kunjungan);
        }
        
        $this->db->from('kunjungan');
        //execute query
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function getDaftarKunjungan($panjang_data,$mulai_data,$kolom_urut,$urutan,$no_induk){
        //cleaning query from XSS
        $panjang_data = $this->security->xss_clean($panjang_data);
        $mulai_data = $this->security->xss_clean($mulai_data);
        $kolom_urut = $this->security->xss_clean($kolom_urut);
        $urutan = $this->security->xss_clean($urutan);
        $no_induk = $this->security->xss_clean($no_induk);
        //cleaning query from SQL injection
        $panjang_data = $this->db->escape_str($panjang_data);
        $mulai_data = $this->db->escape_str($mulai_data);
        $kolom_urut = $this->db->escape_str($kolom_urut);
        $urutan = $this->db->escape_str($urutan);
        $no_induk = $this->db->escape_str($no_induk);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->select('id_kunjungan,no_induk,tanggal_kunjungan');
        //filter no induk
        if(!empty($no_induk)){
            $this->db->where('no_induk',$no_induk);
        }
        
        $this->db->from('kunjungan');
        $this->db->limit($panjang_data,$mulai_data);
        $this->db->order_by($kolom_urut,$urutan);
        
        //execute query
        $query = $this->db->get();
        
        return $query->result();
        
    }
    
    function getDaftarKunjunganbySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$no_induk){
        //cleaning query from XSS
        $panjang_data = $this->security->xss_clean($panjang_data);
        $mulai_data = $this->security->xss_clean($mulai_data);
        $kolom_urut = $this->security->xss_clean($kolom_urut);
        $urutan = $this->security->xss_clean($urutan);
        $search = $this->security->xss_clean($search);
        $no_induk = $this->security->xss_clean($no_induk);
        //cleaning query from SQL injection
        $panjang_data = $this->db->escape_str($panjang_data);
        $mulai_data = $this->db->escape_str($mulai_data);
        $kolom_urut = $this->db->escape_str($kolom_urut);
        $urutan = $this->db->escape_str($urutan);
        $search = $this->db->escape_str($search);
        $no_induk = $this->db->escape_str($no_induk);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->group_start();
        $this->db->select('id_kunjungan,no_induk,tanggal_kunjungan');
        $this->db->from('kunjungan');
        $this->db->like('id_kunjungan',$search);
        $this->db->or_like('no_induk',$search);
        $this->db->or_like('tanggal_kunjungan',$search);
        $this->db->limit($panjang_data,$mulai_data);
        $this->db->order_by($kolom_urut,$urutan);
        $this->db->group_end();
        
        //filter no induk
        if(!empty($no_induk)){
            $this->db->where('no_induk',$no_induk);
        }
        
        //execute query
        $query = $this->db->get();
        
        //return data sebagai array jumlah data dan data
        $data = array(
            'jumlah' => $query->num_rows(),
            'data' => $query->result()
        );
        
        return $data;
        
    }
    
    function getDataKunjungan($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->from('kunjungan');
        $this->db->where('id_kunjungan',$data);
        //execute query
        $query = $this->db->get();
        return $query->row();
    }
    
    function tambahKunjungan($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //insert data ke db 'anggota'
        if(!$this->db->insert('kunjungan',$data)){
            //throw exception if failed
            $query = $this->db->error();
            return $query['code'];
        }else {
            //success
            $query='0';
            return $query;
        }
    }
}