<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#item-1-1" id="item-1-1-tab" data-toggle="tab" role="tab" aria-controls="item-1-1" aria-selected="true">My Appointments</a></li>
                    <li class="nav-item"><a class="nav-link" href="#item-1-2" id="item-1-2-tab" data-toggle="tab" role="tab" aria-controls="item-1-2" aria-selected="false">My Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#item-1-3" id="item-1-3-tab" data-toggle="tab" role="tab" aria-controls="item-1-3" aria-selected="false">Appointments Scheduling</a></li>
                    <li class="nav-item"><a class="nav-link" href="#item-1-4" id="item-1-4-tab" data-toggle="tab" role="tab" aria-controls="item-1-4" aria-selected="false">Messages</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div id="nav-tabContent" class="tab-content">
                    <div id="item-1-1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="item-1-1-tab">
                        <div id="appointments" class="row mt-5 hidden">
                            <div class="col-12 d-flex justify-content-center align-items-center" v-if="showLoader">
                                <span>
                                    <i class="fa fa-spin fa-spinner"></i>
                                </span>
                            </div>
                            <div class="col-12" v-if="!showLoader">
                                <div v-if="appointments.length <= 0">
                                    <p>No appointments</p>
                                </div>
                                <div class="appoints" v-if="appointments.length > 0">
                                    <div class="appointment" v-for="appoint in appointments">
                                        <h4>{{appoint.serviceName}}</h4>
                                        <table>
                                            <tr>
                                                <td>Date</td>
                                                <td>{{new Date(appoint.appointmentDate).toDateString()}}</td>
                                            </tr>
                                            <tr>
                                                <td>Time</td>
                                                <td>{{appoint.starttime}} - {{appoint.endtime}}</td>
                                            </tr>
                                            <tr>
                                                <td>Client</td>
                                                <td>{{appoint.clientName}}</td>
                                            </tr>
                                            <tr>
                                                <td>Client Email</td>
                                                <td>{{appoint.clientEmail}}</td>
                                            </tr>
                                            <tr>
                                                <td>Client Phone</td>
                                                <td>{{appoint.clientPhone}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>
                                                    <span class="badge" v-bind:class="{'badge-warning': appoint.status == 2, 'badge-info': appoint.status == 3, 'badge-success': appoint.status == 4, 'badge-danger': appoint.status == 0 || appoint.status == 1}">
                                                        <span v-if="appoint.status == 0">Declined</span>
                                                        <span v-if="appoint.status == 1">Cancelled by user</span>
                                                        <span v-if="appoint.status == 2">Pending Approval</span>
                                                        <span v-if="appoint.status == 3">Approved</span>
                                                        <span v-if="appoint.status == 4">Completed</span>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="appoint.extservs">
                                                <td>Extra Servicces</td>
                                                <td>
                                                    <span class="extserv">
                                                        <small>+{{appoint.extservs.length}} service(s)</small>
                                                    </span>
                                                    <div class="extserv-modal" v-if="appoint.extservs">
                                                        <h6>Extra Services</h6>
                                                        <table>
                                                            <tr v-for="extserv in appoint.extservs">
                                                                <td><b>{{extserv.name}}</b>-   </td>
                                                                <td>${{extserv.cost}}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="appoint.status == 2">
                                                <td colspan="2">
                                                    <button :disabled="appApproving.indexOf(appoint.appointmentID) > -1" v-on:click="approveAppointment(appoint)" class="btn btn-primary btn-block mt-3">{{appApproving.indexOf(appoint.appointmentID) > -1 ? 'Approving...' : 'Approve'}}</button>
                                                </td>
                                            </tr>
                                            <tr v-if="appoint.status == 3">
                                                <td colspan="2">
                                                    <button :disabled="appCompleting.indexOf(appoint.appointmentID) > -1" v-on:click="completeAppointment(appoint)" class="btn btn-success btn-block mt-3">{{appCompleting.indexOf(appoint.appointmentID) > -1 ? 'Completing...' : 'Complete'}}</button>
                                                </td>
                                            </tr>
                                            <tr v-if="appoint.status == 2 || appoint.status == 3">
                                                <td colspan="2">
                                                    <button :disabled="appCancelling.indexOf(appoint.appointmentID) > -1" v-on:click="cancelAppointment(appoint)" class="btn btn-danger btn-block mt-3">{{appCancelling.indexOf(appoint.appointmentID) > -1 ? 'Declining...' : 'Decline'}}</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="item-1-2" class="tab-pane fade" role="tabpanel" aria-labelledby="item-1-2-tab">
                        <?php echo form_open('agent/update_profile', array(
                            'id' => 'acc-info-form',
                            'data-bvalidator-validate' => ''
                        )) ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Account Info</h4>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <?php echo form_input(array(
                                                "name" => 'name',
                                                "class" => 'form-control',
                                                "value" => $this->session->userdata('name'),
                                                "data-bvalidator" => "required,minlen[3],maxlen[50]"
                                            )); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <?php echo form_input(array(
                                                "class" => 'form-control',
                                                "value" => $this->session->userdata('email'),
                                                "disabled" => "disabled"
                                            )); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Phone</label>
                                            <?php echo form_input(array(
                                                "name" => 'phone',
                                                "class" => 'form-control',
                                                "value" => $this->session->userdata('phone'),
                                                "data-bvalidator" => "required,minlen[5],maxlen[12]"
                                            )); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Appointment Address</label>
                                            <?php echo form_input(array(
                                                "name" => 'address',
                                                "class" => 'form-control',
                                                "value" => $this->session->userdata('address'),
                                                "data-bvalidator" => "required,maxlen[255]"
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
                        <?php echo form_open('agent/change_password', array(
                            'class' => 'password-strength',
                            'id' => 'change-pass-form',
                            'data-bvalidator-validate' => ''
                        )) ?>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h4>Change Password</h4>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Current Password</label>
                                            <?php echo form_password(array(
                                                "name" => 'cpass',
                                                "class" => 'form-control',
                                                "placeholder" => "********",
                                                "data-bvalidator" => "required"
                                            )); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="npass">New Password</label>
                                            <div class="input-group">
                                                <?php echo form_password(array(
                                                "name" => 'npass',
                                                "id" => 'pass-field',
                                                "class" => 'form-control password-strength__input',
                                                "placeholder" => "********",
                                                "data-bvalidator" => "required,minlen[8]|maxlen[128]"
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
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Repeat Password</label>
                                            <?php echo form_password(array(
                                                "name" => 'rpass',
                                                "class" => 'form-control',
                                                "placeholder" => "********",
                                                "data-bvalidator" => "required,equal[pass-field]"
                                            )); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Email token</label>
                                            <?php echo form_input(array(
                                                "name" => 'emailtoken',
                                                "class" => 'form-control',
                                                "data-bvalidator" => "required"
                                            )); ?>
                                            <a id="send-token" class="cursor-pointer">Send token</a>
                                            <script>
                                                $('#send-token').on('click', function(e){
                                                    e.preventDefault();
                                                    $('#send-token').prop('disabled', '');
                                                    $('#send-token').text('Sending...')
                                                    makeRequest('pass_change_token', {}, (res) => {
                                                        $('#send-token').text('Send token');
                                                        if(!res.error){
                                                            toastr.success(res.response);
                                                        }else{
                                                            toastr.error(res.response);
                                                        }
                                                    }, (error) => {
                                                        $('#send-token').text('Send token');
                                                        toastr.error("Sorry, an unexpected error occurred.");
                                                    })
                                                })
                                            </script>
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
                    </div>
                    <div id="item-1-3" class="tab-pane fade" role="tabpanel" aria-labelledby="item-1-3-tab">
                        <div id="appointment-scheduling" class="py-3">
                            <div class="row" >
                                <div class="col-sm-12 d-flex justify-content-center align-items-center" v-if="showLoader">
                                    <span>
                                        <i class="fa fa-spin fa-spinner"></i>
                                    </span>
                                </div>
                                <div class="col-sm-12 mb-1" v-if="!showLoader">
                                    
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-service-modal">New Service</button>
                                </div>
                                <div class="col-sm-12" v-if="Object.keys(servicecategories).length > 0 && !showLoader">
                                    <div class="category mt-3 py-5" v-for="(servicecat, catIdx) in servicecategories">
                                        <h3 class="cat-head">{{servicecat[0].categoryName}}</h3>
                                        <div class="row service my-3 py-5" v-for="(service, seIdx) in servicecat">
                                            <div class="col-12">
                                                <h4>{{service.serviceName}} <a class="text-danger cursor-pointer ml-3" v-on:click="deleteService(service,catIdx, seIdx)"><small>Delete</small></a></h4>
                                                <div class="row">
                                                    <div class="col-12 col-md-7">
                                                        <div action="">
                                                            <h5>Service Information</h5>
                                                            <div class="form-row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Service Name</label>
                                                                        <input type="text" v-model="service.serviceName" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Service Cost</label>
                                                                        <input type="number" v-model="service.cost" min="0" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Status</label>
                                                                        <select name="" class="form-control" v-model="service.status">
                                                                            <option value="0">Disabled</option>
                                                                            <option value="1">Enabled</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <button class="btn btn-primary" v-on:click="updateServiceInformation(service, seIdx)" :disabled="updatingServiceInfo">
                                                                    {{ updatingServiceInfo ? 'Updating...' : 'Update Information'}}
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="schedule-info mt-5">
                                                            <h5><strong>Schedule Information</strong></h5>
                                                            <div class="panel-group" id="accordion">
                                                                <div class="panel panel-default" v-for="(wikDay, index) in daysOfWeek">
                                                                    <div class="panel-heading">
                                                                    <a data-toggle="collapse" data-parent="#accordion" v-bind:href="'#collapse-' + catIdx + '-' + seIdx + '-' + index">
                                                                        <h4 class="panel-title m-0">
                                                                           {{wikDay}}
                                                                        </h4></a>
                                                                    </div>
                                                                    <div v-bind:id="'collapse-' + catIdx + '-' + seIdx + '-' + index" class="panel-collapse collapse in">
                                                                        <div class="panel-body">
                                                                            <div class="timmings">
                                                                                <div v-for="(timing, tIndex) in service.serviceTiming[days[index]]">
                                                                                    <p class="mb-1 d-flex justify-content-end" v-bind:class="{'justify-content-end': timing.timingID, 'justify-content-between': !timing.timingID}">
                                                                                        <span v-if="!timing.timingID">
                                                                                            <em>New: Unsaved</em>
                                                                                        </span>
                                                                                        <span>
                                                                                            <span>
                                                                                                <a v-on:click="deleteTiming(service, index, tIndex, timing.timingID)" class="text-danger cursor-pointer">Delete</a>
                                                                                                <a class="text-success cursor-pointer ml-2" v-on:click="saveTiming(service, index, tIndex, timing)" :disabled="timingSaving.indexOf(timing) >= 0">{{timingSaving.indexOf(timing) >= 0 ? 'Saving' : 'Save'}}</a>
                                                                                            </span>
                                                                                        </span>
                                                                                    </p>
                                                                                    <div class="timing">
                                                                                        <span>
                                                                                            <input :checked="timing.status == 1" type="checkbox" v-on:change="statusChange(timing, $event)" name="" id="">
                                                                                        </span>
                                                                                        <ul class="list-unstyled timing-times">
                                                                                            <li>
                                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                                    <span>Start Time</span>
                                                                                                    <span>
                                                                                                        <input class="time-picker" v-model="timing.starttime" v-on:change="starttimeChange(timing, $event)"  type="text">
                                                                                                    </span>
                                                                                                </div>
                                                                                            </li>
                                                                                            <li>
                                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                                    <span>Finish Time</span>
                                                                                                    <span>
                                                                                                        <input class="time-picker" v-model="timing.endtime" v-on:change="endtimeChange(timing, $event)" type="text">
                                                                                                    </span>
                                                                                                </div>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                    <button class="btn btn-info btn-block" v-on:click="addTiming(service, index)">Add Timing</button>
                                                                                </div>
                                                                            </div>
                                                                            <ul class="list-unstyled"></ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-5">
                                                        <div class="">
                                                            <table class="extra-servs" >
                                                                <thead>
                                                                    <th width="70%">Service name</th>
                                                                    <th width="20%">Cost</th>
                                                                    <th width="10%">Action</th>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-if="service.extservs && service.extservs.length > 0" class="ext-serv" v-for="ext in service.extservs">
                                                                        <td width="70%">{{ext.name}}</td>
                                                                        <td width="20%">{{ext.cost}}</td>
                                                                        <td width="10%"><a class="text-danger cursor-pointer" v-on:click="deleteExtraService(service, ext.esID)">Delete</a></td>
                                                                    </tr>
                                                                    <tr v-if="newExtraServs[catIdx][seIdx].length > 0" v-for="newserv in newExtraServs[catIdx][seIdx]">
                                                                        <td width="70%">
                                                                            <input type="text" v-model="newserv.name" maxlength="25">
                                                                        </td>
                                                                        <td width="20%">
                                                                            <input type="number" min="0" v-model="newserv.cost">
                                                                        </td>
                                                                        <td width="10%"><a class="text-danger cursor-pointer" v-on:click="cancelNewExt(newserv, catIdx, seIdx)">Cancel</a></td>
                                                                    </tr>
                                                                    <tr v-if="newExtraServs[catIdx][seIdx].length > 0">
                                                                        <td class="text-center" colspan="3"><a v-on:click="saveNewExtra(service, catIdx, seIdx)" class="cursor-pointer">Save new</a></td>
                                                                    </tr>
                                                                    <tr  v-if="service.extservs">
                                                                        <td colspan="3">
                                                                            <button v-on:click="addExtraService(service, catIdx, seIdx)" class="btn btn-primary btn-block">Add Extra Service</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div v-if="!service.extservs">
                                                                <p class="text-center">No Extra Service</p>
                                                                <p><button v-on:click="addExtraService(service, catIdx, seIdx)" class="btn btn-primary btn-block">Add Extra Service</button></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 text-center" v-if="Object.keys(servicecategories).length <= 0 && !showLoader">
                                    No services
                                </div>
                                <!-- Modal -->
                                <div id="new-service-modal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Add new service</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <select name="cat" class="form-control" id="" v-on:change="onCategoryChange($event)" v-model="newService.serviceCategoryID">
                                                                <?php foreach($servcats as $val): ?>
                                                                    <option value="<?=$val->serviceCategoryID ?>"><?=$val->categoryName ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Service Name</label>
                                                            <input type="text" v-model="newService.serviceName" class="form-control" maxlength="25">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Cost</label>
                                                            <input type="number" min="0" v-model="newService.cost" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <button class="btn btn-primary" v-on:click="addService()" :disabled="addingService">
                                                        {{ addingService ? 'Adding...' : 'Add'}}
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="item-1-4" class="tab-pane fade" role="tabpanel" aria-labelledby="item-1-4-tab">
                        <?php $this->load->view('contact/index'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    bValidator.defaultOptions.useTheme = 'gray4';
    bValidator.defaultOptions.themes.gray4.showClose = true;  
    //$('#acc-info-form').bValidator();
</script>
<script>
    

</script>
<script>
    var token = '<?=$this->security->get_csrf_hash() ?>';
    var defUserImage = '<?= base_url('assets/img/user.png') ?>'
    var _unexpectedError = "Sorry, an unexpected error occured"; 
    var vueDash = new Vue({
        el: '#appointments',
        data: {
            appointments: [],
            appCancelling: [],
            appApproving: [],
            appCompleting: [],
            showLoader: true
        },
        methods: {
            cancelAppointment: function(appointment){
                if(appointment.status == 2 || appointment.status == 3){
                    var canacelApp = confirm("Are you sure you want to decline this appointment? This cannot be undone.")
                    if(canacelApp){
                        this.appCancelling.push(appointment.appointmentID)
                        var _this = this;
                        _data = {appID: appointment.appointmentID}
                        makeRequest("cancel_appointment", _data, (res) => {
                            _this.removeAppointmentFromCancelling(appointment.appointmentID)
                            if(!res.error){
                                toastr.success("Cancelled appointment successfully")
                                appointment.status = 0;
                            }else{
                                toastr.error(res.response)
                            }
                        }, (error) => {
                            _this.removeAppointmentFromCancelling(appointment.appointmentID)
                            toastr.error(_unexpectedError)

                        })
                    }
                }
            },
            approveAppointment: function(appointment){
                if(appointment.status == 2){
                    var canacelApp = confirm("Are you sure you want to approve this appointment?")
                    if(canacelApp){
                        this.appApproving.push(appointment.appointmentID)
                        var _this = this;
                        _data = {appID: appointment.appointmentID}
                        makeRequest("approve_appointment", _data, (res) => {
                            _this.removeAppointmentFromApproving(appointment.appointmentID)
                            if(!res.error){
                                toastr.success("Approved appointment successfully")
                                appointment.status = 3;
                            }else{
                                toastr.error(res.response)
                            }
                        }, (error) => {
                            _this.removeAppointmentFromApproving(appointment.appointmentID)
                            toastr.error(_unexpectedError)
                        })
                    }
                }
            },
            completeAppointment: function(appointment){
                if(appointment.status == 3){
                    var canacelApp = confirm("Are you sure you want to complete this appointment?")
                    if(canacelApp){
                        this.appCompleting.push(appointment.appointmentID)
                        var _this = this;
                        _data = {appID: appointment.appointmentID}
                        makeRequest("complete_appointment", _data, (res) => {
                            _this.removeAppointmentFromCompleting(appointment.appointmentID)
                            if(!res.error){
                                toastr.success("Completed appointment successfully")
                                appointment.status = 4;
                            }else{
                                toastr.error(res.response)
                            }
                        }, (error) => {
                            _this.removeAppointmentFromCompleting(appointment.appointmentID)
                            toastr.error(_unexpectedError)
                        })
                    }
                }
            },
            setAppStatus(appId, status){

            },
            removeAppointmentFromCancelling(id){
                if(id){
                    this.appCancelling = this.appCancelling.filter(_id => _id != id)
                }
            },
            removeAppointmentFromApproving(id){
                if(id){
                    this.appApproving = this.appApproving.filter(_id => _id != id)
                }
            },
            removeAppointmentFromCompleting(id){
                if(id){
                    this.appCompleting = this.appCompleting.filter(_id => _id != id)
                }
            }
        },
        mounted(){
            var _this = this;
            var loadAppoints = function(){
                makeGETRequest('get_appointments', (data) => {
                    _this.showLoader = false
                    _this.appointments = data
                },(data)=> {
                    _this.showLoader = false
                });
            }
            loadAppoints();
            $('#item-1-1-tab').on('click', function(){
                loadAppoints();
            })
            $('#appointments').removeClass('hidden');
        }
    })



    var vueScheduler = new Vue({
        el: '#appointment-scheduling',
        data: {
            showLoader: true,
            servicecategories: [],
            daysOfWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            days: ['mon','tue','wed','thu','fri','sat', 'sun'],
            timingSaving: [],
            newExtraServs: [],
            updatingServiceInfo: false,
            serviceNames: [],
            newService: {serviceID: "", serviceName: "", serviceCategoryID: '', cost: '' },
            addingService: false,
            addServiceCategoryName: ""
        },
        methods: {
            addTiming: function(service, index){
                if(!service.serviceTiming){
                    service.serviceTiming = []
                }
                if(!service.serviceTiming[this.days[index]]){
                    service.serviceTiming[this.days[index]] = []
                }
                service.serviceTiming[this.days[index]].push({starttime: "", endtime: "", status: 1, appday: this.days[index], slots: 1, serviceID: service.serviceID})
                vueScheduler.$forceUpdate();
                setTimeout(() => {
                    initTimePicker();
                }, 500);
            },
            deleteTiming: function(service, index, tIndex, timingID){
                var contfirmDelete = confirm("Are you sure you want to delete this schedule?");
                if(contfirmDelete){
                    var tempTiming = service.serviceTiming[this.days[index]];
                    service.serviceTiming[this.days[index]] = service.serviceTiming[this.days[index]].filter((t,i) => {
                        return i != tIndex;
                    });
                    vueScheduler.$forceUpdate();
                    if(timingID){
                        var _this = this;
                        _data = {timingID}
                        toastr.options = {
                            "showDuration": "15000",
                            "hideDuration": "3000",
                            "timeOut": "15000",
                        }
                        makeRequest("delete_timing", _data, (res) => {
                            if(!res.error){
                                toastr.success("Deleted schedule successfully")
                            }else{
                                res.error(data.response)
                                service.serviceTiming[_this.days[index]] = tempTiming
                            }
                        }, (error) => {
                            toastr.error(_unexpectedError)
                            service.serviceTiming[_this.days[index]] = tempTiming
                        })
                    }
                }
            },
            saveTiming: function(service, index, tIndex, timing){
                var proceed = false;
                if(timing.timingID){
                    var confirmSave = confirm("Are you sure you want to update this schedule?");
                    if(confirmSave){
                        proceed = true;
                    }
                }else{
                    var confirmSave = confirm("Are you sure you want to save this schedule?");
                    if(confirmSave){
                        proceed = true;
                    }
                }
                if(proceed){
                    this.timingSaving.push(timing)
                    var _this = this;
                    _data = timing
                    makeRequest("save_timing", _data, (res) => {
                        _this.removeTimingSaving(timing)
                        if(!res.error){
                            if(res.extra){
                                timing.timingID = res.extra
                            }
                            toastr.success("Saved schedule successfully")
                        }else{
                            toastr.error(res.response)
                        }
                    }, (error) => {
                        _this.removeTimingSaving(timing)
                        toastr.error(_unexpectedError)
                    })
                }
            },
            removeTimingSaving: function(timing){
                if(timing){
                    this.timingSaving = this.timingSaving.filter(s => s != timing)
                }
            },
            starttimeChange: function(timing, event){
                timing.starttime = event.target.value
            },
            endtimeChange: function(timing, event){
                timing.endtime = event.target.value
            },
            statusChange: function(timing, event){
                timing.status = event.target.checked ? 1 : 0
            },
            addExtraService: function(service, catIdx, seIdx){
                if(!this.newExtraServs[catIdx]){
                    this.newExtraServs[catIdx] = [];
                }
                if(!this.newExtraServs[catIdx][seIdx]){
                    this.newExtraServs[catIdx][seIdx] = []
                }
                this.newExtraServs[catIdx][seIdx].push({name: "", cost: 0, serviceID: service.serviceID})
                if(!service.extservs){
                    service.extservs = [];
                }
                vueScheduler.$forceUpdate();
            },
            cancelNewExt: function(newserv, catIdx, seIdx){
                this.newExtraServs[catIdx][seIdx] = this.newExtraServs[catIdx][seIdx].filter(e => e != newserv);
                vueScheduler.$forceUpdate();
            },
            saveNewExtra: function(service, catIdx, seIdx){
                if(this.newExtraServs[catIdx][seIdx].length > 0){
                    var sameName = service.extservs.some(a => this.newExtraServs[catIdx][seIdx].find(b => b.name.trim().toLowerCase() == a.name.trim().toLowerCase()))
                    if(sameName){
                        alert("Two services cannot have the same name")
                        return;
                    }
                    var saveConfirm = confirm("Are you sure you want to save the new extra services");
                    if(saveConfirm){
                        var _this = this;
                        var data = [];
                        this.newExtraServs[catIdx][seIdx].forEach(s => data.push(s));
                        makeRequest('save_extra_services', {extservs: data}, (res) => {
                            if(!res.error){
                                toastr.success(res.response);
                                _this.newExtraServs[catIdx][seIdx].forEach((d, i) => {
                                    d.esID = res.extra[i]
                                    service.extservs.push(d)
                                    vueScheduler.$forceUpdate();
                                })
                                _this.newExtraServs[catIdx][seIdx] = [];
                            }else{
                                toastr.error(res.response)
                            }
                        }, (error) => {
                            toastr.error(_unexpectedError)
                        })
                    }
                }
            },
            deleteExtraService(service, esID){
                var deleteConfirm = confirm("Are you sure you want to delete this extra service");
                if(deleteConfirm){
                    var idx = -1;
                    var temp;
                    service.extservs.forEach((es, index) => {
                        if(es.esID == esID){
                            idx = index;
                            temp = es;
                        }
                    })
                    vueScheduler.$forceUpdate();
                    if(idx >= 0){
                        service.extservs = service.extservs.filter(s => s.esID != esID)
                        makeRequest('delete_extra_service', {esID}, (res) => {
                            if(!res.error){
                                toastr.success(res.response)
                            }else{
                                service.extservs.push(temp);
                                toastr.error(res.response);
                            }
                        }, (error) => {
                            service.extservs.push(temp);
                            toastr.error(_unexpectedError);
                        })
                    }
                }
            },
            updateServiceInformation: function(service, idx){
                if(!service.serviceName.trim()){
                    toastr.error("No service name specified");
                    return;
                }
                if(service.cost < 0){
                    toastr.error("Cost of service cannot be less than zero");
                    return;
                }
                var conf = confirm("Are you sure you want to update this information?");
                if(conf){
                    var _this = this
                    this.updatingServiceInfo = true;
                    makeRequest("update_service_information", {serviceID: service.serviceID, serviceName: service.serviceName, cost: service.cost, status: service.status}, (res) => {
                        _this.updatingServiceInfo = false;
                        if(!res.error){
                            toastr.success(res.response)
                            _this.serviceNames[idx].serviceName = service.serviceName;
                        }else{
                            toastr.error(res.response)
                        }
                    }, (error) => {
                        _this.updatingServiceInfo = false;
                        toastr.error(_unexpectedError)
                    })
                }
            },
            deleteService: function(service, catIdx, seIdx){
                var conf = confirm("Are you sure you want to delete this service? this can not be undone.");
                if(conf){
                    var temp = service;
                    var catLength = this.servicecategories[catIdx].length
                    if(catLength == 1){
                        temp = this.servicecategories[catIdx]
                        delete this.servicecategories[catIdx];
                        vueScheduler.$forceUpdate();
                    }else{
                        this.servicecategories[catIdx] = this.servicecategories[catIdx].filter((s) => {
                            return s != service 
                        })
                    }
                    makeRequest('delete_service', {serviceID: service.serviceID}, (res) => {
                        if(!res.error){
                            toastr.success('Deleted service successfully');
                            this.serviceNames = this.serviceNames.filter((s) => {
                                return s.serviceID != service.serviceID;
                            })
                        }else{
                            toastr.error(res.response);
                            if(this.servicecategories[catIdx]){
                                this.servicecategories[catIdx].push(service);
                            }else{
                                this.servicecategories[catIdx] = []
                                this.servicecategories[catIdx].push(service);
                            }
                            vueScheduler.$forceUpdate();
                        }
                    }, (error) => {
                        toastr.error(_unexpectedError)
                        if(this.servicecategories[catIdx]){
                            this.servicecategories[catIdx].push(service);
                        }else{
                            this.servicecategories[catIdx] = []
                            this.servicecategories[catIdx].push(service);
                        }
                        vueScheduler.$forceUpdate();
                    })
                }
            },
            addService: function(){
                var _newService = this.newService;
                var exists = this.serviceNames.find((s) => {
                    return s.category == _newService.serviceCategoryID && _newService.serviceName.trim() == s.serviceName
                })
                if(exists){
                    alert("You already have a service with the same name and in the same category");
                    return;
                }else{
                    if(this.newService.cost >= 0){
                        var _this = this;
                        makeRequest('add_service', _newService, (res) => {
                            _this.addingService = false;
                            if(!res.error){
                                toastr.success(res.response)
                                _newService.serviceID = res.extra
                                _newService['status'] = 1
                                _newService['extservs'] = []
                                _newService['serviceTiming'] = {}
                                _newService['categoryName'] = _this.addServiceCategoryName

                                _this.newService = {serviceID: "", serviceName: "", serviceCategoryID: '', cost: '' }
                                var findServiceName = _this.serviceNames.find((s) => {
                                    return s.category == _newService.serviceCategoryID
                                })
                                if(findServiceName){
                                    _this.newExtraServs[findServiceName.catIdx][_this.servicecategories[findServiceName.catIdx].length] = [];
                                    _this.servicecategories[findServiceName.catIdx].push(_newService);
                                    _this.serviceNames.push({serviceID: _newService.serviceID, category: _newService.serviceCategoryID, serviceName: _newService.serviceName, catIdx: findServiceName.catIdx})
                                }else{
                                    var catKey = _this.addServiceCategoryName.toLowerCase();
                                    _this.newExtraServs[catKey] = [];
                                    _this.newExtraServs[catKey][0] = [];
                                    _this.servicecategories[catKey] = []
                                    _this.servicecategories[catKey].push(_newService)
                                    _this.serviceNames.push({serviceID: _newService.serviceID, category: _newService.serviceCategoryID, serviceName: _newService.serviceName, catIdx: catKey})
                                }
                            }else{
                                toastr.error(res.response)
                            }
                        }, (error) => {
                            toastr.error(_unexpectedError)
                            _this.addingService = false;
                        })
                    }else{
                        alert("Service cost must be greater than or equal to zero");
                        return;
                    }
                }
            },
            onCategoryChange: function(event){
                this.addServiceCategoryName = event.target.selectedOptions[0].text
            }
        },
        mounted(){
            var _this = this;
            var loadServices = function(){
                makeGETRequest('get_agent_services', (data) => {
                    _this.showLoader = false
                    _this.servicecategories = data;
                    Object.keys(_this.servicecategories).forEach((sss) => {
                        _this.newExtraServs[sss] = [];
                        _this.servicecategories[sss].forEach((ss, inde) => {
                            _this.newExtraServs[sss][inde] = [];
                            _this.serviceNames.push({serviceID: ss.serviceID, category: ss.serviceCategoryID, serviceName: ss.serviceName, catIdx: sss});
                        })
                    })
                    setTimeout(() => {
                        initTimePicker();
                    }, 1000);
                }, (data) => {
                    _this.showLoader = false
                });
            }
            loadServices();
            $('#item-1-3-tab').on('click', function(){
                initTimePicker();
            });
        }
    })
</script>