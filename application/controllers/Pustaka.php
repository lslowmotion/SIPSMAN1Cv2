<?php
class Pustaka extends CI_Controller{
    public function __construct(){
        parent::__construct();
        //load model PustakaM
        $this->load->model('PustakaM');
    }
    
    function index(){
        //ambil kode kategori dari segmen 2 URI
        $kode = $this->uri->segment('3');
        $data['daftar_pustaka'] = $this->PustakaM->getDaftarPustakabyKategori($kode);
        if(empty($data['daftar_pustaka'])){
            //fetch daftar koleksi pustaka
            $data['daftar_pustaka'] = $this->PustakaM->getDaftarPustaka();
            $this->load->view('head');
            $this->load->view('DaftarPustaka',$data);
            $this->load->view('foot');
        }else{
            $this->load->view('head');
            $this->load->view('DaftarPustaka',$data);
            $this->load->view('foot');
        }        
    }
        
    /* function tambahPustaka(){
        if(empty($this->input->post('submit'))){
            //konfigurasi validasi data masukan
            $config = array(
                array(
                    'field' => '',
                )
            );
        }
    } */
}