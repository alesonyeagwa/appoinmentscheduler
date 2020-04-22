<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard | Log Viewer</title>
        <!-- <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="<?=base_url()?>assets/fonts/ionicons.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
        <link rel="stylesheet" href="<?=base_url()?>assets/css/styles.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <link rel="stylesheet"
          href="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <?php 
            if(!empty($loadcss)){
                echo loadCss($loadcss);
            } 
        ?>
        <style>
            h1 {
                font-size: 1.5em;
                margin-top: 0;
            }

            .date {
                min-width: 75px;
            }

            .text {
                word-break: break-all;
            }

            a.llv-active {
                z-index: 2;
                background-color: #f5f5f5;
                border-color: #777;
            }
        </style>
        <script src="<?=base_url()?>assets/js/jquery.min.js"></script>
        <script src="<?=base_url()?>assets/js/vue.min.js"></script>
        <script src="<?=base_url()?>assets/js/script.js"></script>
        <script>
            var website = '<?= base_url(); ?>';
        </script>
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
                            <li><a href="<?= base_url('settings') ?>">Settings</a></li>
                        </ul>
                    </div>
                </div>
                
                <?php 
                    if(!empty($loadjs)){
                       echo loadJS($loadjs);
                    } 
                ?>
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3 col-md-2 sidebar">
                            <h3><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Log Files</h3>
                            <p class="text-muted">
                                
                            </p>
                            <div class="list-group">
                                <?php if(empty($files)): ?>
                                    <a class="list-group-item liv-active">No Log Files Found</a>
                                <?php else: ?>
                                    <?php foreach($files as $file): ?>
                                        <a href="?f=<?= base64_encode($file); ?>"
                                        class="list-group-item <?= ($currentFile == $file) ? "llv-active" : "" ?>">
                                            <?= str_replace('.php', '', $file); ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-9 col-md-10 table-container">
                            <?php if(is_null($logs)): ?>
                                <div>
                                    <br><br>
                                    <strong>Log file > 50MB, please download it.</strong>
                                    <br><br>
                                </div>
                            <?php else: ?>
                                <table id="table-log" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Level</th>
                                        <th>Date</th>
                                        <th>Content</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php foreach($logs as $key => $log): ?>
                                        <tr data-display="stack<?= $key; ?>">

                                            <td class="text-<?= $log['class']; ?>">
                                                <span class="<?= $log['icon']; ?>" aria-hidden="true"></span>
                                                &nbsp;<?= $log['level']; ?>
                                            </td>
                                            <td class="date"><?= $log['date']; ?></td>
                                            <td class="text">
                                                <?php if (array_key_exists("extra", $log)): ?>
                                                    <a class="pull-right expand btn btn-default btn-xs"
                                                    data-display="stack<?= $key; ?>">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </a>
                                                <?php endif; ?>
                                                <?= $log['content']; ?>
                                                <?php if (array_key_exists("extra", $log)): ?>
                                                    <div class="stack" id="stack<?= $key; ?>"
                                                        style="display: none; white-space: pre-wrap;">
                                                        <?= $log['extra'] ?>
                                                    </div>
                                                <?php endif; ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                            <div>
                                <?php if($currentFile): ?>
                                    <a href="?dl=<?= base64_encode($currentFile); ?>">
                                        <span class="glyphicon glyphicon-download-alt"></span>
                                        Download file
                                    </a>
                                    -
                                    <a id="delete-log" href="?del=<?= base64_encode($currentFile); ?>"><span
                                                class="glyphicon glyphicon-trash"></span> Delete file</a>
                                    <?php if(count($files) > 1): ?>
                                        -
                                        <a id="delete-all-log" href="?del=<?= base64_encode("all"); ?>"><span class="glyphicon glyphicon-trash"></span> Delete all files</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
                <script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
                <script>
                    $(document).ready(function () {

                        $('.table-container tr').on('click', function () {
                            $('#' + $(this).data('display')).toggle();
                        });

                        $('#table-log').DataTable({
                            "order": [],
                            "stateSave": true,
                            "stateSaveCallback": function (settings, data) {
                                window.localStorage.setItem("datatable", JSON.stringify(data));
                            },
                            "stateLoadCallback": function (settings) {
                                var data = JSON.parse(window.localStorage.getItem("datatable"));
                                if (data) data.start = 0;
                                return data;
                            }
                        });
                        $('#delete-log, #delete-all-log').click(function () {
                            return confirm('Are you sure?');
                        });
                    });
                </script>
            </div>
        </div>
        <?php $this->load->view('components/footer'); ?>
        <script src="<?=base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
    </body>

</html>