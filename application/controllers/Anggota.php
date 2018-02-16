<?php
class Anggota extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url());
        }
        //load model AnggotaM
        $this->load->model('AnggotaM');
    }
    
    function index(){
        //fetch daftar anggota
        $data['daftar_anggota'] = $this->AnggotaM->getDaftarAnggota();
        //tampilkan daftar anggota
        $this->load->view('head');
        $this->load->view('DaftarAnggota',$data);
        $this->load->view('foot');
        /* $this->load->view('head');
        $this->load->view('Anggota');
        $this->load->view('foot'); */
    }
    
    function dataAnggota(){
        //ambil no induk dari segmen ke 3 URI
        $no_induk = $this->uri->segment('3');
        //fetch data anggota
        $data['data_anggota'] = $this->AnggotaM->getDataAnggota($no_induk);
        //tampilkan data anggota
        if(empty($data['data_anggota'])){
            redirect(base_url('Anggota'));
        }else{
            $this->load->view('head');
            $this->load->view('DataAnggota',$data);
            $this->load->view('foot');
        }
    }
    
    function tambahAnggota(){
        if(!empty($this->input->post('submit'))){
            //konfigurasi validasi data masukan
            $config = array(
                array(
                    'field' => 'nama',
                    'label' => 'Nama',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong'),
                ),
                array(
                    'field' => 'no-induk',
                    'label' => 'No Induk',
                    'rules' => 'required|numeric',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka'
                    ),
                ),
                array(
                    'field' => 'alamat',
                    'label' => 'Alamat',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong'),
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong'),
                ),
                array(
                    'field' => 'no-telepon',
                    'label' => 'No Telepon',
                    'rules' => 'required|numeric',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka'
                    ),
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
                redirect(base_url('Anggota/tambahAnggota'));
            }else{
                $no_induk = $this->input->post('no-induk');
                $nama = $this->input->post('nama');
                //jika lolos validasi, data POST dimasukkan ke array untuk dimasukkan db   
                $data_anggota = array(
                    'no_induk' => $this->input->post('no-induk'),
                    'nama' => $this->input->post('nama'),
                    'alamat' => $this->input->post('alamat'),
                    'email' => $this->input->post('email'),
                    'telepon' => $this->input->post('no-telepon')
                );
                $data_akun = array(
                    'id' => $no_induk,
                    'password' => $no_induk,
                    'level' => 'anggota'
                );
                //insert array ke db, $result menerima kode eksepsi
                $result = $this->AnggotaM->tambahAnggota($data_anggota);
                
                //berhasil memasukkan data
                if($result=='0'){
                    $this->load->model('AkunM');
                    $this->AkunM->tambahAkun($data_akun);
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success" role="alert">Anggota dengan identitas:
                        <br>Nama: <b>'
                            .$nama.
                        '</b><br>No induk: <b>'
                            .$no_induk.
                        '</b><br>telah ditambahkan </div>');
                    redirect(base_url('Anggota/tambahAnggota'));
                //gagal memasukkan data
                }else{
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <strong>Terjadi kesalahan</strong>, silahkan masukan <strong>no induk</strong> yang belum digunakan.
                        </div>');
                    redirect(base_url('Anggota/tambahAnggota'));
                }
            }
            
        //jika tidak submit, cukup tampilkan
        }else{
             $this->load->view('head');
             $this->load->view('FormTambahAnggota');
             $this->load->view('foot');
        }
    }
    
    function editAnggota(){
        //ambil no induk dari segmen ke-3 URI
        $no_induk = $this->uri->segment('3');
        
        //pengecekan adanya segmen ke-3 URI, jika tidak ada lempar ke ../Anggota
        if(empty($no_induk)){
            redirect(base_url('Anggota'));
        //jika ada, cek data POST
        }else{
            
            //pengecekan data post edit anggota, jika ada lakukan edit
            if (!empty($this->input->post('submit'))){
                //konfigurasi validasi data masukan
                $config = array(
                    array(
                        'field' => 'nama',
                        'label' => 'Nama',
                        'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong'),
                    ),
                    array(
                        'field' => 'no-induk',
                        'label' => 'No Induk',
                        'rules' => 'required|numeric',
                        'errors' => array(
                            'required' => '%s tidak boleh kosong',
                            'numeric' => '%s harus berupa angka'
                        ),
                    ),
                    array(
                        'field' => 'alamat',
                        'label' => 'Alamat',
                        'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong'),
                    ),
                    array(
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'required',
                        'errors' => array('required' => '%s tidak boleh kosong'),
                    ),
                    array(
                        'field' => 'no-telepon',
                        'label' => 'No Telepon',
                        'rules' => 'required|numeric',
                        'errors' => array(
                            'required' => '%s tidak boleh kosong',
                            'numeric' => '%s harus berupa angka'
                        ),
                    ),
                );
                //validasi data masukan
                $this->form_validation->set_rules($config);
                
                //tampilkan info apabila error validasi
                if($this->form_validation->run() == FALSE){
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <b>Terjadi Kesalahan :</b>'.validation_errors().'
                        </div>');
                    redirect(current_url());
                    
                //lakukan memasukkan data ke dalam db
                }else{
                    $no_induk = $this->input->post('no-induk');
                    $data_anggota = array(
                        'no_induk' => $this->input->post('no-induk'),
                        'nama' => $this->input->post('nama'),
                        'alamat' => $this->input->post('alamat'),
                        'email' => $this->input->post('email'),
                        'telepon' => $this->input->post('no-telepon')
                    );
                    $result = $this->AnggotaM->editAnggota($data_anggota);
                    
                    //jika berhasil memasukkan data ke dalam db
                    if($result=='0'){
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-success" role="alert">'
                                .$no_induk.' Telah diedit 
                            </div>');
                        redirect(base_url('Anggota/dataAnggota/'.$no_induk));
                    //gagal memasukkan data ke dalam db
                    }else{
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-danger" role="alert">
                                <b>Terjadi kesalahan</b>
                                , Kode : <strong>'.$result.'</strong>
                            </div>');
                        redirect(current_url());
                    }
                }
                
            //jika tidak ada data POST, cukup tampilkan data anggota berdasarkan no induk pada URI segmen 3
            }else{
                $data['data_anggota'] = $this->AnggotaM->getDataAnggota($no_induk);
                if(empty($data['data_anggota'])){
                    redirect(base_url('Anggota/daftarAnggota'));
                }else{
                    $this->load->view('head');
                    $this->load->view('FormEditAnggota',$data);
                    $this->load->view('foot');
                }
            }
        }
    }
    
    function hapusAnggota(){
        $no_induk = $this->input->post('no-induk');
        $this->load->model('AkunM');
        $this->AkunM->hapusAkun($no_induk);
        $this->AnggotaM->hapusAnggota($no_induk);
        $this->session->set_flashdata('message',
            '<div class="alert alert-success" role="alert">Data dengan no induk = '
                .$no_induk.' telah dihapus
            </div>');
        redirect(base_url('Anggota'));
    }
    
   /*  function debugHapusAnggota(){
        $this->load->model('AkunM');
        $this->AkunM->hapusAkun('10898');
        $this->AnggotaM->hapusAnggota('10898');
    } */
}