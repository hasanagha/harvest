<? /* Base Template */ ?>
<html>
    <head>
        <title>Harvest: My Account</title>
        <? include "includes/favicon.php"; ?>
        <link href="<?=base_url()?>static/css/style.css" rel="stylesheet" type="text/css" >
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" >
        <link href="<?=base_url()?>static/css/sweetalert.css" rel="stylesheet" type="text/css" >
        <script type="text/javascript" src="<?=base_url()?>static/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>static/js/sweetalert.min.js"></script>
    </head>
    <body data-base-url="<?=base_url()?>">
        <header>
            <div class="logo">
                <img src="https://cdn.dynamo6.com/wp-content/themes/dynamic/img/dynamo6.svg" alt="Dynamo6 logo" class="svg" itemprop="logo">
            </div>
            <div class="dropdown">
                <button class="dropbtn"><? echo getUserName();?> <i class="fa fa-angle-double-down" aria-hidden="true"></i>
 </button>
                <div class="dropdown-content">
                    <a href="<?=site_url('account/projects')?>">Projects</a>
                    <a href="<?=site_url('account/timesheets')?>">Timesheets</a>
                    <a href="<?=site_url('account/logout')?>">Logout</a>
                </div>
            </div>
        </header>
        <div class="preloader">
            <img src="<?=base_url()?>static/images/preloader.gif" /> <br />
            processing ....
        </div>
        <div class="wrapper">
        <?php
            include (
                isset($page_name) && file_exists(FCPATH . 'base/views/' . $page_name . '.php')?$page_name:'404'
            ) . '.php';
        ?>
        </div>
    </body>
</html>