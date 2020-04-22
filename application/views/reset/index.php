<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/styles.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/theme_1581438391731.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/gray4.css">
    <script src="<?=base_url()?>assets/js/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/js/jquery.bvalidator.min.js"></script>
    <script src="<?=base_url()?>assets/js/default.min.js"></script>
    <script src="<?=base_url()?>assets/js/gray4.js"></script>
    <script>
        bValidator.defaultOptions.useTheme = 'gray4';
        bValidator.defaultOptions.themes.gray4.showClose = true;  
        bValidator.defaultOptions.singleError = true;  
    </script>
</head>

<body>
    <div class="register-dark">
        <?php if($resetStep == '1'): ?>
        <?= form_open('resetpass/email', array(
                            'class' => 'password-strength',
                            'data-bvalidator-validate' => ''
                        )) ?>
            <h2 class="text-center">Enter email registered</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <?php if(empty($form_validation)){
            } else {
                if(count($form_validation)) {
                    echo "<div class=\"alert alert-danger alert-dismissable\">
                        <i class=\"fa fa-ban\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                        $form_validation
                    </div>";
                }
            }
            ?>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo form_input(array(
                            "name" => 'email',
                            "class" => 'form-control',
                            "value" => set_value('email', ''),
                            "placeholder"=> "Email",
                            "data-bvalidator" => "required,email,maxlen[128]",
                            "data-bvalidator-modifier" => "trim"
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_submit('submit', 'Submit', array(
                    'class' => 'btn btn-primary btn-block'
                )) ?>
            </div>
            <p><?= anchor("signin/index", "Go to Login", array("style" => "color: #fff !important")) ?></p>
        <?= form_close() ?>
        <?php endif; ?>
        <?php if($resetStep == '2'): ?>
        <?= form_open('resetpass/secq', array(
                            'class' => 'password-strength',
                            'data-bvalidator-validate' => ''
                        )) ?>
            <h2 class="text-center">Answer the security question</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <?php if(empty($form_validation)){
            } else {
                if(count($form_validation)) {
                    echo "<div class=\"alert alert-danger alert-dismissable\">
                        <i class=\"fa fa-ban\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                        $form_validation
                    </div>";
                }
            }
            ?>
            <div class="form-row">
                <div class="col-md-12">
                    <h5><?= $secq ?></h5>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo form_input(array(
                            "name" => 'seca',
                            "class" => 'form-control',
                            "value" => set_value('seca', ''),
                            "placeholder"=> "Answer",
                            "data-bvalidator" => "required",
                            "data-bvalidator-modifier" => "trim"
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_submit('submit', 'Submit', array(
                    'class' => 'btn btn-primary btn-block'
                )) ?>
            </div>
            <p><?= anchor("signin/index", "Go to Login", array("style" => "color: #fff !important")) ?></p>
        <?= form_close() ?>
        <?php endif; ?>
        <?php if($resetStep == '3'): ?>
        <?= form_open('resetpass/change_password', array(
                            'class' => 'password-strength',
                            'data-bvalidator-validate' => ''
                        )) ?>
            <h2 class="text-center">Enter the token sent to your email</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <?php 
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
            ?>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <?php echo form_password(array(
                                "name" => 'pass',
                                "id" => 'pass-field',
                                "class" => 'form-control password-strength__input',
                                "placeholder"=> "Password",
                                "data-bvalidator" => "required,minlen[8]|maxlen[128]",
                                "autocomplete" => "new-password"
                            )); ?>
                            <div class="input-group-append">
                                <button class="password-strength__visibility btn btn-outline-secondary" type="button"><span class="password-strength__visibility-icon" data-visible="hidden"><i class="fas fa-eye-slash"></i></span><span class="password-strength__visibility-icon js-hidden" data-visible="visible"><i class="fas fa-eye"></i></span></button>
                            </div>
                        </div>
                        <div class="password-strength__bar-block progress mb-4">
                            <div class="password-strength__bar progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="password-strength__error text-danger js-hidden">This symbol is not allowed!</small>
                        <small class="form-text text-muted mt-2" id="passwordHelp">Add 9 charachters or more, lowercase letters, uppercase letters, numbers and symbols to make the password really strong!</small>
                        <script src="<?=base_url('assets/js/pass.js') ?>"></script>
                    </div>                  
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo form_password(array(
                            "name" => 'rpass',
                            "class" => 'form-control',
                            "placeholder" => "Repeat Password",
                            "data-bvalidator" => "required,equal[pass-field]",
                            "autocomplete" => "new-password"
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo form_input(array(
                            "name" => 'emailtoken',
                            "class" => 'form-control',
                            "value" => set_value('emailtoken', ''),
                            "placeholder"=> "Enter Token",
                            "data-bvalidator" => "required",
                            "data-bvalidator-modifier" => "trim"
                        )); ?>
                        <p><?= anchor("resetpass/resendToken", "Resend token", array("style" => "color: #fff !important")) ?></p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_submit('submit', 'Submit', array(
                    'class' => 'btn btn-primary btn-block'
                )) ?>
            </div>
            <p><?= anchor("signin/index", "Go to Login", array("style" => "color: #fff !important")) ?></p>
        <?= form_close() ?>
        <?php endif; ?>
    </div>
</body>

</html>