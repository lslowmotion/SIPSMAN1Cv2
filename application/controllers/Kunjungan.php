<?php
class Kunjungan extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('KunjunganM');
    }
    
    function index(){
        //jika bukan admin atau anggota membuka URI lv 3 selain id sendiri, lempar ke daftar kunjungan berdasarkan no induk pengguna menggunakan URI segmen 3
        if($this->session->userdata('level') != 'admin' && (empty($this->uri->segment('3')) || $this->session->userdata('id') != $this->uri->segment('3'))){
            redirect(base_url('kunjungan/index/'.$this->session->userdata('id')));
        }else{
            $this->load->view('head');
            $this->load->view('DaftarKunjungan');
            $this->load->view('foot');
        }
    }
    
    function daftarKunjungan(){
        //jika bukan admin dan tidak ada URI segmen 3 atau membuka URI lv 3 selain id sendiri, beri URI segmen 3 berdasarkan no induk anggota
        if($this->session->userdata('level') != 'admin' && (empty($this->uri->segment('3')) || $this->session->userdata('id') != $this->uri->segment('3'))){
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
        $total_data = $this->KunjunganM->getJumlahKunjungan($no_induk,null);
        
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
        if(empty($this->input->post('no-induk'))){
            $this->load->view('FormKunjungan');
        }else{
            $config = array(
                array(
                    'field' => 'no-induk',
                    'label' => 'No induk',
                    'rules' => 'required|numeric|max_length[18]',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka',
                        'max_length' => '%s tidak boleh lebih dari 18 karakter'
                    )
                )
            );
            $this->form_validation->set_rules($config);
            
            //validasi form
            if ($this->form_validation->run() == FALSE){
                
                //jika tidak lolos validasi, lempar kembali dengan alert
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger" role="alert">'
                        .validation_errors().
                    '</div>');
                redirect(base_url('kunjungan/tambahkunjungan'));
            }
            
            $no_induk = $this->input->post('no-induk');
            
            //cek no induk di data anggota
            $this->load->model('AnggotaM');
            $cek_anggota = $this->AnggotaM->getDataAnggota($no_induk);
            if(empty($cek_anggota)){
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger" role="alert">
                    Tidak ada anggota dengan no induk <b>'.$no_induk.'</b>. Mohon daftar terlebih dahulu pada petugas perpustakaan.
                    </div>');
                redirect(base_url('kunjungan/tambahkunjungan'));
            }
            
            //jika pengunjung dengan no induk bersangkutan telah tercatat pada tanggal kunjung sekarang, lempar
            $tanggal_kunjungan = date('d M Y',strtotime('Today'));
            $cek_kunjungan = $this->KunjunganM->getJumlahKunjungan($no_induk,$tanggal_kunjungan);
            if($cek_kunjungan > 0){
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger" role="alert">
                    Kunjungan no induk <b>'.$no_induk.'</b> pada tanggal <b>'.$tanggal_kunjungan.'</b> sudah dicatat sebelumnya.
                    </div>');
                redirect(base_url('kunjungan/tambahkunjungan'));
            }
            
            //mencari nomor id kunjungan yang tersedia
            $kunjungan_ke = 1;
            $format_kunjungan_ke = sprintf("%03d", $kunjungan_ke);
            $id_kunjungan = date('ymd',strtotime('Today')).'-'.$format_kunjungan_ke;
            $cek_ketersediaan_id_kunjungan = $this->KunjunganM->getDataKunjungan($id_kunjungan);
            while(!empty($cek_ketersediaan_id_kunjungan)){
                $kunjungan_ke ++;
                $format_kunjungan_ke = sprintf("%03d", $kunjungan_ke);
                $id_kunjungan = date('ymd',strtotime('Today')).'-'.$format_kunjungan_ke;
                $cek_ketersediaan_id_kunjungan = $this->KunjunganM->getDataKunjungan($id_kunjungan);
            }
            
            //format data masukan
            $data_kunjungan = array(
                'id_kunjungan' => $id_kunjungan,
                'no_induk' => $no_induk,
                'tanggal_kunjungan' => $tanggal_kunjungan
            );
            
            //insert array ke db, $result menerima kode eksepsi
            $result = $this->KunjunganM->tambahKunjungan($data_kunjungan);
            
            //berhasil memasukkan data
            if($result == '0'){
                //sukses transaksi, lempar ke data peminjaman bersangkutan
                $this->session->set_flashdata('message',
                    '<div class="alert alert-success" role="alert">
                        Selamat datang <b>'.$cek_anggota->nama.'</b>. Kunjungan anda berhasil dicatat.
                    </div>'
                );  
                redirect(base_url('kunjungan/tambahkunjungan'));
                //gagal memasukkan data
            }else{
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger" role="alert">
                        <strong>Terjadi kesalahan dalam pencatatan kunjungan no induk <b>'.$no_induk.'</b>.</strong>
                    </div>');
                redirect(base_url('kunjungan/tambahkunjungan'));
            }
        }
    }
}