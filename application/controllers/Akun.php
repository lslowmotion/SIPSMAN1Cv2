<?php
class Akun extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AkunM');
    }
    public function index()  {
        //lempar ke form login
        if (empty($this->session->userdata('id'))){
            $this->load->view('head');
            $this->load->view('FormLogin');
            $this->load->view('foot');
        }else{
            redirect(base_url());
        }
    }
    
    function login(){
        if(!empty($this->input->post('submit'))){
            $config = array(
                array(
                    'field' => 'id',
                    'label' => 'Username',
                    'rules' => 'required',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',),
                    ),
                array(
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong',),
                )
            );
            $this->form_validation->set_rules($config);
            
            if($this->form_validation->run() == FALSE){
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-danger" role="alert">'
                    .validation_errors().
                    '</div>'
                    );
                redirect(base_url('akun'));
            }else{
                $login_send = array(
                    'id' => $this->input->post('id'),
                    'password' => $this->input->post('password')
                );
                $login_receive=$this->AkunM->login($login_send);
                if (!empty($login_receive)){
                    foreach ($login_receive as $row){
                        $id=$row->id;
                        $level=$row->level;
                    }
                    if ($level=='admin'){
                        $this->session->set_userdata('id',$id);
                        $this->session->set_userdata('id_name',$id);
                        $this->session->set_userdata('level',$level);
                        redirect(base_url());
                    }elseif ($level=='anggota'){
                        $this->load->model('AnggotaM');
                        $name=$this->AnggotaM->login($id);
                        foreach ($name as $row){
                            $name=$row->nama;
                        }
                        $this->session->set_userdata('id',$id);
                        $this->session->set_userdata('id_name',$name);
                        $this->session->set_userdata('level',$level);
                        redirect(base_url());
                        
                    }
                }else{
                    $this->session->set_flashdata(
                        'message','
                        <div class="alert alert-danger" role="alert">
					       Login gagal! silakan cek kembali nis/nip dan password, atau hubungi petugas.
						</div>'
                    );
                    redirect(base_url('akun'));
                }
            }
        }else{
            redirect(base_url('akun'));
        }
    }
    
    function logout(){
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('id_name');
        $this->session->unset_userdata('level');
        $this->session->set_flashdata(
            'message','
            <div class="alert alert-success" role="alert">
				Logout berhasil
			</div>'
            );
        redirect(base_url());
    }
    
    function resetPassword(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        }
        $id = $this->input->post('id');
        $url = $this->input->post('url');
        $this->AkunM->resetPassword($id);
        $this->session->set_flashdata(
            'message',
            '<div class="alert alert-success" role="alert">
				Password dari no induk '.$id.' berhasil direset
			</div>'
        );
        redirect(base_url('anggota'));
    }
    
    function editPassword(){
        //cek otoritas
        if($this->session->userdata('level') != 'admin'){
            redirect(base_url('akun'));
        }
        
        //jika POST kosong, tampilkan form edit password
        if(empty($this->input->post('submit'))){
            $this->load->view('head');
            $this->load->view('FormEditPassword');
            $this->load->view('foot');
            
        //jika ada data POST, proses edit password
        }else{
            //konfigurasi validasi data masukan
            $config = array(
                array(
                    'field' => 'password-lama',
                    'label' => 'Password lama',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong')
                ),
                array(
                    'field' => 'password-baru',
                    'label' => 'Password baru',
                    'rules' => 'required',
                    'errors' => array('required' => '%s tidak boleh kosong'),
                ),
                array(
                    'field' => 'konfirmasi-password-baru',
                    'label' => 'Konfirmasi password baru',
                    'rules' => 'required|matches[password-baru]',
                    'errors' => array(
                        'required' => '%s tidak boleh kosong',
                        'matches' => 'Konfirmasi password baru tidak sama'
                    )
                )
            );
            $this->form_validation->set_rules($config);
            //validasi
            if($this->form_validation->run() == FALSE){
                $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">'
                    .validation_errors().
                    '</div>');
                    redirect(base_url('akun/editpassword'));
            }else{
                //autentikasi user dan password lama
                $auth_send = array(
                    'id' => $this->session->userdata('id'),
                    'password' => $this->input->post('password-lama')
                );
                $auth_receive = $this->AkunM->login($auth_send);
                //autentikasi berhasil dan mengubah password
                if(!empty($auth_receive)){
                    $auth_baru = array(
                        'id' => $this->session->userdata('id'),
                        'password' => $this->input->post('password-baru')
                    );
                    $this->AkunM->editPassword($auth_baru);
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success" role="alert">
                            Password berhasil diubah
                        </div>'
                    );
                    redirect(base_url('akun/editpassword'));
                //autentikasi gagal
                }else{
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                            Password lama salah
                        </div>'
                    );
                    redirect(base_url('akun/editpassword'));
                }
            }
        }
    }
}