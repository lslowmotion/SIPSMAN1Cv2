<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

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
	<div id="wrapper">
        <!-- Navigation -->
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo base_url();?>">SIPSMAN1C</a>
			</div>
        <!-- /.navbar-header -->
		<ul class="nav navbar-top-links navbar-right">
            <!-- /.dropdown -->
			<li class="dropdown">
			<?php if(!empty($this->session->userdata('id'))){?>
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<?php echo $this->session->userdata('id_name');?>
					<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
				</a>
				<ul class="dropdown-menu dropdown-user">
					<li><a href="#"><i class="fa fa-key fa-fw"></i> Ganti Password</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo base_url('Akun/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
				</ul>
			<?php }else{?>
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					Login
                    <i class="fa fa-sign-in fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
						
								
									<form class="col-md-12" method="post" action="<?php echo base_url('Akun/login');?>" >
										<div class="form-group">
											<label for="id">Username</label>
											<input class="form-control" placeholder="NIP/NIS"  name="id" type="text" />
										</div>
										<div class="form-group">
											<label for="password">Password</label>
											<input class="form-control" placeholder="password" name="password" type="password" />
										</div>
										<input type="hidden" name="submit" value=TRUE />
										<button type="submit" class="form-control btn-primary"><i class="fa fa-chevron-right "></i> Login</button>
									</form>
								
						
                    </ul>
				<?php }?>
            <!-- /.dropdown-user -->
			</li>
            <!-- /.dropdown -->
		</ul>
            <!-- /.navbar-top-links -->
			<div class="navbar-default sidebar" role="navigation">
				<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">
                        <li>
                            <a href="<?php echo base_url();?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <?php if(!empty($this->session->userdata('id'))){?>
                        <li>
                            <a href="#"><i class="fa fa-book fa-fw"></i> Koleksi Pustaka<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<?php if($this->session->userdata('level')=='admin'){ ?>
                                <li>
                                    <a href="#">Kelola Koleksi</a>
                                </li>
                                <?php }?>
                                <li>
                                    <a href="#">Lihat Koleksi</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-list fa-fw"></i> Peminjaman<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<?php if($this->session->userdata('level')=='admin'){ ?>
                                <li>
                                    <a href="#">Kelola Peminjaman</a>
                                </li>
                                <?php }?>
                                <li>
                                    <a href="#">Status Peminjaman</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                         <li>
                         	<?php if($this->session->userdata('level')=='admin'){ ?>
                            <a href="#"><i class="fa fa-book fa-fw"></i> Keanggotaan<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href="#">Kelola Anggota</a>
                                </li>
                            </ul>
                            <?php }?>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php }?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
</div>

<script src="<?php echo base_url('assets/js/jquery-3.1.1.min.js');?>"></script>