<?php
class main extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        if($this->session->userdata('level') =='admin'){
            redirect(base_url('kunjungan'));
        }
        $this->load->view('head');
        $this->load->view('Dashboard');
        $this->load->view('foot');
    }
}