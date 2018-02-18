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
}