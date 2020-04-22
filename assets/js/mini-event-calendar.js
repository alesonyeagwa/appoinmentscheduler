(function( $ ) {
	var calenderTpl = `
		<div id="calTitle">
			<button type="button" class="month-mover prev">
				<svg fill="#FFFFFF" height="30" viewBox="0 0 24 24" width="30" xmlns="http://www.w3.org/2000/svg">
					<path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
				</svg>
			</button>
			<div id="monthYear"></div>
			<button type="button" class="month-mover next">
				<svg fill="#FFFFFF" height="30" viewBox="0 0 24 24" width="30" xmlns="http://www.w3.org/2000/svg">
					<path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
				</svg>
			</button>
		</div>
		<div>
			<div id="calThead"></div>
			<div id="calTbody"></div>
		</div>
		<div id="calTFooter">
			<h3 id="eventTitle">No events today.</h3>
			<a href="javascript:void(0);" id="calLink">ALL EVENTS</a>
		</div>
	`;
	var weekDaysFromSunday = '<div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div>';
	var weekDaysFromMonday = '<div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div><div>S</div>';
	var shortMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"];
	var selectedDate;

    $.fn.miniEventCalendar = $.fn.MEC = function(options) {
    	var settings = $.extend({
			calendar_link : "",
			events: [],
			forgetToday: false,
			disablePastDays: false,
			from_monday: false,
			selectedDate: null,
			onMonthChanged: null,
			onDateSelected: null
        }, options );

		var miniCalendar = this;

        miniCalendar.addClass('mini-cal').html(calenderTpl);

		var thead = miniCalendar.find("#calThead");
		var tbody = miniCalendar.find("#calTbody");
		var calTitle = miniCalendar.find("#monthYear");
		var calFooter = miniCalendar.find("#calTFooter");
        var eventTitle = miniCalendar.find("#eventTitle");
		var eventsLink = miniCalendar.find("#calLink");

		var today = new Date();
		var curMonth = today.getMonth();
		var curYear = today.getFullYear();

        eventTitle.text("No events today.");
		eventsLink.text("ALL EVENTS");
		eventsLink.attr("href", settings.calendar_link);

		if(settings.from_monday)
			thead.html(weekDaysFromMonday);
		else
			thead.html(weekDaysFromSunday);

		if(!settings.calendar_link.length && !settings.events.length)
			calFooter.css("display", "none");

		miniCalendar.find(".month-mover").each(function(){
			var mover = $(this);
			mover.bind("click", function(e){
				e.preventDefault();
				if(mover.hasClass("next"))
					viewNextMonth();
				else
					viewPrevMonth();
			});
		});

		miniCalendar.on("click touchstart", ".a-date", function(e){
			e.preventDefault(); 
			$(".a-date").removeClass('focused');
		    if(!$(this).hasClass('blurred') || $(this).hasClass('future')){
				showEvent($(this).data('event'));
				$(this).focus();
				$(this).addClass('focused');
				if(settings.onDateSelected){
					var date = new Date($(this).attr('data-date'));
					if(date != 'Invalid Date')
						selectedDate = date;
						settings.onDateSelected(date);
				}
			}
		});

		function populateCalendar(month, year, onInit) {
			tbody.html("");
			calTitle.text(shortMonths[month] + " " + year);
			eventTitle.text("Click day to see event");
			eventsLink.text("All Events");
			eventsLink.attr("href", "#");


			curMonth = month;
			curYear = year;

			var ldate = new Date(year, month);
			var dt = new Date(ldate);
			var weekDay = dt.getDay();

			if(settings.from_monday)
				weekDay = dt.getDay() > 0 ? dt.getDay() - 1 : 6;

			if(ldate.getDate() === 1)
				tbody.append(lastDaysOfPrevMonth(weekDay));

			var stillOnDaysBeforeToday = true;
			while (ldate.getMonth() === month) {
     			dt = new Date(ldate);

				var isToday = areSameDate(ldate, new Date());
				var today = new Date();
				if(isToday || (month > today.getMonth() && year >= today.getFullYear())){
					stillOnDaysBeforeToday = false;
				} 
     			var event = null;
     			var eventIndex = settings.events.findIndex(function(ev) {
		     		return areSameDate(dt, new Date(ev.date));
		     	});

		        if(eventIndex != -1){
		        	event = settings.events[eventIndex];

		        	if(onInit && isToday)
		        		showEvent(event);
		        }
				var blur = stillOnDaysBeforeToday && settings.disablePastDays;
				var future = ldate - today > 0 ? true : false;
     			tbody.append(dateTpl(blur, ldate, isToday, event, onInit && isToday, future));

     			ldate.setDate(ldate.getDate() + 1);

     			var bufferDays = 43 - miniCalendar.find(".a-date").length;

		        if(ldate.getMonth() != month){
		        	for(var i = 1; i < bufferDays; i++){
						tbody.append(dateTpl(true, new Date(ldate.getFullYear(), ldate.getMonth(), i), null, null, null, true));
					}
				}
			}
			 
			if(settings.onMonthChanged){
				settings.onMonthChanged(month, year);
			}

			if(selectedDate){
				$("[data-date = '"+ simpleDateFormat(selectedDate) +"']").trigger('click');
			}
 		}

 		function lastDaysOfPrevMonth(day){
 			if(curMonth > 0){
				var monthIdx = curMonth - 1;
				var yearIdx = curYear;
			}
			else{
     			if(curMonth < 11){
     				var monthIdx = 0;
     				var yearIdx = curYear + 1;
     			}else{
     				var monthIdx = 11;
     				var yearIdx = curYear - 1;
     			}
     		}
     		
     		var prevMonth = getMonthDays(monthIdx, yearIdx);
     		var lastDays = "";
			for (var i = day; i > 0; i--){
				var d = new Date(yearIdx, monthIdx, prevMonth[prevMonth.length - i]);
				var future = d - new Date() > 0 ? true : false;
				lastDays += dateTpl(true, d, null, null, null, future);
			}

        	return lastDays;
 		}

		function dateTpl(blurred, date, isToday, event, isSelected, futureDay){
			var tpl = "<div class='a-date blurred' data-date='"+ simpleDateFormat(date) +"'><span>"+date.getDate()+"</span></div>";
			if(futureDay){
				tpl = "<button type='button' class='a-date future blurred' data-date='"+ simpleDateFormat(date) +"'><span>"+date.getDate()+"</span></button>";
			}

			if(!blurred){
				var hasEvent = event && event !== null;
		        var cls = isToday && !settings.forgetToday ? "current " : "";
		        cls += futureDay ? "future " : "";
		        cls += hasEvent && isSelected ? "focused " : "";
		        cls += hasEvent ? "event " : "";
		        
		        var tpl ="<button type='button' class='a-date "+cls+"' data-date='"+ simpleDateFormat(date) +"' data-event='"+JSON.stringify(event)+"'><span>"+date.getDate()+"</span></button>";
			}

			return tpl;
		}

		function simpleDateFormat(date){
			return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
		}

		function showEvent(event){
			if(event && event !== null && event !== undefined){
				eventTitle.text(event.title);
				eventsLink.text("VIEW EVENT");
				eventsLink.attr("href", event.link);
			}else{
				eventTitle.text("No events on this day.");
				eventsLink.text("ALL EVENTS");
				eventsLink.attr("href", settings.calendar_link);
			}
		}

		function viewNextMonth(){
			var nextMonth = curMonth < 11 ? curMonth + 1 : 0;
			var nextYear = curMonth < 11 ? curYear : curYear + 1;

			populateCalendar(nextMonth, nextYear);
		}

		function viewPrevMonth(){
			var today = new Date();
			var prevMonth = curMonth > 0 ? curMonth - 1 : 11;
			var prevYear = curMonth > 0 ? curYear : curYear - 1;
			if(settings.disablePastDays && (prevYear < today.getFullYear() || prevMonth < today.getMonth())){
				return;
			}
			populateCalendar(prevMonth, prevYear);
		}

		function areSameDate(d1, d2) {
			return d1.getFullYear() == d2.getFullYear()
		        && d1.getMonth() == d2.getMonth()
		        && d1.getDate() == d2.getDate();
		}

		function getMonthDays(month, year) {
			var date = new Date(year, month, 1);
			var days = [];
			while (date.getMonth() === month) {
				days.push(date.getDate());
				date.setDate(date.getDate() + 1);
			}
			return days;
		}

		populateCalendar(curMonth, curYear, true);

		if(settings.selectedDate){
			var selDate = new Date(settings.selectedDate);
			if(selDate != "Invalid Date"){
				selectedDate = selDate;
				populateCalendar(selDate.getMonth(), selDate.getFullYear());
			}
		}

        return miniCalendar;
    };
 
}( jQuery ));