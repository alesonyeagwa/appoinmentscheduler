<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=base_url()?>assets/fonts/ionicons.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
        <link rel="stylesheet" href="<?=base_url()?>assets/css/styles.css">
        <link rel="stylesheet" href="<?=base_url()?>assets/css/theme_1581438391731.css">
        <?php 
            if(!empty($loadcss)){
                echo loadCss($loadcss);
            } 
        ?>
        <script>
            var website = '<?= base_url(); ?>';
            var tName = '<?=$this->security->get_csrf_token_name()?>';
        </script>
        <script src="<?=base_url()?>assets/js/jquery.min.js"></script>
        <script src="<?=base_url()?>assets/js/vue.min.js"></script>
        <script src="<?=base_url()?>assets/js/toastr.min.js"></script>
        <script src="<?=base_url()?>assets/js/script.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Welcome <?= $this->session->username ?></h1>
                        <p><a href="<?=base_url('signin/signout')?>">Logout</a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="navvv">
                            <li><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <?php $CI =&get_instance(); ?>
                            <?php if(can('index-agent')): ?>
                            <li><a href="<?= base_url('agent') ?>">Agents</a></li>
                            <?php endif; ?>
                            <?php if(can('index-user')): ?>
                            <li><a href="<?= base_url('user') ?>">Users</a></li>
                            <?php endif; ?>
                            <?php if(can('index-logview')): ?>
                            <li><a href="<?= base_url('logview') ?>">View Logs</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <?php 
                    if(!empty($loadjs)){
                       echo loadJS($loadjs);
                    } 
                ?>
                <h4><?= $title ?></h4>
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
                <?php $this->load->view($subview); ?>
            </div>
        </div>
        <?php $this->load->view('components/footer'); ?>
        <script src="<?=base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
    </body>

</html>