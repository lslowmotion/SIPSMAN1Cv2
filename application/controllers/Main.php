<?php
class main extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        if(!empty($this->session->userdata('id'))){
            redirect(base_url('kunjungan'));
        }
        $this->load->view('head');
        $this->load->view('DaftarPustaka');
        $this->load->view('foot');
    }
}