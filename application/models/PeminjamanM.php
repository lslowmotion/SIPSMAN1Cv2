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
    
    function getAturanPeminjaman($id){
        //cleaning query from XSS
        $id = $this->security->xss_clean($id);
        //cleaning query from SQL injection
        $id = $this->db->escape_str($id);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->where('id_aturan',$id);
        $this->db->from('aturan');
        $query = $this->db->get();
        return $query->row();
    }
    
    function getDataPeminjaman($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->from('peminjaman');
        $this->db->where('kode_transaksi',$data);
        //execute query
        $query = $this->db->get();
        return $query->row();
    }
    
    function kembaliPeminjaman($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //set tanggal kembali
        $now = date('d M Y');
        $data_update = array('tanggal_kembali' => $now);
        //set query
        $this->db->from('peminjaman');
        $this->db->where('kode_transaksi',$data);
        
        //execute query
        $query = $this->db->update('peminjaman',$data_update);
    }
    
    function pinjamPeminjaman($data){
        //cleaning query from XSS
        $data = $this->security->xss_clean($data);
        //cleaning query from SQL injection
        $data = $this->db->escape_str($data);
        //flush
        $this->db->flush_cache();
        //insert data ke db 'peminjaman'
        if(!$this->db->insert('peminjaman',$data)){
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