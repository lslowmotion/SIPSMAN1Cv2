<?php
class Kunjungan extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('KunjunganM');
    }
    
    function index(){
        //jika bukan admin, lempar ke daftar peminjaman berdasarkan no induk pengguna menggunakan URI segmen 3
        if($this->session->userdata('level') == 'admin' || !empty($this->uri->segment('3'))){
            $this->load->view('head');
            $this->load->view('DaftarKunjungan');
            $this->load->view('foot');
        }else{
            redirect(base_url('peminjaman/index/'.$this->session->userdata('id')));
        }
    }
    
    function daftarKunjungan(){
        //jika bukan admin dan tidak ada URI segmen 3, beri URI segmen 3 berdasarkan no induk anggota
        if($this->session->userdata('level') != 'admin' && empty($this->uri->segment('3'))){
            redirect(base_url('kunjungan/daftarkunjungan/'.$this->session->userdata('id')));
        }
        //kolom untuk menentukan kolom db yang akan diurutkan dari POST DataTables
        $kolom = array(
            0 => 'id_kunjungan',
            1 => 'no_induk',
            2 => 'tanggal_kunjungan'
        );
        
        //mengambil POST dari DataTables untuk kemudian dilempar ke db untuk fetch
        $panjang_data = $this->input->post('length'); //jumlah data difetch
        $mulai_data = $this->input->post('start'); //data mulai fetch dari data ke-sekian
        $kolom_urut = $kolom[$this->input->post('order')[0]['column']]; //kolom yang diurutkan
        $urutan = $this->input->post('order')[0]['dir']; //urutan (ascending/descending)
        
        //mengambil URI segmen ke-3
        $no_induk = $this->uri->segment('3');
        
        //mencari jumlah data kunjungan
        $total_data = $this->KunjunganM->getJumlahKunjungan($no_induk);
        
        //memasukkan total data ke data terfilter sebagai inisialisasi
        $total_data_terfilter = $total_data;
        
        //apabila search POST dari DataTables kosong, ambil daftar kunjungan berdasarkan jumlah data, mulai fetch, kolom terurut, dan urutan
        if(empty($this->input->post('search')['value'])){
            $data_kunjungan = $this->KunjunganM->getDaftarKunjungan($panjang_data,$mulai_data,$kolom_urut,$urutan,$no_induk);
            //apabila search POST dari DataTables isi, ambil data kunjungan by search
        }else{
            //search dari POST
            $search = $this->input->post('search')['value'];
            
            //mengambil jumlah data terfilter dari search di db
            $total_data_terfilter = $this->KunjunganM->getDaftarKunjunganbySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$no_induk)['jumlah'];
            
            //apabila jumlah data terfilter lebih dari 0, isi $data_kunjungan dengan data hasil search
            if($total_data_terfilter > 0){
                $data_kunjungan = $this->KunjunganM->getDaftarKunjunganbySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$no_induk)['data'];
                //jika tidak ditemukan, kosongi $data_kunjungan
            }else{
                $data_kunjungan = null;
            }
        }
        
        //jika $data_kunjungan tidak kosong, masukkan data yang akan di-parse ke DataTables dalam $data
        if(!empty($data_kunjungan)){
            foreach ($data_kunjungan as $row){
                $data[] = array(
                    $row->id_kunjungan,
                    $row->no_induk,
                    date("d M Y", strtotime($row->tanggal_kunjungan)),
                    '<a href="'.base_url('anggota/dataanggota/'.$row->no_induk).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Data Pengunjung</button></a>'
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
    
    function tambahKunjungan(){
        $this->load->view('FormKunjungan');
    }
}