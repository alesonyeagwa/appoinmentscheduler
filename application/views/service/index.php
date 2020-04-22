<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
//                    if(permissionChecker('service_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('service/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
<!--                --><?php //} ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('service_title')?></th>
<!--                                --><?php //if(permissionChecker('service_edit') || permissionChecker('service_delete') || permissionChecker('service_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
<!--                                --><?php //} ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($services)) {$i = 1; foreach($services as $service) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('service_title')?>">
                                        <?php
                                            if(strlen($service->title) > 25)
                                                echo strip_tags(substr($service->title, 0, 25)."...");
                                            else
                                                echo strip_tags(substr($service->title, 0, 25));
                                        ?>
                                    </td>
<!--                                    --><?php //if(permissionChecker('service_edit') || permissionChecker('service_delete') || permissionChecker('service_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('service/view/'.$service->serviceID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('service/edit/'.$service->serviceID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('service/delete/'.$service->serviceID, $this->lang->line('delete')) ?>
                                        </td>
<!--                                    --><?php //} ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>