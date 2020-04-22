<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification | <?= $title ?></title>
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
    <div class="verify-page">
        <div class="email-sent">
            <?php if(!empty($this->session->flashdata('errors'))){
                echo "<div class=\"alert alert-danger alert-dismissable\">
                    <i class=\"fa fa-ban\"></i>
                    <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>".
                    $this->session->flashdata('errors') ."
                </div>";
            }
            ?>
            <?php if(!empty($this->session->flashdata('success'))){
                echo "<div class=\"alert alert-success alert-dismissable\">
                    <i class=\"fa fa-ban\"></i>
                    <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>".
                    $this->session->flashdata('success') ."
                </div>";
            }
            ?>
            <h2 class="text-center mb-5">Appointment scheduler</h2>
            <h4 class="text-center"><?= $message ?></h4>
            <?php if(!empty($canResendEmail) && $canResendEmail): ?>
                <a style="color: #fff !important" href="<?= base_url("verification/sendemail") ?>">Resend Email</a>
            <?php endif; ?>
            <a class="mt-5" style="color: #fff !important" href="<?= base_url("signin/index") ?>">Go Home</a>
        </div>
    </div>
</body>

</html>