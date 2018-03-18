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
    
    function index(){
        //jika bukan admin, lempar ke daftar peminjaman berdasarkan no induk pengguna menggunakan URI segmen 3
        if($this->session->userdata('level') == 'admin' || !empty($this->uri->segment('3'))){
            $this->load->view('head');
            $this->load->view('DaftarPeminjaman');
            $this->load->view('foot');
        }else{
            redirect(base_url('peminjaman/index/'.$this->session->userdata('id')));
        }
    }
    
    function daftarPeminjaman(){
        //jika bukan admin dan tidak ada URI segmen 3, beri URI segmen 3 berdasarkan no induk anggota
        if($this->session->userdata('level') != 'admin' && empty($this->uri->segment('3'))){
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
        $total_data = $this->PeminjamanM->getJumlahPeminjaman($no_induk,null);
        
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
        
        //jika $data_peminjaman tidak kosong, masukkan data yang akan di-parse ke DataTables dalam $data
        if(!empty($data_peminjaman)){
            foreach ($data_peminjaman as $row){
                $data[] = array(
                    $row->kode_transaksi,
                    $row->no_induk,
                    date("d M Y", strtotime($row->tanggal_pinjam)),
                    $this->convert_tanggal_kembali($row->tanggal_kembali),
                    
                    //menghitung denda
                    $this->hitung_denda(strtotime($row->tanggal_pinjam),$row->tanggal_kembali),
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
    
    //fungsi untuk mengubah tanggal kembali menjadi bentuk yang readable
    private function convert_tanggal_kembali($tanggal_kembali){
        if($tanggal_kembali == 'Belum dikembalikan'){
            $status = 'Belum dikembalikan';
        }else{
            $status = date("d M Y",strtotime($tanggal_kembali));
        }
        return $status;
    }
    
    //fungsi untuk menghitung denda berdasarkan tanggal pinjam dan tanggal kembali
    private function hitung_denda($unix_tanggal_pinjam,$tanggal_kembali){
        $id_aturan = 1;
        $aturan_receive = $this->PeminjamanM->getAturanPeminjaman($id_aturan);
        
        //menentukan maksimal durasi pinjam dalam hari
        $durasi = $aturan_receive->durasi;
        //menentukan denda dalam rupiah per hari
        $denda = $aturan_receive->denda;
        
        //maksimal durasi pinjam dalam UNIX time
        $unix_durasi = 86400 * $durasi;
        
        //convert tanggal kembali
        if($tanggal_kembali != 'Belum dikembalikan'){
            $total_denda = max(0,(((strtotime($tanggal_kembali) - $unix_tanggal_pinjam) - $unix_durasi) / 86400) * $denda);
        }else{
            $total_denda = max(0,(((strtotime('today') - $unix_tanggal_pinjam) - $unix_durasi) / 86400) * $denda);
        }
        return $total_denda;
    }
    
    function dataPeminjaman(){
        //load model yang diperlukan
        $this->load->model('PustakaM');
        $this->load->model('AnggotaM');
        //ambil no induk dari segmen ke 3 URI
        $kode_transaksi = $this->uri->segment('3');
        //fetch data peminjaman
        $data['data_peminjaman'] = $this->PeminjamanM->getDataPeminjaman($kode_transaksi);
        
        //tampilkan data peminjaman
        if(empty($data['data_peminjaman'])){
            redirect(base_url('peminjaman'));
        }else{
            //jika pengguna non admin mengakses data peminjaman selain milik sendiri, lempar ke status peminjaman
            if($this->session->userdata('level') != 'admin' && $data['data_peminjaman']->no_induk != $this->session->userdata('id')){
                redirect(base_url('peminjaman'));
            }
            $data['data_peminjaman']->tanggal_pinjam = date("d M Y", strtotime($data['data_peminjaman']->tanggal_pinjam));
            $data['data_peminjaman']->tanggal_kembali = $this->convert_tanggal_kembali($data['data_peminjaman']->tanggal_kembali);
            $data['data_peminjaman']->denda = $this->hitung_denda(strtotime($data['data_peminjaman']->tanggal_pinjam),$data['data_peminjaman']->tanggal_kembali);
            $data['data_pustaka'] = $this->PustakaM->getDataPustaka($data['data_peminjaman']->nomor_panggil);
            $data['data_anggota'] = $this->AnggotaM->getDataAnggota($data['data_peminjaman']->no_induk);
            $this->load->view('head');
            $this->load->view('DataPeminjaman',$data);
            $this->load->view('foot');
        }
    }
    
    function kembali(){
        //jika bukan admin, lempar pengguna
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('peminjaman'));
        }
        
        $kode_transaksi = $this->input->post('kode-transaksi');
        
        //cek apakah kode transaksi ada di db
        //jika post tidak kosong, lakukan pengecekan kode transaksi di db
        if(!empty($kode_transaksi)){
            $cek_kode_transaksi = $this->PeminjamanM->getDataPeminjaman($kode_transaksi);
        //jika kosong, set kode transaksi menjadi kosong
        }else{
            $cek_kode_transaksi = null;
        }
        
        //jika kode transaksi tidak kosong, proses pengembalian
        if(!empty($cek_kode_transaksi)){
            
            //cek tanggal_kembali apakah belum dikembalikan
            $cek_tanggal_kembali = $this->PeminjamanM->getDataPeminjaman($kode_transaksi)->tanggal_kembali;
            
            //jika tanggal kembali belum dikembalikan, proses pengembalian
            if($cek_tanggal_kembali == 'Belum dikembalikan'){
                
                
                //ambil nomor panggil, ambil data pustaka, edit pustaka kurangi jumlah_dipinjam pada pustaka bersangkutan
                $nomor_panggil = $this->PeminjamanM->getDataPeminjaman($kode_transaksi)->nomor_panggil;
                $this->load->model('PustakaM');
                $jumlah_dipinjam = $this->PustakaM->getDataPustaka($nomor_panggil)->jumlah_dipinjam;
                $jumlah_dipinjam --;
                $data_pustaka = array(
                    'nomor_panggil' => $nomor_panggil,
                    'jumlah_dipinjam' => $jumlah_dipinjam
                );
                $result = $this->PustakaM->editPustaka($data_pustaka);
                //jika gagal mengubah jumlah dipinjam pada data pustaka bersangkutan, lempar
                if($result != '0'){
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <strong>Terjadi kesalahan dalam mengubah jumlah pustaka dipinjam pada data pustaka.</strong>
                        </div>');
                    redirect(base_url('peminjaman/datapeminjaman/'.$kode_transaksi));
                }
                
                //eksekusi pencatatan pengembalian peminjaman pada tabel peminjaman
                $this->PeminjamanM->kembaliPeminjaman($kode_transaksi);
                
                $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                    Pengembalian peminjaman pustaka dengan kode transaksi '.$kode_transaksi.' berhasil diproses.
                </div>');
                redirect(base_url('peminjaman/datapeminjaman/'.$kode_transaksi));
            //jika tanggal kembali tidak kosong, informasikan ke pengguna bahwa transaksi tidak dapat diproses
            }else{
                $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                    Pengembalian tidak dapat diproses. Pengembalian pustaka dengan kode transaksi '.$kode_transaksi.' sudah pernah diproses sebelumnya.
                </div>');
                redirect(base_url('peminjaman/datapeminjaman/'.$kode_transaksi));
            }
        //jika kode transaksi kosong, lempar ke status peminjaman dan informasikan error ke pengguna
        }else{
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Terjadi kesalahan dalam transaksi pengembalian.</div>');
            redirect(base_url('peminjaman'));
        }
    }
    
    function pinjam(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('peminjaman'));
        }
        if(!empty($this->input->post('submit'))){
            //konfigurasi validasi data masukan
            $config = array(
                array(
                    'field' => 'nomor-panggil',
                    'label' => 'Nomor panggil',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'no-induk',
                    'label' => 'No induk',
                    'rules' => 'required|numeric',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka'
                    )
                ),
                array(
                    'field' => 'tanggal-pinjam',
                    'label' => 'Tanggal pinjam',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
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
                    redirect(base_url('peminjaman/pinjam'));
            }else{
                //load model yang diperlukan untuk verifikasi data masukan no induk dan nomor panggil
                $this->load->model('AnggotaM');
                $this->load->model('PustakaM');
                //lakukan verifikasi data masukan no induk dan nomor panggil
                $cek_no_induk = $this->AnggotaM->getDataAnggota($this->input->post('no-induk'));
                $cek_nomor_panggil = $this->PustakaM->getDataPustaka($this->input->post('nomor-panggil'));
                $cek_ketersediaan_pustaka = $cek_nomor_panggil->jumlah_pustaka - $cek_nomor_panggil->jumlah_dipinjam;
                //jika tidak ditemukan, lempar kembali ke form pinjam
                if($cek_no_induk == null || $cek_nomor_panggil == null || $cek_ketersediaan_pustaka == 0){
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                        No induk atau nomor panggil tidak dapat ditemukan dalam daftar pustaka yang tersedia. Pastikan data masukan transaksi peminjaman telah sesuai.
                        </div>');
                    redirect(base_url('peminjaman/pinjam'));
                }
                
                //lakukan verifikasi maksimal pinjam yang dibolehkan
                $id_aturan = 1;
                $maksimal_pinjam = $this->PeminjamanM->getAturanPeminjaman($id_aturan)->maksimal_pinjam;
                $no_induk = $this->input->post('no-induk');
                //menghitung jumlah peminjaman yang masih belum dikembalikan oleh anggota bersangkutan
                $jumlah_peminjaman_by_no_induk = $this->PeminjamanM->getJumlahPeminjaman($no_induk,'Belum dikembalikan') + 1;
                //jika peminjaman belum kembali lebih dari maksimal pinjam yang diperbolehkan, lempar
                if($jumlah_peminjaman_by_no_induk > $maksimal_pinjam){
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                        No induk '.$no_induk.' sedang meminjam jumlah koleksi pustaka maksimal yang diizinkan yaitu sebanyak '.$maksimal_pinjam.' eksemplar. Mohon kembalikan pustaka yang sedang dipinjam terlebih dahulu.
                        </div>');
                    redirect(base_url('peminjaman/pinjam'));
                }
                
                //mencari nomor kode transaksi yang tersedia
                $transaksi_ke = 1;
                $format_transaksi_ke = sprintf("%03d", $transaksi_ke);
                $kode_transaksi = date('ymd',strtotime($this->input->post('tanggal-pinjam'))).'-'.$format_transaksi_ke;
                $cek_ketersediaan_kode_transaksi = $this->PeminjamanM->getDataPeminjaman($kode_transaksi);
                while(!empty($cek_ketersediaan_kode_transaksi)){
                    $transaksi_ke ++;
                    $format_transaksi_ke = sprintf("%03d", $transaksi_ke);
                    $kode_transaksi = date('ymd',strtotime($this->input->post('tanggal-pinjam'))).'-'.$format_transaksi_ke;
                    $cek_ketersediaan_kode_transaksi = $this->PeminjamanM->getDataPeminjaman($kode_transaksi);
                }
                
                //jika lolos validasi, dan POST dimasukkan ke array untuk dimasukkan db
                $data_peminjaman = array(
                    'kode_transaksi' => $kode_transaksi,
                    'nomor_panggil' => $this->input->post('nomor-panggil'),
                    'no_induk' => $no_induk,
                    'tanggal_pinjam' => date('d M Y',strtotime($this->input->post('tanggal-pinjam'))),
                    'tanggal_kembali' => 'Belum dikembalikan'
                );
                
                
                //tambah jumlah dipinjam pada pustaka bersangkutan
                $jumlah_dipinjam = $cek_nomor_panggil->jumlah_dipinjam;
                $jumlah_dipinjam ++;
                $data_pustaka = array(
                    'nomor_panggil' => $this->input->post('nomor-panggil'),
                    'jumlah_dipinjam' => $jumlah_dipinjam
                );
                $result = $this->PustakaM->editPustaka($data_pustaka);
                //jika gagal mengubah jumlah dipinjam pada data pustaka bersangkutan, lempar
                if($result != '0'){
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <strong>Terjadi kesalahan dalam mengubah jumlah pustaka dipinjam pada data pustaka.</strong>
                        </div>');
                    redirect(base_url('peminjaman/pinjam'));
                }
                
                //insert array ke db, $result menerima kode eksepsi
                $result = $this->PeminjamanM->pinjamPeminjaman($data_peminjaman);
                
                //berhasil memasukkan data
                if($result == '0'){
                    //sukses transaksi, lempar ke data peminjaman bersangkutan
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success" role="alert">
                            Peminjaman dengan kode transaksi '.$kode_transaksi.' berhasil dicatat.
                        </div>'
                        );
                    redirect(base_url('peminjaman/datapeminjaman/'.$kode_transaksi));
                //gagal memasukkan data
                }else{
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <strong>Terjadi kesalahan dalam pencatatan transaksi peminjaman.</strong>
                        </div>');
                    redirect(base_url('peminjaman/pinjam'));
                }
            }
        }else{
            //ambil daftar anggota
            $this->load->model('AnggotaM');
            $jumlah_anggota = $this->AnggotaM->getJumlahAnggota();
            $data['daftar_anggota'] = $this->AnggotaM->getDaftarAnggota($jumlah_anggota,0,0,'asc');
            
            //ambil daftar pustaka
            $this->load->model('PustakaM');
            $jumlah_pustaka = $this->PustakaM->getJumlahPustaka(null);
            $data['daftar_pustaka'] = $this->PustakaM->getDaftarPustaka($jumlah_pustaka,0,0,'asc',null);
            
            //ambil aturan maksimal durasi peminjaman
            $id_aturan = 1;
            $data['durasi'] = $this->PeminjamanM->getAturanPeminjaman($id_aturan)->durasi;
            
            //tampilkan form
            $this->load->view('head');
            $this->load->view('FormPinjam',$data);
            $this->load->view('foot');
        }
    }
}