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