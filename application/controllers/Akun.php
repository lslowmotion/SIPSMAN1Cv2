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
           /* debugging gagal login 
            $this->load->view('dashboard');*/
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
                $login_s = array(
                    'id' => $this->input->post('id'),
                    'password' => $this->input->post('password')
                );
                //$this->load->model('AkunM');
                $login_r=$this->AkunM->login($login_s);
                if (!empty($login_r)){
                    foreach ($login_r as $row){
                        $id=$row->id;
                        $level=$row->level;
                    }
                    if ($level=='admin'){
                        $this->session->set_userdata('id',$id);
                        $this->session->set_userdata('id_name',$id);
                        $this->session->set_userdata('level',$level);
                        redirect(base_url());
                    }elseif ($level=='anggota'){
                        
                        /*debugging level anggota
                        $this->session->set_userdata('id',$id);
                        $this->session->set_userdata('id_name',$id);
                        $this->session->set_userdata('level',$level);
                        redirect(base_url());*/
                        
              
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
        $id = $this->input->post('id');
        $url=$this->input->post('url');
        $this->AkunM->resetPassword($id);
        $this->session->set_flashdata(
            'message',
            '<div class="alert alert-success" role="alert">
				Password dari: '.$id.' berhasil direset
			</div>'
        );
        redirect($url);
    }
}