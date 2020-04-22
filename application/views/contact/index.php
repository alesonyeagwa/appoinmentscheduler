<?php echo form_open('dashboard/sendMessage', array(
    'data-bvalidator-validate' => ''
)) ?>
<div class="row">
    <div class="col-md-12">
        <h4>Send a feedback or a complaint</h4>
        <div class="form-row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Subject</label>
                    <?php echo form_input(array(
                        "name" => 'subject',
                        "class" => 'form-control',
                        "data-bvalidator" => "required,minlen[3],maxlen[50]"
                    )); ?>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Message (Max: 200 characters)</label>
                    <?php echo form_textarea(array(
                        "name" => 'message',
                        "class" => 'form-control',
                        "maxlength"=>"200",
                        "data-bvalidator" => "required,minlen[5],maxlen[200]"
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php echo form_submit('submit', 'Submit', array(
            'class' => 'btn btn-primary'
        )) ?>
    </div>
</div>
<?php echo form_close() ?>