<?php
class Kategori extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //load model KategoriM
        $this->load->model('KategoriM');
    }
       
    function index(){
        //tampilkan daftar kategori
        $this->load->view('head');
        $this->load->view('DaftarKategori');
        $this->load->view('foot');
    }
    
    function daftarKategori(){
        //kolom untuk menentukan kolom db yang akan diurutkan dari POST DataTables
        $kolom = array(
            0 => 'kode_klasifikasi',
            1 => 'nama_kategori'
        );
        
        //mengambil POST dari DataTables untuk kemudian dilempar ke db untuk fetch
        $panjang_data = $this->input->post('length'); //jumlah data difetch
        $mulai_data = $this->input->post('start'); //data mulai fetch dari data ke-sekian
        $kolom_urut = $kolom[$this->input->post('order')[0]['column']]; //kolom yang diurutkan
        $urutan = $this->input->post('order')[0]['dir']; //urutan (ascending/descending)
        
        //mencari jumlah data kategori
        $total_data = $this->KategoriM->getJumlahKategori();
        
        //memasukkan total data ke data terfilter sebagai inisialisasi
        $total_data_terfilter = $total_data;
        
        //apabila search POST dari DataTables kosong, ambil daftar kategori  berdasarkan jumlah data, mulai fetch, kolom terurut, dan urutan
        if(empty($this->input->post('search')['value'])){
            $data_kategori = $this->KategoriM->getDaftarKategori($panjang_data,$mulai_data,$kolom_urut,$urutan);
            //apabila search POST dari DataTables isi, ambil data kategori by search
        }else{
            //search dari POST
            $search = $this->input->post('search')['value'];
            
            //mengambil jumlah data terfilter dari search di db
            $total_data_terfilter = $this->KategoriM->getDaftarKategoribySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan)['jumlah'];
            
            //apabila jumlah data terfilter lebih dari 0, isi $data_kategori dengan data hasil search
            if($total_data_terfilter > 0){
                $data_kategori = $this->KategoriM->getDaftarKategoribySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan)['data'];
                //jika tidak ditemukan, kosongi $data_kategori
            }else{
                $data_kategori = null;
            }
        }
        //jika admin, tampilkan tombol delete
        if($this->session->userdata('level') == 'admin'){
            //jika $data_kategori tidak kosong, masukkan data yang akan di-parse ke DataTables dalam $data
            if(!empty($data_kategori)){
                foreach ($data_kategori as $row){
                    $data[] = array(
                        $row->kode_klasifikasi,
                        $row->nama_kategori,
                        '<a href="'.base_url('pustaka/index/'.$row->kode_klasifikasi).'"><button type="button" class="btn btn-info"><i class="fa fa-search"></i> Cari Koleksi</button></a>
                        <a href="'.base_url('kategori/editkategori/'.$row->kode_klasifikasi).'">
        					<button class="form-control btn-warning">
        						<i class="fa fa-edit"></i> Edit Data
        					</button>
        				</a>'
                        
                    );
                }
                //jika kosong, kosongi $data
            }else{
                $data = array();
            }
        //jika tidak admin, cukup tampilkan tombol detail
        }else{
            //jika $data_kategori tidak kosong, masukkan data yang akan di-parse ke DataTables dalam $data
            if(!empty($data_kategori)){
                foreach ($data_kategori as $row){
                    $data[] = array(
                        $row->kode_klasifikasi,
                        $row->nama_kategori,
                        '<a href="'.base_url('pustaka/index/'.$row->kode_klasifikasi).'"><button type="button" class="btn btn-info"><i class="fa fa-search"></i> Cari Koleksi</button></a>'
                        
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
    
    function tambahKategori(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        }
        if(!empty($this->input->post('submit'))){
            //konfigurasi validasi data masukan
            $config = array(
                array(
                    'field' => 'kode-klasifikasi',
                    'label' => 'Kode klasifikasi',
                    'rules' => 'required|regex_match[/^[0-9.]+$/]',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'regex_match' => '%s harus berupa angka atau titik (.)'
                    )
                ),
                array(
                    'field' => 'nama-kategori',
                    'label' => 'Nama kategori',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong'
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
                    redirect(base_url('kategori/tambahkategori'));
            }else{
                //jika lolos validasi, data POST dimasukkan ke array untuk dimasukkan db
                $data_kategori = array(
                    'kode_klasifikasi' => $this->input->post('kode-klasifikasi'),
                    'nama_kategori' => $this->input->post('nama-kategori')
                );
                //insert array ke db, $result menerima kode eksepsi
                $result = $this->KategoriM->tambahKategori($data_kategori);
                
                //berhasil memasukkan data
                if($result == '0'){
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success" role="alert">Kategori dengan data:
                        <br>Kode Klasifikasi: <b>'
                        .$data_kategori['kode_klasifikasi'].
                        '</b><br>Nama Kategori: <b>'
                        .$data_kategori['nama_kategori'].
                        '</b><br>telah ditambahkan </div>');
                        redirect(base_url('kategori/tambahkategori'));
                        //gagal memasukkan data
                }else{
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <strong>Terjadi kesalahan</strong>, silahkan masukan <strong>kode klasifikasi</strong> yang belum digunakan.
                        </div>');
                    redirect(base_url('kategori/tambahkategori'));
                }
            }
        }else{
            $this->load->view('head');
            $this->load->view('FormTambahKategori');
            $this->load->view('foot');
        }
    }
    
    function editKategori(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        }
        //ambil no induk dari segmen ke-3 URI
        $kode_klasifikasi = $this->uri->segment('3');
        
        //pengecekan adanya segmen ke-3 URI, jika tidak ada lempar ke ../Kategori
        if(empty($kode_klasifikasi)){
            redirect(base_url('kategori'));
            //jika ada, cek data POST
        }else{
            
            //pengecekan data post edit kategori, jika ada lakukan edit
            if (!empty($this->input->post('submit'))){
                //konfigurasi validasi data masukan
                $config = array(
                    array(
                        'field' => 'nama-kategori',
                        'label' => 'Nama kategori',
                        'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong'),
                    ),
                    array(
                        'field' => 'kode-klasifikasi',
                        'label' => 'Kode klasifikasi',
                        'rules' => 'required|numeric',
                        'errors' => array(
                            'required' => '%s tidak boleh kosong',
                            'numeric' => '%s harus berupa angka'
                        ),
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
                    redirect(current_url());
                    
                    //lakukan memasukkan data ke dalam db
                }else{
                    $kode_klasifikasi = $this->input->post('kode-klasifikasi');
                    $data_kategori = array(
                        'kode_klasifikasi' => $this->input->post('kode-klasifikasi'),
                        'nama_kategori' => $this->input->post('nama-kategori'),
                    );
                    $result = $this->KategoriM->editKategori($data_kategori);
                    
                    //jika berhasil memasukkan data ke dalam db
                    if($result=='0'){
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-success" role="alert">Data kategori dengan kode klasifikasi: '
                                .$kode_klasifikasi.' telah diedit
                            </div>');
                        redirect(base_url('kategori'));
                    //gagal memasukkan data ke dalam db
                    }else{
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-danger" role="alert">
                                <b>Terjadi kesalahan dalam memasukkan data perubahan kategori</b>
                                , Kode: <strong>'.$result.'</strong>
                            </div>');
                        redirect(base_url('kategori'));
                    }
                }
                
                //jika tidak ada data POST, cukup tampilkan data kategori berdasarkan kode klasifikasi pada URI segmen 3
            }else{
                $data['data_kategori'] = $this->KategoriM->getDataKategori($kode_klasifikasi);
                if(empty($data['data_kategori'])){
                    redirect(base_url('kategori'));
                }else{
                    $this->load->view('head');
                    $this->load->view('FormEditKategori',$data);
                    $this->load->view('foot');
                }
            }
        }
    }
    
    function hapusKategori(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        }
        //ambil nomor panggil dari POST
        $kode_klasifikasi = $this->input->post('kode-klasifikasi');
        
        //fetch data pustaka dengan kategori terkait
        $this->load->model('PustakaM');
        $jumlah_pustaka = $this->PustakaM->getJumlahPustaka($kode_klasifikasi);
        
        //jika ada daftar pustaka dengan kategori terkait, jangan hapus dan beri notif ke pengguna
        if($jumlah_pustaka > 0){
            $this->session->set_flashdata('message',
                '<div class="alert alert-danger" role="alert">Terdapat koleksi dengan kode klasifikasi '
                .$kode_klasifikasi.'
            </div>');
                redirect(base_url('kategori'));
        }else{
        
            $this->KategoriM->hapusKategori($kode_klasifikasi);
            $this->session->set_flashdata('message',
                '<div class="alert alert-success" role="alert">Data kategori dengan kode klasifikasi '
                .$kode_klasifikasi.' telah dihapus
                </div>');
                redirect(base_url('kategori'));
        }
    }
}