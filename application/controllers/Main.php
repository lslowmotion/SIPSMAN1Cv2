<?php
class main extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        $this->load->view('head');
        $this->load->view('dashboard');
        $this->load->view('foot');
    }
}