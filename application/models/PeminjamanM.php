<?php
class PeminjamanM extends CI_Model{
    function getJumlahPeminjaman($no_induk){
        //flush
        $this->db->flush_cache();
        //set query
        //filter no induk
        if(!empty($no_induk)){
            $this->db->where('no_induk',$no_induk);
        }
        
        $this->db->from('peminjaman');
        //execute query
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function getDaftarPeminjaman($panjang_data,$mulai_data,$kolom_urut,$urutan,$no_induk){
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
        $this->db->select('kode_transaksi,no_induk,tanggal_pinjam,tanggal_kembali');
        //filter no induk
        if(!empty($no_induk)){
            $this->db->where('no_induk',$no_induk);
        }
        
        $this->db->from('peminjaman');
        $this->db->limit($panjang_data,$mulai_data);
        $this->db->order_by($kolom_urut,$urutan);
        
        //execute query
        $query = $this->db->get();
        
        return $query->result();
        
    }
    
    function getDaftarPeminjamanbySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$no_induk){
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
        $this->db->select('kode_transaksi,no_induk,tanggal_pinjam,tanggal_kembali');
        $this->db->from('peminjaman');
        $this->db->like('kode_transaksi',$search);
        $this->db->or_like('no_induk',$search);
        $this->db->or_like('tanggal_pinjam',$search);
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
}