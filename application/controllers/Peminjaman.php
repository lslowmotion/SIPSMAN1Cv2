<?php
use Mpdf\Mpdf;

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
        //jika bukan admin atau anggota membuka URI lv 3 selain id sendiri, atau tidak ada URI segmen 3, lempar ke daftar peminjaman berdasarkan no induk pengguna menggunakan URI segmen 3
        if($this->session->userdata('level') != 'admin' && (empty($this->uri->segment('3')) || $this->session->userdata('id') != $this->uri->segment('3'))){
            redirect(base_url('peminjaman/index/'.$this->session->userdata('id')));
        }else{            
            $this->load->view('head');
            $this->load->view('DaftarPeminjaman');
            $this->load->view('foot');
        }
    }
    
    function daftarPeminjaman(){
        //jika bukan admin, anggota membuka URI lv 3 selain id sendiri, atau tidak ada URI segmen 3, beri URI segmen 3 berdasarkan no induk anggota
        if($this->session->userdata('level') != 'admin' && (empty($this->uri->segment('3')) || $this->session->userdata('id') != $this->uri->segment('3'))){
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
        $total_data = $this->PeminjamanM->getJumlahPeminjaman($no_induk,null,null);
        
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
            if($this->session->userdata('level') == 'admin'){
                foreach ($data_peminjaman as $row){
                    $data[] = array(
                        $row->kode_transaksi,
                        $row->no_induk,
                        date("d M Y", strtotime($row->tanggal_pinjam)),
                        $row->tanggal_kembali,
                        
                        //menghitung denda
                        $this->hitungDenda($row->tanggal_pinjam,$row->tanggal_kembali),
                        '<a href="'.base_url('peminjaman/datapeminjaman/'.$row->kode_transaksi).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Detail/Kembalikan</button></a>'
                    );
                }
            }else{
                foreach ($data_peminjaman as $row){
                    $data[] = array(
                        $row->kode_transaksi,
                        $row->no_induk,
                        date("d M Y", strtotime($row->tanggal_pinjam)),
                        $row->tanggal_kembali,
                        
                        //menghitung denda
                        $this->hitungDenda($row->tanggal_pinjam,$row->tanggal_kembali),
                        '<a href="'.base_url('peminjaman/datapeminjaman/'.$row->kode_transaksi).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Detail</button></a>'
                    );
                }
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
    
    //-- fungsi untuk mengubah tanggal kembali menjadi bentuk yang readable (DEPRECATED, bisa dihapus sewaktu-waktu)
    /* private function convertTanggalKembali($tanggal_kembali){
        if($tanggal_kembali == 'Belum dikembalikan'){
            $status = 'Belum dikembalikan';
        }else{
            $status = date('d M Y',strtotime($tanggal_kembali));
        }
        return $status;
    } */
    
    //-- fungsi untuk menghitung denda berdasarkan tanggal pinjam dan tanggal kembali
    private function hitungDenda($tanggal_pinjam,$tanggal_kembali){
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
            $total_denda = max(0,ceil(((strtotime($tanggal_kembali) - strtotime($tanggal_pinjam)) - $unix_durasi) / 86400) * $denda);
        }else{
            $total_denda = max(0,ceil(((strtotime('today') - strtotime($tanggal_pinjam)) - $unix_durasi) / 86400) * $denda);
        }
        return $total_denda;
    }
    
    function dataPeminjaman(){
        //load model yang diperlukan
        $this->load->model('PustakaM');
        $this->load->model('AnggotaM');
        //ambil kode transaksi dari segmen ke 3 URI
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
            $data['data_peminjaman']->tanggal_kembali = $data['data_peminjaman']->tanggal_kembali;
            $data['data_peminjaman']->denda = $this->hitungDenda($data['data_peminjaman']->tanggal_pinjam,$data['data_peminjaman']->tanggal_kembali);
            $data['data_pustaka'] = $this->PustakaM->getDataPustaka($data['data_peminjaman']->nomor_panggil);
            $data['data_anggota'] = $this->AnggotaM->getDataAnggota($data['data_peminjaman']->no_induk);
            $this->load->view('head');
            $this->load->view('DataPeminjaman',$data);
            $this->load->view('foot');
        }
    }
    
    function cetakStrukPeminjaman(){
        //jika bukan admin, lempar pengguna
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('peminjaman'));
        }
        
        //load model yang diperlukan
        $this->load->model('PustakaM');
        $this->load->model('AnggotaM');
        //ambil kode transaksi dari segmen ke 3 URI
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
            $data['data_peminjaman']->tanggal_kembali = $data['data_peminjaman']->tanggal_kembali;
            $data['data_peminjaman']->denda = $this->hitungDenda($data['data_peminjaman']->tanggal_pinjam,$data['data_peminjaman']->tanggal_kembali);
            $data['data_pustaka'] = $this->PustakaM->getDataPustaka($data['data_peminjaman']->nomor_panggil);
            $data['data_anggota'] = $this->AnggotaM->getDataAnggota($data['data_peminjaman']->no_induk);
            $view = $this->load->view('FileStrukPeminjaman',$data,true);
            
            //pengaturan ukuran kertas dan margin mpdf
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A6',
                'margin_left' => 10,
                'margin_top' => 10,
                'margin_right' => 10,
                'margin_bottom' => 10,
                'default_font_size' => 9
            ]);
            
            //header file pdf
            $mpdf->WriteHTML('<h3>Bukti Pengembalian Pustaka Perpustakaan SMA N 1 Cilacap</h3>');
            
            //body file pdf
            $mpdf->WriteHTML($view);
            
            //nama file pdf pada browser
            $mpdf->SetTitle('Bukti Pengembalian');
            
            //nama file pdf pada download
            $mpdf->Output('Bukti Pengembalian.pdf','I');
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
                //menghitung jumlah peminjaman yang masih belum dikembalikan oleh anggota bersangkutan + 1 (karena akan pinjam baru)
                $jumlah_peminjaman_by_no_induk = $this->PeminjamanM->getJumlahPeminjaman($no_induk,null,'Belum dikembalikan') + 1;
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
    
    function pengaturanPeminjaman(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('peminjaman'));
        }
        
        //jika POST kosong, lempar ke form
        if(empty($this->input->post('submit'))){
            $id_aturan = 1;
            $data['data_aturan'] = $this->PeminjamanM->getAturanPeminjaman($id_aturan);
            $this->load->view('head');
            $this->load->view('FormPengaturanPeminjaman',$data);
            $this->load->view('foot');
        }else{ //jika POST tidak kosong, proses
        
            $config = array(
                array(
                    'field' => 'denda',
                    'label' => 'Nominal denda',
                    'rules' => 'required|numeric',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka (dalam rupiah)'
                    )
                ),
                array(
                    'field' => 'durasi',
                    'label' => 'Maksimal durasi pinjam',
                    'rules' => 'required|numeric',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka (dalam hari)'
                    ),
                ),
                array(
                    'field' => 'maksimal-pinjam',
                    'label' => 'Maksimal koleksi pustaka yang boleh dipinjam',
                    'rules' => 'required|numeric',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka (dalam eksemplar)'
                    )
                )
            );
            //validasi data masukan
            $this->form_validation->set_rules($config);
            //tampilkan info apabila error validasi
            if($this->form_validation->run() == FALSE){
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger" role="alert">
                        <b>Terjadi Kesalahan:</b><br>'.validation_errors().'
                    </div>');
                redirect(base_url('peminjaman/pengaturanpeminjaman'));
            }
            
            $id_aturan = 1;
            
            $data_aturan = array(
                'id_aturan' => $id_aturan,
                'denda' => $this->input->post('denda'),
                'durasi' => $this->input->post('durasi'),
                'maksimal_pinjam' => $this->input->post('maksimal-pinjam')
            );
            
            $result = $this->PeminjamanM->editAturanPeminjaman($data_aturan);
            
            //jika berhasil memasukkan data ke dalam db
            if($result=='0'){
                $this->session->set_flashdata('message',
                    '<div class="alert alert-success" role="alert">Aturan peminjaman berhasil diubah</div>');
                    redirect(base_url('peminjaman/pengaturanpeminjaman'));
                    //gagal memasukkan data ke dalam db
            }else{
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger" role="alert">
                                <b>Terjadi kesalahan dalam mengubah aturan peminjaman.</b>
                                , Kode : <strong>'.$result.'</strong>
                            </div>');
                redirect(base_url('peminjaman/pengaturanpeminjaman'));
            }
        }
    }
    
    function showFileDaftarPeminjaman(){
        
        $panjang_data = $this->PeminjamanM->getJumlahPeminjaman(null,null,null);
        
        $data_peminjaman = $this->PeminjamanM->getDaftarPeminjaman($panjang_data,0,0,'desc',null);
        
        foreach ($data_peminjaman as $row){
            $row->denda = $this->hitungDenda($row->tanggal_pinjam,$row->tanggal_kembali);
        }
        
        $data['data_peminjaman'] = $data_peminjaman;
        
        $this->load->view('FileDaftarPeminjaman',$data);
    }
    
    function cetakDaftarPeminjaman(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('peminjaman'));
        }
        
        //load model yang diperlukan
        $this->load->model('AnggotaM');
        $this->load->model('PustakaM');
        
        //validasi masukan POST untuk pengaturan bulan dan tahun yang dicetak
        if(!empty($this->input->post('submit'))){
            //konfigurasi validasi data masukan
            $config = array(
                array(
                    'field' => 'bulan',
                    'label' => 'Bulan pada menu Cetak Status Peminjaman',
                    'rules' => 'max_length[3]',
                    'errors' => array('max_length' => '%s harus berupa 3 karakter')
                ),
                array(
                    'field' => 'tahun',
                    'label' => 'Tahun pada menu Cetak Status Peminjaman',
                    'rules' => 'numeric|max_length[4]|min_length[4]',
                    'errors' => array(
                        'numeric' => '%s harus berupa angka',
                        'max_length' => '%s tidak valid. Tahun harus berupa 4 digit angka',
                        'min_length' => '%s tidak valid. Tahun harus berupa 4 digit angka'
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
                    redirect(base_url('peminjaman'));
            }
        }
        
        //menerima POST pengaturan bulan dan tahun
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        
        //mengatur search query agar memiliki format yang tepat (contoh: Mar 2018)
        $filterbulantahun = $bulan.' '.$tahun;
        
        //mencari jumlah data peminjaman
        $panjang_data = $this->PeminjamanM->getJumlahPeminjaman(null,null,null);
        
        //fetch data peminjaman berdasarkan search query bulan tahun
        $data_peminjaman = $this->PeminjamanM->getDaftarPeminjamanbySearch($panjang_data,0,$filterbulantahun,0,'desc',null)['data'];
        foreach ($data_peminjaman as $row){
            $row->nama = $this->AnggotaM->getDataAnggota($row->no_induk)->nama;
            $row->judul = $this->PustakaM->getDataPustaka($row->nomor_panggil)->judul;
            $row->denda = $this->hitungDenda($row->tanggal_pinjam,$row->tanggal_kembali);
        }
        $data['data_peminjaman'] = $data_peminjaman;
        
        //melempar data peminjaman ke view FileDaftarPeminjaman dan menyimpannya ke dalam variabel $view
        $view = $this->load->view('FileDaftarPeminjaman',$data,true);
        
        //mengubah format bulan menjadi bentuk panjang untuk digunakan pada header file pdf
        switch ($bulan) {
            case "Jan":
                $bulan = 'Januari';
                break;
            case "Feb":
                $bulan = 'Februari';
                break;
            case "Mar":
                $bulan = 'Maret';
                break;
            case "Apr":
                $bulan = 'April';
                break;
            case "May":
                $bulan = 'Mei';
                break;
            case "Jun":
                $bulan = 'Juni';
                break;
            case "Jul":
                $bulan = 'Juli';
                break;
            case "Aug":
                $bulan = 'Agustus';
                break;
            case "Sep":
                $bulan = 'September';
                break;
            case "Oct":
                $bulan = 'Oktober';
                break;
            case "Nov":
                $bulan = 'November';
                break;
            case "Dec":
                $bulan = 'Desember';
                break;
            default:
                $bulan = '';
        }
        
        //mengubah format tahun menjadi bentuk normal untuk digunakan pada header file pdf
        if (empty($tahun)){
            $tahun = '';
        }else{
            $tahun = ' '.$tahun;
        }
        
        //pengaturan ukuran kertas dan margin mpdf
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 25,
            'margin_top' => 25,
            'margin_right' => 25,
            'margin_bottom' => 25
        ]);
        
        //header file pdf
        $mpdf->WriteHTML('<h2>Daftar Peminjaman Perpustakaan SMA N 1 Cilacap</h2><h3>'.$bulan.$tahun.'</h3>');
        
        //body file pdf
        $mpdf->WriteHTML($view);
        
        //nama file pdf pada browser
        $mpdf->SetTitle('Daftar Peminjaman Perpustakaan');
        
        //nama file pdf pada download
        $mpdf->Output('Daftar Peminjaman Perpustakaan.pdf','I');
    }
}