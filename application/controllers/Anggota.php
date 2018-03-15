<?php
class Anggota extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        } 
        //load model AnggotaM
        $this->load->model('AnggotaM');
    }
    
    function index(){
        $this->load->view('head');
        $this->load->view('DaftarAnggota');
        $this->load->view('foot');
    }
    
    function daftarAnggota(){
        //kolom untuk menentukan kolom db yang akan diurutkan dari POST DataTables
        $kolom = array(
            0 => 'no_induk',
            1 => 'nama',
            2 => 'alamat',
        );
         
        //mengambil POST dari DataTables untuk kemudian dilempar ke db untuk fetch
        $panjang_data = $this->input->post('length'); //jumlah data difetch
        $mulai_data = $this->input->post('start'); //data mulai fetch dari data ke-sekian
        $kolom_urut = $kolom[$this->input->post('order')[0]['column']]; //kolom yang diurutkan
        $urutan = $this->input->post('order')[0]['dir']; //urutan (ascending/descending)
        
        //mencari jumlah data anggota       
        $total_data = $this->AnggotaM->getJumlahAnggota();
        
        //memasukkan total data ke data terfilter sebagai inisialisasi
        $total_data_terfilter = $total_data;
        
        //apabila search POST dari DataTables kosong, ambil daftar anggota berdasarkan jumlah data, mulai fetch, kolom terurut, dan urutan      
        if(empty($this->input->post('search')['value'])){
            $data_anggota = $this->AnggotaM->getDaftarAnggota($panjang_data,$mulai_data,$kolom_urut,$urutan);
        //apabila search POST dari DataTables isi, ambil data anggota by search
        }else{
            //search dari POST
            $search = $this->input->post('search')['value'];
            
            //mengambil jumlah data terfilter dari search di db
            $total_data_terfilter = $this->AnggotaM->getDaftarAnggotabySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan)['jumlah'];
            
            //apabila jumlah data terfilter lebih dari 0, isi $data_anggota dengan data hasil search
            if($total_data_terfilter > 0){
                $data_anggota = $this->AnggotaM->getDaftarAnggotabySearch($panjang_data,$mulai_data,$search,$kolom_urut,$urutan)['data'];
            //jika tidak ditemukan, kosongi $data_anggota    
            }else{
                $data_anggota = null;
            }
        }
        
        //jika $data_anggota tidak kosong, masukkan data yang akan di-parse ke DataTables dalam $data
        if(!empty($data_anggota)){
            foreach ($data_anggota as $row){
                $data[] = array(
                    $row->no_induk,
                    $row->nama,
                    $row->alamat,
                    '<a href="'.base_url('anggota/dataanggota/'.$row->no_induk).'"><button type="button" class="btn btn-primary"><i class="fa fa-list"></i> Detail</button></a>
                    
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapusModal" data-no-induk="'.$row->no_induk.'" data-nama="'.$row->nama.'"><i class="fa fa-trash"></i> Hapus</button>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#resetModal" data-no-induk="'.$row->no_induk.'" data-nama="'.$row->nama.'"><i class="fa fa-refresh"></i> Reset Password</button>'
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
    
    function dataAnggota(){
        //ambil no induk dari segmen ke 3 URI
        $no_induk = $this->uri->segment('3');
        //fetch data anggota
        $data['data_anggota'] = $this->AnggotaM->getDataAnggota($no_induk);
        //tampilkan data anggota
        if(empty($data['data_anggota'])){
            redirect(base_url('anggota'));
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
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'no-induk',
                    'label' => 'No induk',
                    'rules' => 'required|numeric|max_length[18]',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka',
                        'max_length' => '%s tidak boleh lebih dari 18 karakter'
                    )
                ),
                array(
                    'field' => 'alamat',
                    'label' => 'Alamat',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'no-telepon',
                    'label' => 'No telepon',
                    'rules' => 'required|numeric',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'numeric' => '%s harus berupa angka'
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
                redirect(base_url('anggota/tambahanggota'));
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
                if($result == '0'){
                    $this->load->model('AkunM');
                    $this->AkunM->tambahAkun($data_akun);
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success" role="alert">Anggota dengan identitas:
                        <br>Nama: <b>'
                            .$nama.
                        '</b><br>No induk: <b>'
                            .$no_induk.
                        '</b><br>berhasil ditambahkan </div>');
                    redirect(base_url('anggota/tambahanggota'));
                //gagal memasukkan data
                }else{
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            <strong>Terjadi kesalahan</strong>, silahkan masukan <strong>no induk</strong> yang belum digunakan.
                        </div>');
                    redirect(base_url('anggota/tambahanggota'));
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
            redirect(base_url('anggota'));
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
                            <b>Terjadi Kesalahan:</b><br>'.validation_errors().'
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
                            '<div class="alert alert-success" role="alert">Data anggota dengan no induk: '
                                .$no_induk.' berhasil diedit 
                            </div>');
                        redirect(base_url('anggota/dataanggota/'.$no_induk));
                    //gagal memasukkan data ke dalam db
                    }else{
                        $this->session->set_flashdata('message',
                            '<div class="alert alert-danger" role="alert">
                                <b>Terjadi kesalahan dalam memasukkan perubahan data anggota.</b>
                                , Kode : <strong>'.$result.'</strong>
                            </div>');
                        redirect(current_url());
                    }
                }
                
            //jika tidak ada data POST, cukup tampilkan data anggota berdasarkan no induk pada URI segmen 3
            }else{
                $data['data_anggota'] = $this->AnggotaM->getDataAnggota($no_induk);
                if(empty($data['data_anggota'])){
                    redirect(base_url('anggota'));
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
        $this->AnggotaM->hapusAnggota($no_induk);
        $this->load->model('AkunM');
        $this->AkunM->hapusAkun($no_induk);
        $this->session->set_flashdata('message',
            '<div class="alert alert-success" role="alert">Data anggota dengan no induk '
                .$no_induk.' berhasil dihapus
            </div>');
        redirect(base_url('anggota'));
    }
}