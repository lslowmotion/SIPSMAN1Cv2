<?php
class Pustaka extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //load model PustakaM
        $this->load->model('PustakaM');
    }
    
    function index(){
        //fetch daftar pustaka
        $data['daftar_pustaka'] = $this->PustakaM->getDaftarPustaka();
        $this->load->view('head');
        $this->load->view('DaftarPustaka',$data);
        $this->load->view('foot');
    }
    
    
}