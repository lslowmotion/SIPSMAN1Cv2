<?php
use Mpdf\Mpdf;

class Pustaka extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //load model PustakaM
        $this->load->model('PustakaM');
    }
    
    function index(){
        $this->load->view('head');
        $this->load->view('DaftarPustaka');
        $this->load->view('foot');
    }
    
    function daftarPustaka(){
        //kolom untuk menentukan kolom db yang akan diurutkan dari POST DataTables
        $kolom = array(
            0 => 'nomor_panggil',
            1 => 'judul',
            2 => 'pengarang'
        );
        
        //mengambil POST dari DataTables untuk kemudian dilempar ke db untuk fetch
        $panjang_data = $this->input->post('length'); //jumlah data difetch
        $mulai_data = $this->input->post('start'); //data mulai fetch dari data ke-sekian
        $kolom_urut = $kolom[$this->input->post('order')[0]['column']]; //kolom yang diurutkan
        $urutan = $this->input->post('order')[0]['dir']; //urutan (ascending/descending)
        
        //mengambil URI segmen ke-3
        $kode = $this->uri->segment('3');
        
        //mencari jumlah data pustaka
        $total_data = $this->PustakaM->getJumlahPustaka($kode);
        
        //memasukkan total data ke data terfilter sebagai inisialisasi
        $total_data_terfilter = $total_data;
        
        //apabila search POST dari DataTables kosong, ambil daftar pustaka berdasarkan jumlah data, mulai fetch, kolom terurut, dan urutan
        if(empty($this->input->post('search')['value'])){
            $data_pustaka = $this->PustakaM->getDaftarPustaka($panjang_data,$mulai_data,$kolom_urut,$urutan,$kode);
            //apabila search POST dari DataTables isi, ambil data pustaka by search
        }else{
            //search dari POST
            $search = $this->input->post('search')['value'];
            
            //mengambil jumlah data terfilter dari search di db
            $total_data_terfilter = $this->PustakaM->getDaftarPustakabySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$kode)['jumlah'];
            
            //apabila jumlah data terfilter lebih dari 0, isi $data_pustaka dengan data hasil search
            if($total_data_terfilter > 0){
                $data_pustaka = $this->PustakaM->getDaftarPustakabySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan,$kode)['data'];
                //jika tidak ditemukan, kosongi $data_pustaka
            }else{
                $data_pustaka = null;
            }
        }

        //jika tidak admin, cukup tampilkan tombol detail
        if($this->session->userdata('level') != 'admin'){
            //jika $data_pustaka tidak kosong, masukkan data yang akan di-parse ke DataTables dalam $data
            if(!empty($data_pustaka)){
                foreach ($data_pustaka as $row){
                    $data[] = array(
                        $row->nomor_panggil,
                        $row->judul,
                        $row->pengarang,
                        
                        '<a href="#"><img class="center-block" data-toggle="modal" data-target="#sampulModal" data-sampul="
							    '.base_url($row->sampul).
                        '" data-judul="'.$row->judul.'" src="'
                        .base_url($row->sampul).
                        '" alt="'.$row->judul.'" style="width:80px;"></a>',
                        
                        $row->jumlah_pustaka - $row->jumlah_dipinjam.' eksemplar',
                        
                        '<a href="'.base_url('pustaka/datapustaka/'.$row->nomor_panggil).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Detail</button></a>'
                        
                    );
                }
                //jika kosong, kosongi $data
            }else{
                $data = array();
            }
        //jika admin, tampilkan semua tombol menu
        }else{
            //jika $data_pustaka tidak kosong, masukkan data yang akan di-parse ke DataTables dalam $data
            if(!empty($data_pustaka)){
                foreach ($data_pustaka as $row){
                    $data[] = array(
                        $row->nomor_panggil,
                        $row->judul,
                        $row->pengarang,
                        
                        '<a href="#"><img class="center-block" data-toggle="modal" data-target="#sampulModal" data-sampul="
							    '.base_url($row->sampul).
                        '" data-judul="'.$row->judul.'" src="'
                        .base_url($row->sampul).
                        '" alt="'.$row->judul.'" style="width:80px;"></a>',
                        
                        $row->jumlah_pustaka - $row->jumlah_dipinjam.' eksemplar',
                        
                        '<a href="'.base_url('pustaka/datapustaka/'.$row->nomor_panggil).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Detail</button></a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapusModal" data-nomor-panggil="'.$row->nomor_panggil.'" data-judul="'.$row->judul.'"><i class="fa fa-trash"></i> Hapus</button>'
                        
                    );
                }
                //jika kosong, kosongi $data
            }else{
                $data = array();
            }
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
    
        
    function tambahPustaka(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        }
        if(!empty($this->input->post('submit'))){
            //konfigurasi validasi data masukan
            $config = array(
                array(
                    'field' => 'isbn',
                    'label' => 'ISBN',
                    'rules' => 'numeric',
                    'errors' => array('numeric' => '%s harus berupa angka')
                ),
                array(
                    'field' => 'kode-klasifikasi',
                    'label' => 'Kode klasifikasi',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'judul',
                    'label' => 'Judul',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'pengarang',
                    'label' => 'Pengarang',
                    //'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                array(
                    'field' => 'penerbit',
                    'label' => 'Penerbit',
                    //'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                array(
                    'field' => 'kota-terbit',
                    'label' => 'Penerbit',
                    //'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'tahun-terbit',
                    'label' => 'Tahun terbit',
                    'rules' => 'required|numeric|max_length[4]',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa 4 digit angka',
                        'max_length' => '%s harus berupa 4 digit angka'
                    )
                ),
                array(
                    'field' => 'penerbit',
                    'label' => 'Penerbit',
                    //'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'jumlah',
                    'label' => 'Jumlah koleksi pustaka',
                    'rules' => 'required|numeric|greater_than_equal_to[1]',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka',
                        'greater_than_equal_to' => "%s harus lebih dari 0"
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
                    redirect(base_url('pustaka/tambahpustaka'));
            }else{
                //mencari nomor panggil yang belum terdaftar
                $eksemplar = 1;
                $nomor_panggil = $this->input->post('nomor-panggil').'.'.$eksemplar;
                $nama_file = $this->input->post('nomor-panggil').'_'.$eksemplar;
                $cek_ketersediaan_nomor_panggil = $this->PustakaM->getDataPustaka($nomor_panggil);
                while(!empty($cek_ketersediaan_nomor_panggil)){
                    $eksemplar ++;
                    $nomor_panggil = $this->input->post('nomor-panggil').'.'.$eksemplar;
                    $nama_file = $this->input->post('nomor-panggil').'_'.$eksemplar;
                    $cek_ketersediaan_nomor_panggil = $this->PustakaM->getDataPustaka($nomor_panggil);
                }
                
                //konfigurasi upload sampul
                $upload_config = array(
                    'upload_path' => './assets/cover',
                    'allowed_types' => 'gif|jpg|jpeg|png|bmp',
                    'file_name' => $nama_file
                );
                
                //upload sampul
                $this->load->library('upload',$upload_config);
                $upload_sampul = 'sampul';
                
                //jika gagal upload, tampilkan eksepsi error
                if (!$this->upload->do_upload($upload_sampul))
                {
                    $error_upload = $this->upload->display_errors();
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <strong>'.$error_upload.'</strong>
                        </div>');
                    redirect(base_url('pustaka/tambahpustaka'));
                }
                
                //jika berhasil upload, masukkan data ke db
                else
                {
                    $link_upload = 'assets/cover/'.$this->upload->data('file_name');
                                       
                    //jika lolos validasi, data POST dimasukkan ke array untuk dimasukkan db
                    $data_pustaka = array(
                        'nomor_panggil' => $nomor_panggil,
                        'isbn' => $this->input->post('isbn'),
                        'kode_klasifikasi' => $this->input->post('kode-klasifikasi'),
                        'judul' => $this->input->post('judul'),
                        'pengarang' => $this->input->post('pengarang'),
                        'penerbit' => $this->input->post('penerbit'),
                        'kota_terbit' => $this->input->post('kota-terbit'),
                        'tahun_terbit' => $this->input->post('tahun-terbit'),
                        'sampul' => $link_upload,
                        'jumlah_pustaka' => $this->input->post('jumlah')
                    );
                    //insert array ke db, $result menerima kode eksepsi
                    $result = $this->PustakaM->tambahPustaka($data_pustaka);
                    
                    //berhasil memasukkan data
                    if($result == '0'){
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-success" role="alert">Pustaka dengan:
                                <br>Nomor panggil: <b>'
                                .$data_pustaka['nomor_panggil'].
                                '</b><br>Judul: <b>'
                                .$data_pustaka['judul'].
                                '</b><br>Jumlah eksemplar: <b>'
                                .$data_pustaka['jumlah_pustaka'].
                                '</b><br>berhasil ditambahkan
                            </div>'
                        );
                        redirect(base_url('pustaka/tambahpustaka'));
                        //gagal memasukkan data
                    }else{
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-danger" role="alert">
                            <strong>Terjadi kesalahan dalam memasukkan data pustaka.</strong>
                        </div>');
                        redirect(base_url('pustaka/tambahpustaka'));
                    }
                }
            }
        //jika tidak submit, cukup tampilkan form tambah pustaka
        }else{
            $this->load->model('KategoriM');
            $data['daftar_kategori'] = $this->KategoriM->getDaftarKategori(null,null,'kode_klasifikasi','asc');
            $this->load->view('head');
            $this->load->view('FormTambahPustaka',$data);
            $this->load->view('foot');
        }
    }
    
    function dataPustaka(){
        //ambil no induk dari segmen ke 3 URI
        $nomor_panggil = $this->uri->segment('3');
        //fetch data pustaka
        $data['data_pustaka'] = $this->PustakaM->getDataPustaka($nomor_panggil);
        
        //mengambil kategori berdasarkan kode klasifikasi
        if(isset($data['data_pustaka'])){
            $kode_klasifikasi = $data['data_pustaka']->kode_klasifikasi;
            $this->load->model('KategoriM');
            $data['data_kategori'] = $this->KategoriM->getDataKategori($kode_klasifikasi);
        }
        
        //tampilkan data pustaka
        if(empty($data['data_pustaka'])){
            redirect(base_url('pustaka'));
        }else{
            $this->load->view('head');
            $this->load->view('DataPustaka',$data);
            $this->load->view('foot');
        }
    }
    
    function editPustaka(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        }
        //ambil no induk dari segmen ke-3 URI
        $nomor_panggil = $this->uri->segment('3');
        
        //pengecekan adanya segmen ke-3 URI, jika tidak ada lempar ke ../Pustaka
        if(empty($nomor_panggil)){
            redirect(base_url('pustaka'));
        //jika ada, cek data POST
        }else{
            //mengambil jumlah pustaka dipinjam untuk memastikan jumlah pustaka tidak lebih kecil dari jumlah pustaka dipinjam
            $jumlah_dipinjam = $this->PustakaM->getDataPustaka($nomor_panggil)->jumlah_dipinjam;
            //pengecekan data post edit pustaka, jika ada lakukan edit
            if (!empty($this->input->post('submit'))){
                //konfigurasi validasi data masukan
                $config = array(
                    array(
                        'field' => 'nomor-panggil',
                        'label' => 'Nomor panggil',
                        'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                    array(
                        'field' => 'isbn',
                        'label' => 'ISBN',
                        'rules' => 'numeric',
                        'errors' => array('numeric' => '%s harus berupa angka')
                    ),
                    array(
                        'field' => 'kode-klasifikasi',
                        'label' => 'Kode klasifikasi',
                        'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                    array(
                        'field' => 'judul',
                        'label' => 'Judul',
                        'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                    array(
                        'field' => 'pengarang',
                        'label' => 'Pengarang',
                        //'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                    array(
                        'field' => 'penerbit',
                        'label' => 'Penerbit',
                        //'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                    array(
                        'field' => 'kota-terbit',
                        'label' => 'Penerbit',
                        //'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                    array(
                        'field' => 'tahun-terbit',
                        'label' => 'Tahun terbit',
                        'rules' => 'required|numeric|max_length[4]',
                        'errors' => array(
                            'required' => '%s tidak boleh kosong',
                            'numeric' => '%s harus berupa 4 digit angka',
                            'max_length' => '%s harus berupa 4 digit angka'
                        )
                    ),
                    array(
                        'field' => 'penerbit',
                        'label' => 'Penerbit',
                        //'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong')
                    ),
                    array(
                        'field' => 'jumlah',
                        'label' => 'Jumlah koleksi pustaka',
                        'rules' => 'required|numeric|greater_than_equal_to['.$jumlah_dipinjam.']',
                        'errors' => array(
                            'required' => '%s tidak boleh kosong',
                            'numeric' => '%s harus berupa angka',
                            'greater_than_equal_to' => "%s harus lebih dari 0 dan tidak boleh lebih sedikit dari jumlah koleksi yang sedang dipinjam"
                        )
                    ),
                );
                //validasi data masukan
                $this->form_validation->set_rules($config);
                
                //tampilkan info apabila error validasi
                if($this->form_validation->run() == FALSE){
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <b>Terjadi Kesalahan:</b>'.validation_errors().'
                        </div>');
                    redirect(current_url());
                    
                //lakukan memasukkan data ke dalam db
                }else{
                    //nomor_panggil dari POST nomor-panggil
                    //$nomor_panggil = $this->input->post('nomor-panggil');
                    
                    //konfigurasi nama file sampul
                    $nama_file = str_replace('.', '_', $nomor_panggil);
                    
                    //konfigurasi upload sampul
                    $upload_config = array(
                        'upload_path' => './assets/cover',
                        'allowed_types' => 'gif|jpg|jpeg|png|bmp',
                        'file_name' => $nama_file
                    );
                    
                    //upload sampul
                    $this->load->library('upload',$upload_config);
                    $upload_sampul = 'sampul';
                    
                    //jika tidak/gagal upload, tampilkan eksepsi error
                    if (!$this->upload->do_upload($upload_sampul)){
                        $error_upload = $this->upload->display_errors();
                        
                        //mengeset link upload sama dengan yang sudah ada
                        $link_upload = $this->PustakaM->getDataPustaka($nomor_panggil)->sampul;
                        
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-warning" role="alert">
                                Gagal upload sampul atau tidak memilih sampul baru. Gambar sampul tetap menggunakan gambar sampul lama.
                                <br>Data pustaka dengan nomor panggil:
                                <strong>'.$nomor_panggil.'</strong> berhasil diedit
                            </div>'
                        );
                    }else{
                        //mengambil link upload dari file sampul yang berhasil di-upload
                        $data['data_pustaka'] = $this->PustakaM->getDataPustaka($nomor_panggil);
                        $sampul_path = '/'.$data['data_pustaka']->sampul;
                        
                        //hapus sampul dari direktori
                        unlink('./'.$sampul_path);
                        $link_upload = 'assets/cover/'.$this->upload->data('file_name');
                        
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-success" role="alert">Data pustaka dengan nomor panggil: '
                            .$nomor_panggil.' berhasil diedit
                        </div>');
                    }
                    
                    //jika lolos validasi, data POST dimasukkan ke array untuk dimasukkan db
                    $data_pustaka = array(
                        'nomor_panggil' => $nomor_panggil,
                        'isbn' => $this->input->post('isbn'),
                        'kode_klasifikasi' => $this->input->post('kode-klasifikasi'),
                        'judul' => $this->input->post('judul'),
                        'pengarang' => $this->input->post('pengarang'),
                        'penerbit' => $this->input->post('penerbit'),
                        'kota_terbit' => $this->input->post('kota-terbit'),
                        'tahun_terbit' => $this->input->post('tahun-terbit'),
                        'sampul' => $link_upload,
                        'jumlah_pustaka' => $this->input->post('jumlah')
                    );
                    $result = $this->PustakaM->editPustaka($data_pustaka);
                    //jika berhasil memasukkan data ke dalam db
                    if($result=='0'){
                        redirect(base_url('pustaka/datapustaka/'.$nomor_panggil));
                        //gagal memasukkan data ke dalam db
                    }else{
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-danger" role="alert">
                                <b>Terjadi kesalahan dalam memasukkan perubahan data pustaka.</b>
                                , Kode: <strong>'.$result.'</strong>
                            </div>');
                        redirect(current_url());
                    }
                    
                }
                
            //jika tidak ada data POST, cukup tampilkan data pustaka berdasarkan no induk pada URI segmen 3
            }else{
                $data['data_pustaka'] = $this->PustakaM->getDataPustaka($nomor_panggil);
                
                //mengambil kategori dari data yang akan diedit berdasarkan kode klasifikasi
                if(isset($data['data_pustaka'])){
                    $kode_klasifikasi = $data['data_pustaka']->kode_klasifikasi;
                    $this->load->model('KategoriM');
                    $data['data_kategori'] = $this->KategoriM->getDataKategori($kode_klasifikasi);
                }
                
                //fetch daftar kategori
                $this->load->model('KategoriM');
                $data['daftar_kategori'] = $this->KategoriM->getDaftarKategori(null,null,'kode_klasifikasi','asc');
                
                
                if(empty($data['data_pustaka'])){
                    redirect(base_url('pustaka'));
                }else{
                    $this->load->view('head');
                    $this->load->view('FormEditPustaka',$data);
                    $this->load->view('foot');
                }
            }
        }
    }
    
    function hapusPustaka(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        }
        //ambil nomor panggil dari POST
        $nomor_panggil = $this->input->post('nomor-panggil');
        
        //cek jika ada pustaka dengan nomor panggil bersangkutan. jika ada, jangan hapus
        $this->load->model('PeminjamanM');
        $cek_peminjaman_by_nomor_panggil = $this->PeminjamanM->getJumlahPeminjaman(null,$nomor_panggil,null);
        if($cek_peminjaman_by_nomor_panggil > 0){
            $this->session->set_flashdata('message',
                '<div class="alert alert-danger" role="alert">
                    Gagal menghapus koleksi pustaka dengan nomor panggil <b>'.$nomor_panggil.'</b>. Terdapat peminjaman dengan data koleksi pustaka yang bersangkutan.
                </div>');
            redirect(base_url('pustaka'));
        }
        
        //delete di db
        $this->PustakaM->hapusPustaka($nomor_panggil);
        //kirim notif ke user
        $this->session->set_flashdata('message',
            '<div class="alert alert-success" role="alert">Data pustaka dengan nomor panggil <b>'
            .$nomor_panggil.'</b> berhasil dihapus
            </div>');
            redirect(base_url('pustaka'));
    }

    function testing(){
        
        $mpdf = new Mpdf();
        
        // Write some HTML code:
        
        $mpdf->WriteHTML('Hello World');
        
        // Output a PDF file directly to the browser
        $mpdf->Output('testing.pdf');
    }
}