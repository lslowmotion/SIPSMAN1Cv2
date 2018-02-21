<?php
class KategoriM extends CI_Model{
        
    function getJumlahKategori(){
        //flush
        $this->db->flush_cache();
        //set query
        $this->db->from('kategori');
        //execute query
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function getDaftarKategori($panjang_data,$mulai_data,$kolom_urut,$urutan){
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
        //jika tidak kosong, tentukan panjang dan mulai data
        if(!empty($panjang_data && $mulai_data)){
            $this->db->limit($panjang_data,$mulai_data);
        }
        $this->db->order_by($kolom_urut,$urutan);
        $this->db->from('kategori');
        //execute query
        $query = $this->db->get();
        
        return $query->result();
        
    }
    
    function getDaftarKategoribySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan){
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
        $this->db->like('kode_klasifikasi',$search);
        $this->db->or_like('nama_kategori',$search);
        $this->db->limit($panjang_data,$mulai_data);
        $this->db->order_by($kolom_urut,$urutan);
        $this->db->from('kategori');
        //execute query
        $query = $this->db->get();
        
        //return data sebagai array jumlah data dan data
        $data = array(
            'jumlah' => $query->num_rows(),
            'data' => $query->result()
        );
        
        return $data;
        
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