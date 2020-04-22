
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("extraservice/index")?>"><?=$this->lang->line('panel_title')?></a></li>
            <li class="active"><?=$this->lang->line('extraservice_add')?> <?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?= form_open('extraservice/add') ?>
                    <?php
                    if(form_error('title'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="title" class="col-sm-2 control-label">
                            <?=$this->lang->line("extraservice_title")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title')?>" placeholder="<?=$this->lang->line("extraservice_title")?>">
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('title'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_class")?>" >
                        </div>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>
