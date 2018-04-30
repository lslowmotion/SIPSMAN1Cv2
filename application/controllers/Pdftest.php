<?php
use Mpdf\Mpdf;

class Pdftest extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
    }
    
    function index(){
        
        $mpdf = new Mpdf();
        
        // Write some HTML code:
        
        $mpdf->WriteHTML('Hello World');
        
        // Output a PDF file directly to the browser
        $mpdf->Output();
    }
}
