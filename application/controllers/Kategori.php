<?php
class Kategori extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //load model KategoriM
        $this->load->model('KategoriM');
    }
    
    public function index(){
        //fetch daftar kategori
        $data['daftar_kategori'] =$this->KategoriM->getDaftarKategori();
        //tampilkan daftar kategori
        $this->load->view('head');
        $this->load->view('DaftarKategori',$data);
        $this->load->view('foot');
    }
    
    function tambahKategori(){
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
}