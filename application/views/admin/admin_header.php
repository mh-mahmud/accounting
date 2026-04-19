<!DOCTYPE html>
<html class="no-js">
    <!-- global declaration -->
    <?php $url_prefix = $this->webspice->settings()->site_url_prefix; ?>
    <head>
        <title>Base4 Accounting System</title>
        <!-- Bootstrap -->
        <link href="<?php echo $url_prefix; ?>global/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $url_prefix; ?>global/admin/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo $url_prefix; ?>global/admin/vendors/easypiechart/jquery.easy-pie-chart.css" rel="stylesheet" media="screen">
        <link href="<?php echo $url_prefix; ?>global/admin/assets/styles.css" rel="stylesheet" media="screen">
        <link href="<?php echo $url_prefix; ?>global/admin/assets/DT_bootstrap.css" rel="stylesheet" media="screen">
		
		<!-- Choosen_CSS -->
		
		<link href="<?php echo $url_prefix; ?>global/admin/assets/chosen.css" rel="stylesheet" media="screen">
		
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

		
		
        <script src="<?php echo $url_prefix; ?>global/admin/vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>

    <body>


        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Admin Panel</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-user"></i> <?php echo ucwords($this->webspice->admin_user_name($this->webspice->encrypt_decrypt($_SESSION['user']['USER_ID'], 'decrypt'))); ?> <i class="caret"></i>

                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a tabindex="-1" href="<?php echo $url_prefix; ?>logout">Logout</a>
                                        <a tabindex="-1" href="<?php echo $url_prefix; ?>change_pass">Change Password</a>
                                    </li>
                                    <!-- <li>
                                        <a tabindex="-1" href="#">Change Password</a>
                                    </li> -->
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav">
                            <li class="active">
                                <a href="<?php echo $url_prefix; ?>admin">Dashboard</a>
                            </li>

                            <?php $get_permission_group = $this->db->query("SELECT GROUP_NAME FROM permission WHERE STATUS=1 GROUP BY GROUP_NAME ORDER BY GROUP_NAME")->result(); ?>
                            <?php foreach($get_permission_group as $gk=>$gv) {
                                    $get_permission = $this->db->query("SELECT * FROM permission WHERE STATUS=1 AND GROUP_NAME='".$gv->GROUP_NAME."' ORDER BY MENU_NAME")->result();
                                    # find out that; at least one permission has or not according to the group name
                                    $is_permitted = false;
                                    foreach($get_permission as $pk=>$pv){
                                        if( $this->webspice->permission_verify($pv->PERMISSION_NAME, true) ){
                                            $is_permitted = true; 
                                            break;
                                        }
                                    }

                                # create main menu
                                if( $is_permitted ){

                            ?>
                                
                                    <li class="dropdown">
                                        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo ucwords(str_replace("_"," ",$gv->GROUP_NAME)) ?> <b class="caret"></b>

                                        </a>
                                        <ul class="dropdown-menu" id="menu1">
                                            <?php
                                            # generate sub menu
                                            $menu_name = null;
                                            foreach($get_permission as $pk1=>$pv1){
                                                if( $this->webspice->permission_verify($pv1->PERMISSION_NAME, true) && $pv1->MENU_NAME != $menu_name ){
                                                    $menu_name = $pv1->MENU_NAME;
                                            ?>
                                            <li>
                                                <a href="<?php echo $url_prefix.$pv1->ROUTE_NAME; ?>"><?php echo ucwords(str_replace('_',' ',$pv1->MENU_NAME)); ?></a>
                                            </li>
                                            <?php } } ?>

                                        </ul>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                            <li>
                                <a href="<?php echo $url_prefix; ?>accounts">Accounts</a>
                            </li>
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>




        </div>
        <!-- <div class="overloaded"></div> -->

        <div class="container">
            <div class="row-fluid">
                        <!-- here will goes alert message -->
                        <?php if( $this->webspice->message_board(null, 'get') ): ?>
                        <div id="message_board" class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <!-- <h4>Success</h4> -->
                            <?php echo $this->webspice->message_board(null,'get_and_destroy'); ?>
                        </div>
                        <?php endif; ?>
                        <!-- alert message end -->
            </div>
        </div>
<!-- Choosen_JS -->

<!-- <script src="http://ajax.googleapis.com/ajax/libs/mootools/1.3/mootools-yui-compressed.js"></script> -->
<link href="<?php echo $url_prefix; ?>global/admin/assets/mootools-more-1.4.0.1.js" rel="stylesheet" media="screen">
<link href="<?php echo $url_prefix; ?>global/admin/assets/chosen.js" rel="stylesheet" media="screen">
<link href="<?php echo $url_prefix; ?>global/admin/assets/Locale.en-US.Chosen.js" rel="stylesheet" media="screen">
