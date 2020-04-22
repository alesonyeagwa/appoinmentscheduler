
<div class="box">
    <div class="box-header">
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a>/</li>
            <li><a href="<?=base_url("user/index")?>"><?=$this->lang->line('panel_title')?></a>/</li>
            <li class="active"><?=$this->lang->line('user_add')?> <?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?= form_open('user/add') ?>
                    <div class='form-group' >
                        <label for="name" class="col-sm-2 control-label">
                            <?=$this->lang->line("name")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>" placeholder="<?=$this->lang->line("panel_title") . ' ' . $this->lang->line("name") ?>" required>
                        </div>
                        <?php
                            if(form_error('name')){
                                echo '<div class="col text-danger">'. form_error('name') .'</div>';
                            }
                        ?>
                        <label for="email" class="col-sm-2 control-label">
                            <?=$this->lang->line("email")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="email" class="form-control" id="email" name="email" value="<?=set_value('email')?>" placeholder="<?=$this->lang->line("email")?>" required>
                        </div>
                        <?php
                            if(form_error('email')){
                                echo '<div class="col text-danger">'. form_error('email') .'</div>';
                            }
                        ?>
                        <label for="phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("phone")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone')?>" placeholder="<?=$this->lang->line("phone")?>" required>
                        </div>
                        <?php
                            if(form_error('phone')){
                                echo '<div class="col text-danger">'. form_error('phone') .'</div>';
                            }
                        ?>
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
