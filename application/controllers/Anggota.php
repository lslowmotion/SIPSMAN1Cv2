<?php
class Anggota extends CI_Controller{
    public function __construct(){
        parent::__construct();
        if(!$this->session->userdata('id')){
            redirect(base_url());
        }
        $this->load->model('AnggotaM');
    }
    
    function index(){
        //cek otoritas
        if($this->session->userdata('level')!='admin'){
            redirect(base_url());
        }
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
        //cek otoritas
        if($this->session->userdata('level')!='admin'){
            redirect(base_url());
        }
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
        //cek otoritas
        if($this->session->userdata('level')!='admin'){
            redirect(base_url());
        }
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
                
                //insert array ke db, $result menerima kode eksepsi
                $result = $this->AnggotaM->tambahAnggota($data_anggota);
                if($result=='0'){
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success" role="alert">Anggota dengan identitas:
                        <br>Nama: <b>'
                            .$nama.
                        '</b><br>No induk: <b>'
                            .$no_induk.
                        '</b><br>telah ditambahkan </div>');
                    redirect(base_url('Anggota/tambahAnggota'));
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
    
}