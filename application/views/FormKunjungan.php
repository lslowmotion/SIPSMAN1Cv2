<!DOCTYPE html>
<html lang="en">

    <head>
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Sistem Informasi Perpustakaan SMA N 1 Cilacap">
        <meta name="author" content="Laatansa">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/ico/sipsman1c.ico');?>" />
    
        <title>SIPSMAN1C - Sistem Informasi Perpustakaan SMA N 1 Cilacap</title>
    
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">
    
        <!-- MetisMenu CSS -->
        <link href="<?php echo base_url('assets/css/metisMenu.min.css');?>" rel="stylesheet">
    
        <!-- Custom CSS -->
        <link href="<?php echo base_url('assets/css/sb-admin-2.css');?>" rel="stylesheet">
    
        <!-- Morris Charts CSS -->
        <link href="<?php echo base_url('assets/css/morris.css');?>" rel="stylesheet">
    
        <!-- Custom Fonts -->
        <link href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">
    
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    
    </head>

    <script src="<?php echo base_url('assets/js/jquery-3.1.1.min.js');?>"></script>
    <link href="<?php echo base_url('assets/DataTables-1.10.12/css/dataTables.bootstrap.min.css')?>" rel="stylesheet"/>
    
    <body>
	<!-- Form kunjungan -->
    	
    		<div class="jumbotron vertical-center">
    			<?php 
    				if($this->session->flashdata('message')){
    					echo $this->session->flashdata('message');
    				}
    			?>
    			
        		<div class="container-fluid">
        			<div class="col-md-4 col-md-offset-4">
        				<div class="panel panel-info" >
            				<div class="panel-heading text-center">Selamat datang di Perpustakaan SMA N 1 Cilacap.<br>
            				Silahkan masukkan nomor induk.</div>
            				<div class="panel-body">
            					<form method="post" action="<?php echo base_url('kunjungan/tambahkunjungan');?>" >
            						<div class="form-group">
            							<label class="control-label" for="no-induk">No Induk:</label>
            							<input class="form-control" placeholder="NIP/NIS"  name="no-induk" type="text" />
            						</div>
            						<div class="form-group">
            							<input type=hidden name="submit" value=TRUE>
            							<button type="submit" id="submit" class="form-control btn-primary">Masuk</button>
            						</div>
            					</form>
            				</div>
        				</div>
        			</div>
        		</div>
    		</div>
    	
    <!-- Formkunjungan -->
    </body>
	

	<!-- jQuery -->
	
    <!-- <script src="<?php echo base_url('assets/js/jquery-3.1.1.min.js');?>"></script> -->	
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url('assets/js/metisMenu.min.js');?>"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo base_url('assets/js/raphael.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/morris.min.js');?>"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url('assets/js/sb-admin-2.js');?>"></script>

</html>