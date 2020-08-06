<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <?php if($this->usertypeID == USERS): ?>
                    <li class="nav-item"><a class="nav-link active" href="#item-1-1" id="item-1-1-tab" data-toggle="tab" role="tab" aria-controls="item-1-1" aria-selected="true">My Appointments</a></li>
                    <li class="nav-item"><a class="nav-link" href="#item-1-2" id="item-1-2-tab" data-toggle="tab" role="tab" aria-controls="item-1-2" aria-selected="false">My Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#item-1-3" id="item-1-3-tab" data-toggle="tab" role="tab" aria-controls="item-1-3" aria-selected="false">New Appointment</a></li>
                    <li class="nav-item"><a class="nav-link" href="#item-1-4" id="item-1-4-tab" data-toggle="tab" role="tab" aria-controls="item-1-4" aria-selected="false">Messages</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="card-body">
                <div id="nav-tabContent" class="tab-content">
                    <div id="item-1-1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="item-1-1-tab">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" type="button" id="new_apt">New Appointment</button>
                                    <script type="text/javascript">
                                        var new_apt = document.getElementById('new_apt')
                                        new_apt.addEventListener('click', function(){
                                            var newAptTab = document.getElementById('item-1-3-tab');
                                            if(newAptTab){
                                                newAptTab.click();
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                            <div id="appointments" class="row mt-5 hidden"><div class="col-12 d-flex justify-content-center align-items-center" v-if="showLoader">
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
                                        <h4>{{appoint.servName}}</h4>
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
                                                <td>Agent</td>
                                                <td>{{appoint.agentName}}</td>
                                            </tr>
                                            <tr>
                                                <td>Appointment Location</td>
                                                <td>{{appoint.address}}</td>
                                            </tr>
                                            <tr>
                                                <td>Agent Phone</td>
                                                <td>{{appoint.phone}}</td>
                                            </tr>
                                            <tr>
                                                <td>Cost</td>
                                                <td>{{appoint.totalCost}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>
                                                    <span class="badge" v-bind:class="{'badge-warning': appoint.status == 2, 'badge-info': appoint.status == 3, 'badge-success': appoint.status == 4, 'badge-danger': appoint.status == 0 || appoint.status == 1}">
                                                        <span v-if="appoint.status == 0">Declined by agent</span>
                                                        <span v-if="appoint.status == 1">Cancelled</span>
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
                                            <tr v-if="appoint.status == 2 || appoint.status == 3">
                                                <td colspan="2">
                                                    <button :disabled="appCancelling.indexOf(appoint.appointmentID) > -1" v-on:click="cancelAppointment(appoint)" class="btn btn-danger btn-block mt-3">{{appCancelling.indexOf(appoint.appointmentID) > -1 ? 'Cancelling...' : 'Cancel'}}</button>
                                                </td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="item-1-2" class="tab-pane fade" role="tabpanel" aria-labelledby="item-1-2-tab">
                        <?php echo form_open('user/update_profile', array(
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
                                                "data-bvalidator" => "required,minlen[5],maxlen[50]"
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
                        <?php echo form_open('user/change_password', array(
                            'class' => 'password-strength',
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
                        <div id="appointment-booking" class="py-3">
                            <div class="row" >
                                <div class="col-sm-12 col-md-3 app-info">
                                    <h3>{{stepHeaderValues[appointmentStep]}}</h3>
                                    <div class="mt-4" v-if="appointmentStep > 0">
                                        <h5>Summary</h5>
                                        <table class="summary-tbl">
                                            <tr>
                                                <td>Category </td>
                                                <td>{{selectedCatValue}}</td>
                                            </tr>
                                            <tr v-if="appointmentStep > 1">
                                                <td>Service </td>
                                                <td>{{appointmentData.selectedAgent.serviceName}}</td>
                                            </tr>
                                            <tr v-if="appointmentStep > 1">
                                                <td>Agent </td>
                                                <td>{{appointmentData.selectedAgent.agentName}}</td>
                                            </tr>
                                            <tr v-if="appointmentStep > 2 && appointmentData.extservs.length > 0">
                                                <td>Extras </td>
                                                <td>{{getSelectedExtras()}}</td>
                                            </tr>
                                            <tr v-if="appointmentStep > 2 && appointmentData.selectedDate">
                                                <td>Date </td>
                                                <td>{{new Date(appointmentData.selectedDate).toDateString()}}</td>
                                            </tr>
                                            <tr v-if="appointmentStep > 2 && appointmentData.selectedDate && appointmentData.selectedTime.starttime">
                                                <td>Time </td>
                                                <td>{{appointmentData.selectedTime.starttime}} - {{appointmentData.selectedTime.endtime}}</td>
                                            </tr>
                                            <tr v-if="appointmentStep > 1">
                                                <td>Total Cost </td>
                                                <td>${{appointmentData.totalCost}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-9 d-flex justify-content-center align-items-center" v-if="showLoader">
                                    <span>
                                        <i class="fa fa-spin fa-spinner"></i>
                                    </span>
                                </div>
                                <div class="col-sm-12 col-md-9" v-if="appointmentStep == 0 && !showLoader">
                                    <select name="cat" class="form-control" id="catList" v-on:change="catChange($event)" v-model="selectedCat">
                                        <?php foreach($servcats as $val): ?>
                                            <option value="<?=$val->serviceCategoryID ?>"><?=$val->categoryName ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-9 services" v-if="appointmentStep == 1 && !showLoader">
                                    <ul v-if="selectedService.length > 0">
                                        <li class="list-unstyled mb-4" v-for="service in selectedService">
                                            <div>
                                                <h4 class="service-head">{{service[0].serviceName}}</h4>
                                                <div class="serv-agents">
                                                    <div class="agent-serv" v-bind:class="{selected: appointmentData.selectedAgent.serviceID == serv_ag.serviceID}" v-on:click="selectAgent(serv_ag)" v-for="serv_ag in service">
                                                        <div class="ag-photo d-flex justify-content-center">
                                                            <img v-if="serv_ag.pp" v-bind:src="serv_ag.pp"/>
                                                            <img v-else v-bind:src="defaultUserImage"/>
                                                        </div>
                                                        <span class="name">{{serv_ag.agentName}}</span>
                                                        <span class="cost">About ${{serv_ag.cost}}</span>
                                                        <span class="extserv" v-if="serv_ag.extservs"><small>+{{serv_ag.extservs.length}} service(s)</small></span>
                                                        <div class="extserv-modal" v-if="serv_ag.extservs">
                                                            <h6>Extra Services</h6>
                                                            <table>
                                                                <tr v-for="extserv in serv_ag.extservs">
                                                                    <td><b>{{extserv.name}}</b>-   </td>
                                                                    <td>${{extserv.cost}}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                    <div class="fill-parent d-flex justify-content-center align-items-center" v-else>
                                        No services yet.
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-9" v-if="appointmentStep == 2 && !showLoader && appointmentData.selectedAgent.extservs">
                                    <ul>
                                        <li class="list-unstyled" v-for="serv in appointmentData.selectedAgent.extservs">
                                            <div class="d-flex align-items-center">
                                                <label><input type="checkbox" v-on:change="onExtraServiceChange()" :value="serv.esID" v-model="appointmentData.extservs"><span class="ml-3">{{serv.name}}  -  ${{serv.cost}}</span></label>
                                                
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-sm-12 col-md-9" v-if="appointmentStep == 3 && !showLoader">
                                    <div class="row">
                                        <div class="d-none d-md-block col-md-3"></div>
                                        <div id="app-date-picker" class="col-12 col-md-6"></div>
                                        <div class="d-none d-md-block col-md-3"></div>
                                    </div>
                                    <div class="row mt-5" v-if="selectedServiceDayTimings && selectedServiceDayTimings.length > 0">
                                        <div class="d-none d-md-block col-md-3"></div>
                                        <div class="col-12 col-md-6">
                                            <h6><b>Pick a time for <span class="app-date">{{new Date(appointmentData.selectedDate).toDateString()}}</span></b></h6>
                                            <div class="d-flex justify-content-around flex-wrap">
                                                <div class="app-time" v-bind:class="{selected: appointmentData.selectedTime.starttime == time.starttime && appointmentData.selectedTime.endtime == time.endtime}" v-on:click="setAppTime(time)" v-for="time in selectedServiceDayTimings">
                                                    {{time.starttime}} - {{time.endtime}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-none d-md-block col-md-3"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-9 d-flex align-items-center" v-if="appointmentStep == 4 && !showLoader">
                                    <div style="width: 100%">
                                        <div class="row" v-if="appointmentData.payMethod == 0 || appointmentData.payMethod == 2">
                                            <div class="d-none d-md-block col-md-3"></div>
                                            <div class="col-6 col-md-3">
                                                <div class="pay-btn"  v-on:click="appointmentData.payMethod = 1; loadCard()">
                                                    Pay Online
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="pay-btn" v-on:click="appointmentData.payMethod = 2" v-bind:class="{selected: appointmentData.payMethod == 2}">
                                                    Pay Locally
                                                </div>
                                            </div>
                                            <div class="d-none d-md-block  col-md-3"></div>
                                        </div>
                                        <div class="row" v-if="appointmentData.payMethod == 1">
                                            <div class="d-none d-md-block col-md-3"></div>
                                            <div class="col-12 col-md-6">
                                                <div class="mb-3">Demo mode, click next to continue :)</div>
                                                <div class="card-wrapper"></div>
                                                <div class="form-container mt-5 active">
                                                    <form id="card-form">
                                                        <input class="form-control" placeholder="Card number" type="tel" name="number">
                                                        <input class="form-control" placeholder="Full name" type="text" name="name">
                                                        <input class="form-control" placeholder="MM/YY" type="tel" name="expiry">
                                                        <input class="form-control" placeholder="CVC" type="number" name="cvc">
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="d-none d-md-block col-md-3"></div>
                                        </div>
                                        <div class="text-danger text-center mt-3">
                                            Amount to be paid <strong>${{appointmentData.totalCost}}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-9" v-if="appointmentStep >= 5 && !showLoader">
                                    <div>
                                        <h6>{{appointmentStep == 6 ? 'Your appointment has been scheduled successfully' : 'Please cross-check your appointment before you submit'}}</h6>
                                    </div>
                                    <div class="app-summ-container">
                                        <h6 class="summ-head">
                                            Appointment info
                                        </h6>
                                        <ul class="sum-details">
                                            <li>
                                                Date
                                                <strong>{{new Date(appointmentData.selectedDate).toDateString()}}</strong>
                                            </li>
                                            <li>
                                                Time
                                                <strong>{{appointmentData.selectedTime.starttime}}-{{appointmentData.selectedTime.endtime}}</strong>
                                            </li>
                                            <li>
                                                Agent
                                                <strong>{{appointmentData.selectedAgent.agentName}}</strong>
                                            </li>
                                            <li>
                                                Phone
                                                <strong>{{appointmentData.selectedAgent.phone}}</strong>
                                            </li>
                                            <li>
                                                Location
                                                <strong>{{appointmentData.selectedAgent.address}}</strong>
                                            </li>
                                            <li>
                                                Service
                                                <strong>{{appointmentData.selectedAgent.serviceName}}</strong>
                                            </li>
                                            <li v-if="appointmentData.extservs.length > 0">
                                                Extras
                                                <strong>{{getSelectedExtras()}}</strong>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="app-summ-container">
                                        <h6 class="summ-head">
                                            Payment Info
                                        </h6>
                                        <ul class="sum-details">
                                            <li>
                                                Payment method
                                                <strong>{{appointmentData.payMethod == 1 ? 'Card payment' : 'Pay Locally'}}</strong>
                                            </li>
                                            <li>
                                                Total
                                                <strong>${{appointmentData.totalCost}}</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3" v-if="!showLoader">
                                <div class="d-none d-md-block col-md-3"></div>
                                <div class="col-12 col-md-9 d-flex justify-content-center">
                                    <span class="cursor-pointer" v-on:click="prevStep" v-if="appointmentStep > 0 && appointmentStep < 6">
                                        <i class="fa fa-arrow-left"></i>
                                        Back
                                    </span>
                                    <span class="cursor-pointer ml-3" v-bind:class="{hidden: !canGoNext()}" v-on:click="nextStep"  v-if="appointmentStep >= 0 && appointmentStep < 6">
                                        {{appointmentStep == 5 ? 'Submit' : 'Next'}}
                                        <i class="fa fa-arrow-right"></i>
                                    </span>
                                    <span>
                                        <button class="btn btn-primary" v-on:click="done" v-if="appointmentStep == 6">Done</button>
                                    </span>
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
    // var initDatePicker = function(dateString){
    //     setTimeout(() => {
    //         $("#app-date-picker").MEC({
    //             from_monday:true, 
    //             forgetToday: true, 
    //             disablePastDays: true,
    //             selectedDate: dateString, 
    //             onDateSelected: function(date){
    //                 vueApp.setAppDate(date);
    //             }
    //         });
    //     }, 100);
    // }
    // var initCard = function(){
    //     setTimeout(() => {
    //         $('#card-form').card({
    //             container: '.card-wrapper',
    //         });
    //     }, 100);
    // }
    // var simpleDateFormat = function(date){
    //     return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
    // }
    
    // toastr.options = {
    //     "closeButton": true,
    //     "debug": false,
    //     "newestOnTop": true,
    //     "progressBar": false,
    //     "positionClass": "toast-top-right",
    //     "preventDuplicates": false,
    //     "onclick": null,
    //     "showDuration": "300",
    //     "hideDuration": "1000",
    //     "timeOut": "5000",
    //     "extendedTimeOut": "1000",
    //     "showEasing": "swing",
    //     "hideEasing": "linear",
    //     "showMethod": "fadeIn",
    //     "hideMethod": "fadeOut"
    // }
    // var toasterSuccess = function(message){
    //     toastr.success(message);
    // };
    // var toasterError = function(message){
    //     toastr.error(message);
    // };
    
</script>
<script nonce="4AEemGb0xJptoIGFP3Nd">
    var token = '<?=$this->security->get_csrf_hash() ?>';
    var tName = '<?=$this->security->get_csrf_token_name()?>';
    var defUserImage = '<?= base_url('assets/img/user.png') ?>'
    
    var vueDash = new Vue({
        el: '#appointments',
        data: {
            appointments: [],
            appCancelling: [],
            showLoader: true
        },
        methods: {
            cancelAppointment: function(appointment){
                if(appointment.status == 2 || appointment.status == 3){
                    var canacelApp = confirm("Are you sure you want to cancel this appointment? This cannot be undone.")
                    if(canacelApp){
                        this.appCancelling.push(appointment.appointmentID)
                        var _this = this;
                        _data = {appID: appointment.appointmentID}
                        
                        makeRequest("cancel_appointment", _data, (res) => {
                            _this.removeAppointmentFromCancelling(appointment.appointmentID)
                            if(!res.error){
                                toastr.success("Cancelled appointment successfully")
                                appointment.status = 1;
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
            setAppStatus(appId, status){

            },
            removeAppointmentFromCancelling(id){
                if(id){
                    this.appCancelling = this.appCancelling.filter(_id => _id != id)
                }
            }
        },
        mounted(){
            var _this = this;
            var loadAppoints = function(){
                $.ajax('get_appointments', {
                    success: function (data) {
                        _this.showLoader = false
                        _this.appointments = JSON.parse(data)
                    },
                    error: function(data){
                        _this.showLoader = false
                    }
                })
            }
            loadAppoints();
            $('#item-1-1-tab').on('click', function(){
                loadAppoints();
            })
            
            $('#appointments').removeClass('hidden');
        }
    })
    var vueApp = new Vue({
        el: '#appointment-booking',
        data: {
            appointmentData: {selectedAgent: {}, extservs: [], selectedDate: "", selectedExtServs: [], selectedTime: {}, payMethod: 0, totalCost: 0},
            appointmentStep: 0,
            stepHeader: 'Select category',
            showLoader: false,
            services: [],
            selectedCat: 1,
            selectedService: {},
            defaultUserImage: defUserImage,
            selectedAgent: {},
            stepHeaderValues: {"0": "Select Category", "1": "Select Service & Agent", "2": "Select Extra Services", "3": "Select Date & Time", "4": "Select Payment Method", "5": "Review"},
            selectedServiceDayTimings: [],
            days: ['sun','mon','tue','wed','thu','fri','sat'],
            payMethod: 0,
            appointments: [{}]
        },
        methods: {
            submit: function(){
                var _this = this;
                var _data =  {serviceID: this.appointmentData.selectedAgent.serviceID, payMethod :  this.appointmentData.payMethod, extServs: this.appointmentData.selectedExtServs, selectedDate: this.appointmentData.selectedDate, selectedTime: this.appointmentData.selectedTime.timingID}
                
                makeRequest("book_appointment", _data, (res) => {
                    _this.showLoader = false
                    if(!res.error){
                        _this.appointmentStep = 6;
                        toastr.success(res.response)
                    }else{
                        toastr.error(res.response)
                    }
                }, (error) => {
                    _this.showLoader = false
                    toastr.error("Sorry, an unexpected error occured")
                })
            },
            prevStep: function(){
                if(this.appointmentStep > 0){
                    if(this.appointmentData.payMethod == 1 && this.appointmentStep == 4){
                        this.appointmentData.payMethod = 0;
                        return;
                    }
                    this.appointmentStep -= 1;
                    if(this.appointmentStep == 2 && !this.appointmentData.selectedAgent.extservs){
                        this.appointmentStep -= 1;
                    }
                    if(this.appointmentStep == 3){
                        initDatePicker(this.appointmentData.selectedDate);
                    }
                }
            },
            nextStep: function(){
                this.showLoader = true;
                if(!this.selectedCatValue){
                    this.selectedCatValue = $('#catList').val();
                }
                if(this.appointmentStep == 0){
                    let loaded = this.loadServices();
                    if(loaded){
                        this.appointmentStep = 1;
                        this.stepHeader = "Select Service & Agent"
                    }
                    this.showLoader = false;
                }else if(this.appointmentStep == 1){
                    if(this.appointmentData.selectedAgent.agentID){
                        var extS = this.appointmentData.selectedAgent.extservs
                        this.appointmentStep = extS && extS.length > 0 ? 2 : 3;
                        this.stepHeader = extS && extS.length > 0 ? "Select Extra Services" : "Select Date & Time";
                        if(this.appointmentStep == 3){
                            initDatePicker()
                        }
                    }
                    this.showLoader = false;
                }else if(this.appointmentStep == 2){
                    this.appointmentStep = 3;
                    this.showLoader = false;
                    initDatePicker()
                }else if(this.appointmentStep == 3){
                    if(this.appointmentData.selectedTime.starttime){
                        this.appointmentStep = 4;
                    }
                    this.showLoader = false;
                }else if(this.appointmentStep == 4){
                    if(this.appointmentData.payMethod == 1 || this.appointmentData.payMethod == 2){
                        this.appointmentStep = 5;
                    }
                    this.showLoader = false;
                }else if(this.appointmentStep == 5){
                    this.submit();
                }else{
                    this.showLoader = false;
                }
            },
            loadServices: async function(){
                var _this = this;
                var cat = this.services.find((s) => {
                        return s.category == this.selectedCat;
                    });
                if(!cat){
                    await $.ajax('get_grouped_services/'+this.selectedCat, {
                        success: function (data) {
                            _this.selectedService = [];
                            var d = JSON.parse(data);
                            Object.keys(d).forEach((k) =>{
                                _this.selectedService.push(d[k])
                            })
                            _this.services.push({category: _this.selectedCat, services: _this.selectedService});
                            return true;
                        },
                        error: function(data){
                            console.error(data)
                            return false
                        }
                    } )
                }else{
                    this.selectedService = cat.services
                    return true
                }
            },
            catChange: function(event){
                this.selectedCatValue = event.target.selectedOptions[0].text;
                this.appointmentData['selectedCat'] = event.target.value
                this.appointmentData['selectedCatValue'] = this.selectedCatValue;
                this.appointmentData.selectedAgent = {};
                this.appointmentData.extservs = [];
            },
            selectAgent: function(agent){
                this.appointmentData.selectedAgent = agent;
                this.appointmentData.totalCost = agent.cost
                this.appointmentData.extservs = [];
                this.appointmentData.selectedDate = "";
                this.appointmentData.selectedTime = {};
                this.selectedServiceDayTimings = [];
            },
            setAppDate: function(date){
                try {
                    var timings = this.appointmentData.selectedAgent.serviceTiming;
                    this.selectedServiceDayTimings = timings[this.days[date.getDay()]];
                    var dd = simpleDateFormat(date);
                    if(dd != this.appointmentData.selectedDate){
                        this.appointmentData.selectedDate = dd;
                        this.appointmentData.selectedTime = {};
                    }
                } catch (error) {
                }
            },
            setAppTime: function(time){
                this.appointmentData.selectedTime = time;
            },
            getSelectedExtras: function(){
                var servNames = [];
                this.appointmentData.extservs.forEach((id) => {
                    var serv = this.appointmentData.selectedAgent.extservs.find((ex) => {
                        return ex.esID == id;
                    });
                    if(serv){
                        servNames.push(serv.name);
                    }
                })
                return servNames.join(',');
            },
            loadCard: function(){
                initCard();
            },
            onExtraServiceChange: function(){
                var extServsCost = 0;
                if(this.appointmentData.extservs.length > 0){
                    this.appointmentData.selectedExtServs = [];
                    this.appointmentData.extservs.forEach((id) => {
                        var serv = this.appointmentData.selectedAgent.extservs.find((ex) => {
                            return ex.esID == id;
                        });
                        if(serv){
                            extServsCost += parseFloat(serv.cost);
                            this.appointmentData.selectedExtServs.push(serv.esID)
                        }
                    })
                }
                this.appointmentData.totalCost = parseFloat(this.appointmentData.selectedAgent.cost) + parseFloat(extServsCost);
            },
            canGoNext: function(){
                if(this.appointmentStep == 0){
                    let loaded = this.loadServices();
                    if(loaded){
                        return true;
                    }
                }else if(this.appointmentStep == 1){
                    if(this.appointmentData.selectedAgent.agentID){
                        return true;
                    }
                    this.showLoader = false;
                }else if(this.appointmentStep == 2){
                        return true;
                }else if(this.appointmentStep == 3){
                    if(this.appointmentData.selectedTime.starttime){
                        return true;
                    }
                }else if(this.appointmentStep == 4){
                    if(this.appointmentData.payMethod == 1 || this.appointmentData.payMethod == 2){
                        return true;
                    }
                }else if(this.appointmentStep == 5){
                        return true;
                }
            },
            done: function(){
                var aptTab = document.getElementById('item-1-1-tab');
                if(aptTab){
                    aptTab.click();
                    this.appointmentStep = 0;
                    this.appointmentData = {selectedAgent: {}, extservs: [], selectedDate: "", selectedExtServs: [], selectedTime: {}, payMethod: 0, totalCost: 0};
                }
            }
        }
    });
</script>