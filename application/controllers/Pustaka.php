<?php
class Pustaka extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //load model PustakaM
        $this->load->model('PustakaM');
    }
    
    function index(){
        //ambil kode kategori dari segmen 2 URI
        $kode = $this->uri->segment('3');
        
        if(empty($kode)){
            //fetch daftar koleksi pustaka
            $data['daftar_pustaka'] = $this->PustakaM->getDaftarPustaka();
            $this->load->view('head');
            $this->load->view('DaftarPustaka',$data);
            $this->load->view('foot');
        }else{
            $data['daftar_pustaka'] = $this->PustakaM->getDaftarPustakabyKategori($kode);
            $this->load->view('head');
            $this->load->view('DaftarPustaka',$data);
            $this->load->view('foot');
        }        
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
            $data['daftar_kategori'] = $this->KategoriM->getDaftarKategori();
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