<?php
class Peminjaman extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PeminjamanM');
    }
    
    public function index(){
        $this->load->view('head');
        $this->load->view('DaftarPeminjaman');
        $this->load->view('foot');
    }
    
    public function daftarPeminjaman(){
        //kolom untuk menentukan kolom db yang akan diurutkan dari POST DataTables
        $kolom = array(
            0 => 'kode_transaksi',
            1 => 'no_induk',
            2 => 'tanggal_pinjam',
            3 => 'tanggal_kembali'
        );
        
        //mengambil POST dari DataTables untuk kemudian dilempar ke db untuk fetch
        $panjang_data = $this->input->post('length'); //jumlah data difetch
        $mulai_data = $this->input->post('start'); //data mulai fetch dari data ke-sekian
        $kolom_urut = $kolom[$this->input->post('order')[0]['column']]; //kolom yang diurutkan
        $urutan = $this->input->post('order')[0]['dir']; //urutan (ascending/descending)
        
        //mencari jumlah data peminjaman
        $total_data = $this->PeminjamanM->getJumlahPeminjaman();
        
        //memasukkan total data ke data terfilter sebagai inisialisasi
        $total_data_terfilter = $total_data;
        
        //apabila search POST dari DataTables kosong, ambil daftar peminjaman berdasarkan jumlah data, mulai fetch, kolom terurut, dan urutan
        if(empty($this->input->post('search')['value'])){
            $data_peminjaman = $this->PeminjamanM->getDaftarPeminjaman($panjang_data,$mulai_data,$kolom_urut,$urutan);
            //apabila search POST dari DataTables isi, ambil data peminjaman by search
        }else{
            //search dari POST
            $search = $this->input->post('search')['value'];
            
            //mengambil jumlah data terfilter dari search di db
            $total_data_terfilter = $this->PeminjamanM->getDaftarPeminjamanbySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan)['jumlah'];
            
            //apabila jumlah data terfilter lebih dari 0, isi $data_peminjaman dengan data hasil search
            if($total_data_terfilter > 0){
                $data_peminjaman = $this->PeminjamanM->getDaftarPeminjamanbySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan)['data'];
                //jika tidak ditemukan, kosongi $data_peminjaman
            }else{
                $data_peminjaman = null;
            }
        }
        
        //menentukan maksimal durasi pinjam dalam hari
        $durasi = 14;
        //menentukan denda dalam rupiah per hari
        $denda = 500;
        //maksimal durasi pinjam dalam UNIX time
        $unix_durasi = 86400 * $durasi;
        
        //jika $data_peminjaman tidak kosong, masukkan data yang akan di-parse ke DataTables dalam $data
        if(!empty($data_peminjaman)){
            foreach ($data_peminjaman as $row){
                $data[] = array(
                    $row->kode_transaksi,
                    $row->no_induk,
                    date("d M Y", strtotime($row->tanggal_pinjam)),
                    date("d M Y", strtotime($row->tanggal_kembali)),
                    max(0,(((strtotime($row->tanggal_kembali) - strtotime($row->tanggal_pinjam)) - $unix_durasi) / 86400) * $denda),
                    '<a href="'.base_url('peminjaman/datapeminjaman/'.$row->kode_transaksi).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Detail</button></a>'
                );
            }
            //jika kosong, kosongi $data
        }else{
            $data = array();
        }
        
        //set data json
        $json_data = array(
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => intval($total_data),
            'recordsFiltered' => intval($total_data_terfilter),
            'data' => $data
        );
        
        //kirim json
        echo json_encode($json_data);
    }
    
    public function pinjam(){
        
    }
}