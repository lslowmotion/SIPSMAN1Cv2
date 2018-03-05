<?php
class PustakaM extends CI_Model{
       
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
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
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
    
    function getJumlahPustaka($kode){
        //cleaning query from XSS
        $kode = $this->security->xss_clean($kode);
        //cleaning query from SQL injection
        $kode = $this->db->escape_str($kode);
        //flush
        $this->db->flush_cache();
        
        //set query
        //filter kode klasifikasi
        if(!empty($kode)){
            $this->db->where('kode_klasifikasi',$kode);
        }
        
        $this->db->from('pustaka');
        //execute query
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function getDaftarPustaka($panjang_data,$mulai_data,$kolom_urut,$urutan,$kode){
        //cleaning query from XSS
        $panjang_data = $this->security->xss_clean($panjang_data);
        $mulai_data = $this->security->xss_clean($mulai_data);
        $kolom_urut = $this->security->xss_clean($kolom_urut);
        $urutan = $this->security->xss_clean($urutan);
        $kode = $this->security->xss_clean($kode);
        //cleaning query from SQL injection
        $panjang_data = $this->db->escape_str($panjang_data);
        $mulai_data = $this->db->escape_str($mulai_data);
        $kolom_urut = $this->db->escape_str($kolom_urut);
        $urutan = $this->db->escape_str($urutan);
        $kode = $this->db->escape_str($kode);
        //flush
        $this->db->flush_cache();
  
        //set query
        $this->db->select('nomor_panggil,judul,pengarang,sampul,jumlah_pustaka,jumlah_dipinjam');
        //filter kode klasifikasi
        if(!empty($kode)){
            $this->db->where('kode_klasifikasi',$kode);
        }
        $this->db->from('pustaka');
        $this->db->limit($panjang_data,$mulai_data);
        $this->db->order_by($kolom_urut,$urutan);
        
        //execute query
        $query = $this->db->get();
        
        return $query->result();
        
    }
    
    function getDaftarPustakabySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$kode){
        //cleaning query from XSS
        $panjang_data = $this->security->xss_clean($panjang_data);
        $mulai_data = $this->security->xss_clean($mulai_data);
        $kolom_urut = $this->security->xss_clean($kolom_urut);
        $urutan = $this->security->xss_clean($urutan);
        $search = $this->security->xss_clean($search);
        $kode = $this->security->xss_clean($kode);
        //cleaning query from SQL injection
        $panjang_data = $this->db->escape_str($panjang_data);
        $mulai_data = $this->db->escape_str($mulai_data);
        $kolom_urut = $this->db->escape_str($kolom_urut);
        $urutan = $this->db->escape_str($urutan);
        $search = $this->db->escape_str($search);
        $kode = $this->db->escape_str($kode);
        //flush
        $this->db->flush_cache();
        
        //set query
        $this->db->group_start();
        $this->db->select('nomor_panggil,judul,pengarang,sampul,jumlah_pustaka,jumlah_dipinjam');
        $this->db->from('pustaka');
        $this->db->like('nomor_panggil',$search);
        $this->db->or_like('judul',$search);
        $this->db->or_like('pengarang',$search);
        $this->db->limit($panjang_data,$mulai_data);
        $this->db->order_by($kolom_urut,$urutan);
        $this->db->group_end();
        //filter kode klasifikasi
        if(!empty($kode)){
            $this->db->where('kode_klasifikasi',$kode);
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
    
    function getDataPustaka($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->from('pustaka');
        $this->db->where('nomor_panggil',$data);
        //execute query
        $query = $this->db->get();
        return $query->row();
    }
    
    function editPustaka($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        $this->db->where('nomor_panggil',$data['nomor_panggil']);
        if(!$this->db->update('pustaka',$data)){
            $query=$this->db->error();
            return $query['code'];
        }else {
            $query='0';
            return $query;
        }
    }
    
    function hapusPustaka($nomor_panggil){
        //cleaning query from XSS
        $nomor_panggil = $this->security->xss_clean($nomor_panggil);
        //cleaning query from SQL injection
        $nomor_panggil = $this->db->escape_str($nomor_panggil);
        
        //flush
        $this->db->flush_cache();
        
        //get data sampul, lalu hapus dari direktori
        //set query
        $this->db->select('sampul');
        $this->db->where('nomor_panggil',$nomor_panggil);
        $this->db->from('pustaka');
        //execute query
        $query = $this->db->get();
        //hapus sampul dari direktori
        $sampul_path = $query->row()->sampul;
        unlink('./'.$sampul_path);
        
        //flush
        $this->db->flush_cache();
        
        //set query
        $this->db->where('nomor_panggil',$nomor_panggil);
        //execute query
        $this->db->delete('pustaka');
    }
    
}