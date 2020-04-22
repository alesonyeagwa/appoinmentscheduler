<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Agent</title>
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
        <?= form_open('register/agent', array(
                            'class' => 'password-strength',
                            'data-bvalidator-validate' => ''
                        )) ?>
            <h2 class="text-center">Agent Register Form</h2>
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
                            "name" => 'name',
                            "class" => 'form-control',
                            "value" => set_value('name', ''),
                            "placeholder"=> "Full Name",
                            "data-bvalidator" => "required,minlen[3],maxlen[50]",
                            "data-bvalidator-modifier" => "trim"
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo form_input(array(
                            "name" => 'email',
                            "class" => 'form-control',
                            "value" => set_value('email', ''),
                            "placeholder"=> "Email",
                            "data-bvalidator" => "required,email,maxlen[128]"
                        )); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo form_input(array(
                            "name" => 'phone',
                            "class" => 'form-control',
                            "placeholder"=> "Phone",
                            "value" => set_value('phone', ''),
                            "data-bvalidator" => "required,number,maxlen[12]"
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo form_input(array(
                            "name" => 'address',
                            "class" => 'form-control',
                            "value" => set_value('address', ''),
                            "placeholder"=> "Address",
                            "data-bvalidator" => "required,maxlen[255]"
                        )); ?>
                    </div>
                </div>
            </div>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <?php 
                        $secQs = array("" => "Security Question");
                        $_secQs = $this->session->userdata("secqs");
                        array_push($secQs, $_secQs);
                        echo form_dropdown('secq', $secQs, '', array(
                            "class" => 'form-control',
                            "placeholder"=> "Security question",
                            "data-bvalidator" => "required"
                        )); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo form_input(array(
                            "name" => 'seca',
                            "class" => 'form-control',
                            "placeholder"=> "Security answer",
                            "data-bvalidator" => "required",
                            "data-bvalidator-modifier" => "trim"
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="radio-inline">
                            <input type="radio" name="payment" value="1" checked>Basic Plan - $10/month
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payment" value="2"> Silver Plan - $8/month for 6 months
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payment" value="3">Gold Plan - $6/month for 6 months
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo form_submit('submit', 'Register', array(
                    'class' => 'btn btn-primary btn-block'
                )) ?>
            </div>
            <p class="text-center"><?= anchor("signin/index", "Go to Login", array("style" => "color: #fff !important")) ?></p>
        <?= form_close() ?>
    </div>
</body>

</html>