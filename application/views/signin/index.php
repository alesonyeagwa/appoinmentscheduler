<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/styles.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/theme_1581438391731.css">
</head>

<body>
    <div class="login-dark">
        <!-- <form method="post" action="<?=base_url()?>signin/index"> -->
        <?= form_open('signin/index') ?>
            <h2 class="text-center">Login Form</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <?php if($form_validation == "No"){
               if(!empty($this->session->flashdata('errors'))){
                    echo "<div class=\"alert alert-danger alert-dismissable\">
                        <i class=\"fa fa-ban\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>".
                        $this->session->flashdata('errors') ."
                    </div>";
                }
                if(!empty($this->session->flashdata('success'))){
                    echo "<div class=\"alert alert-success alert-dismissable\">
                        <i class=\"fa fa-ban\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>".
                        $this->session->flashdata('success') ."
                    </div>";
                }
            } else {
                if(count($form_validation)) {
                    echo "<div class=\"alert alert-danger alert-dismissable\">
                        <i class=\"fa fa-ban\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                        $form_validation
                    </div>";
                }else{
                    if(!empty($this->session->flashdata('errors'))){
                        echo "<div class=\"alert alert-danger alert-dismissable\">
                            <i class=\"fa fa-ban\"></i>
                            <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>".
                            $this->session->flashdata('errors') ."
                        </div>";
                    }
                    if(!empty($this->session->flashdata('success'))){
                        echo "<div class=\"alert alert-success alert-dismissable\">
                            <i class=\"fa fa-ban\"></i>
                            <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>".
                            $this->session->flashdata('success') ."
                        </div>";
                    }
                }
            }
            ?>
            <div class="form-group"><input class="form-control" type="text" name="username" placeholder="Username"></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password"></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Log In</button></div>
            <div class="form-group">
                <p>Register as:</p>
                <?= anchor('register/agent', 'Agent', array('class' => 'btn btn-info btn-block')); ?>
                <?= anchor('register/user', 'User', array('class' => 'btn btn-info btn-block'));  ?>
            </div>
            <a href="<?= base_url('resetpass') ?>" class="forgot">Forgot your email or password?</a>
        <?= form_close() ?>
        <!-- </form> -->
    </div>
    <script src="<?=base_url()?>assets/js/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>