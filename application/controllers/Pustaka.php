<?php
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

        //jika admin, tampilkan tombol delete
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
                        
                        $row->jumlah - $row->dipinjam.' eksemplar',
                        
                        '<a href="'.base_url('pustaka/datapustaka/'.$row->nomor_panggil).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Detail</button></a>'
                        
                    );
                }
                //jika kosong, kosongi $data
            }else{
                $data = array();
            }
        //jika tidak admin, cukup tampilkan tombol detail
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
                        
                        $row->jumlah - $row->dipinjam.' eksemplar',
                        
                        '<a href="'.base_url('pustaka/datapustaka/'.$row->nomor_panggil).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Detail</button></a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapusModal" data-nomor-panggil="'.$row->nomor_panggil.'" data-judul="'.$row->judul.'" data-url="'.current_url().'"><i class="fa fa-trash"></i> Hapus</button>'
                        
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
                /*array(
                    'field' => 'sampul',
                    'label' => 'Sampul',
                    //'rules' => 'required',
                    'errors' => array('required' => 'Harus memilih file %s untuk di-upload')
                ),*/
                array(
                    'field' => 'jumlah',
                    'label' => 'Jumlah',
                    'rules' => 'required|numeric|greater_than_equal_to[1]',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka',
                        'greater_than_equal_to' => "%s harus lebih dari 0"
                    )
                ),
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
                $cek_judul = $this->PustakaM->getJudulbyNomorPanggil($nomor_panggil);
                while(!empty($cek_judul)){
                    $eksemplar++;
                    $nomor_panggil = $this->input->post('nomor-panggil').'.'.$eksemplar;
                    $nama_file = $this->input->post('nomor-panggil').'_'.$eksemplar;
                    $cek_judul = $this->PustakaM->getJudulbyNomorPanggil($nomor_panggil);
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
                        'jumlah' => $this->input->post('jumlah')
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
                            .$data_pustaka['jumlah'].
                            '</b><br>telah ditambahkan
                        </div>'
                            );
                        redirect(base_url('pustaka/tambahpustaka'));
                        //gagal memasukkan data
                    }else{
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-danger" role="alert">
                            <strong>Terjadi kesalahan.</strong>
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
    
    function hapusPustaka(){
        //ambil nomor panggil dari POST
        $nomor_panggil = $this->input->post('nomor-panggil');
        //delete di db
        $this->PustakaM->hapusPustaka($nomor_panggil);
        //kirim notif ke user
        $this->session->set_flashdata('message',
            '<div class="alert alert-success" role="alert">Pustaka dengan nomor panggil '
            .$nomor_panggil.' telah dihapus
            </div>');
            redirect(base_url('pustaka'));
    }

}