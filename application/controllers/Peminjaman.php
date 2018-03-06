<?php
class Peminjaman extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PeminjamanM');
        if(empty($this->session->userdata('id'))){
            redirect(base_url('akun'));
        } 
    }
    
    public function index(){
        //jika bukan admin, lempar ke daftar peminjaman berdasarkan no induk pengguna menggunakan URI segmen 3
        if(($this->session->userdata('level') == 'admin') || !empty($this->uri->segment('3'))){
            $this->load->view('head');
            $this->load->view('DaftarPeminjaman');
            $this->load->view('foot');
        }else{
            redirect(base_url('peminjaman/index/'.$this->session->userdata('id')));
        }
    }
    
    public function daftarPeminjaman(){
        //jika bukan admin dan tidak ada URI segmen 3, beri URI segmen 3 berdasarkan no induk anggota
        if($this->session->userdata('level') != 'admin' && (empty($this->uri->segment('3')))){
            redirect(base_url('peminjaman/daftarpeminjaman/'.$this->session->userdata('id')));
        }
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
        
        //mengambil URI segmen ke-3
        $no_induk = $this->uri->segment('3');
        
        //mencari jumlah data peminjaman
        $total_data = $this->PeminjamanM->getJumlahPeminjaman($no_induk);
        
        //memasukkan total data ke data terfilter sebagai inisialisasi
        $total_data_terfilter = $total_data;
        
        //apabila search POST dari DataTables kosong, ambil daftar peminjaman berdasarkan jumlah data, mulai fetch, kolom terurut, dan urutan
        if(empty($this->input->post('search')['value'])){
            $data_peminjaman = $this->PeminjamanM->getDaftarPeminjaman($panjang_data,$mulai_data,$kolom_urut,$urutan,$no_induk);
            //apabila search POST dari DataTables isi, ambil data peminjaman by search
        }else{
            //search dari POST
            $search = $this->input->post('search')['value'];
            
            //mengambil jumlah data terfilter dari search di db
            $total_data_terfilter = $this->PeminjamanM->getDaftarPeminjamanbySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$no_induk)['jumlah'];
            
            //apabila jumlah data terfilter lebih dari 0, isi $data_peminjaman dengan data hasil search
            if($total_data_terfilter > 0){
                $data_peminjaman = $this->PeminjamanM->getDaftarPeminjamanbySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$no_induk)['data'];
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
                    $this->convert_tanggal_kembali(strtotime($row->tanggal_kembali)),
                    
                    //menghitung denda
                    $this->hitung_denda(strtotime($row->tanggal_pinjam),strtotime($row->tanggal_kembali)),
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
    
    private function convert_tanggal_kembali($unix_tanggal_kembali){
        if($unix_tanggal_kembali == 0){
            $status = 'Belum dikembalikan';
        }else{
            $status = date("d M Y",$unix_tanggal_kembali);
        }
        return $status;
    }
    
    private function hitung_denda($unix_tanggal_pinjam,$unix_tanggal_kembali){
        //menentukan maksimal durasi pinjam dalam hari
        $durasi = 14;
        //menentukan denda dalam rupiah per hari
        $denda = 500;
        //maksimal durasi pinjam dalam UNIX time
        $unix_durasi = 86400 * $durasi;
        if($unix_tanggal_kembali != 0){
            $total_denda = max(0,((($unix_tanggal_kembali - $unix_tanggal_pinjam) - $unix_durasi) / 86400) * $denda);
        }else{
            $total_denda = max(0,(((strtotime('today') - $unix_tanggal_pinjam) - $unix_durasi) / 86400) * $denda);
        }
        return $total_denda;
    }
    
    public function pinjam(){
        
    }
}