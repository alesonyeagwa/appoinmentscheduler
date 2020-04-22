Object.defineProperty(window, "Cookies", {
    get: function() {
        return document.cookie.split(';').reduce(function(cookies, cookie) {
            cookies[cookie.split("=")[0].trim()] = unescape(cookie.split("=")[1]);
            return cookies
        }, {});
    }
});
var _unexpectedError = "Sorry, an unexpected error occured";

var makeRequest = function(url, datax, callback, errorCallback){
    var _data = datax
    var tName = 'x19122934';
    _data[tName] = Cookies[tName];
    $.ajax({
        type: 'POST',
        url: url,
        data: _data,
        dataType: "json",
        success: function (data) {
            if(callback){
                callback(data);
            }
            $("[name='"+ tName +"']").attr('value', Cookies[tName]);
        },
        error: function(data){
            if(errorCallback){
                errorCallback(data);
            }
            $("[name='"+ tName +"']").attr('value', Cookies[tName]);
        }
    })
} 
var makeGETRequest = function(url, callback, errorCallback){
    $.ajax(url, {
        dataType: "json",
        success: function (data) {
            if(callback){
                callback(data);
            }
            $("[name='"+ tName +"']").attr('value', Cookies[tName]);
        },
        error: function(data){
            if(errorCallback){
                errorCallback(data);
            }
            $("[name='"+ tName +"']").attr('value', Cookies[tName]);
        }
    })
} 
Vue.config.errorHandler = (err, vm, info) => {
    // err: error trace
    // vm: component in which error occured
    // info: Vue specific error information such as lifecycle hooks, events etc.
    
    // TODO: Perform any custom logic or log to server
    makeRequest(website + 'logevent', {message: err}, (res) => {
    }, (error) => {
    })
  
};
window.onerror = function(message, source, lineno, colno, error) {
    // TODO: write any custom logic or logs the error
    makeRequest(website + 'logevent', {message}, (res) => {
    }, (error) => {
    })
    //return true
};
var initTimePicker = function(){
    $('.time-picker').clockTimePicker({

    });
}
var initDatePicker = function(dateString){
    setTimeout(() => {
        $("#app-date-picker").MEC({
            from_monday:true, 
            forgetToday: true, 
            disablePastDays: true,
            selectedDate: dateString, 
            onDateSelected: function(date){
                vueApp.setAppDate(date);
            }
        });
    }, 100);
}
var initCard = function(){
    setTimeout(() => {
        $('#card-form').card({
            container: '.card-wrapper',
        });
    }, 100);
}
var simpleDateFormat = function(date){
    return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
}

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
var toasterSuccess = function(message){
    toastr.success(message);
};
var toasterError = function(message){
    toastr.error(message);
};