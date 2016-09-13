<html>
	<head>
		<title>Harvest: Login</title>
        <? include "includes/favicon.php"; ?>
        <link href="<?=base_url()?>static/css/style.css" rel="stylesheet" type="text/css" >
        <script type="text/javascript" src="<?=base_url()?>static/js/jquery.min.js"></script>
	</head>
	<body>
        <?php
        if($message){
            echo '<div class="success">' . $message . '</div>';
        }
        ?>

        <div class="d6-form">
            <form name="frm_login" id="frm_login" method="post" class="login-form-wrapper">
                <fieldset>
                    <legend>Login</legend>

                    <?php echo form_error('txt_account', '<span class="error">', '</span>'); ?>
                    <input name="txt_account" value="<? echo set_value('txt_account');?>" placeholder="Account *" type="text">

                    <?php echo form_error('txt_email', '<span class="error">', '</span>'); ?>
                    <input name="txt_email" value="<? echo set_value('txt_email');?>" placeholder="Email Address *" type="email" />

                    <?php echo form_error('txt_password', '<span class="error">', '</span>'); ?>
                    <input name="txt_password" value="<? echo set_value('txt_password');?>" placeholder="Password *" type="password" />

                </fieldset>
                <input class="button button-primary button-medium" type="submit" name="btn_submit" id="btn_submit" value="Login"/>
            </form>
        </div>

	</body>
</html>