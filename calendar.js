/* * * * * * * * * * * * * * * * * * * *\
 *               Module 4              *
 *      Calendar Helper Functions      *
 *                                     *
 *        by Shane Carr '15 (TA)       *
 *  Washington University in St. Louis *
 *    Department of Computer Science   *
 *               CSE 330S              *
 *                                     *
 *      Last Update: October 2017      *
\* * * * * * * * * * * * * * * * * * * */

/*  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

(function () {
	"use strict";

	/* Date.prototype.deltaDays(n)
	 * 
	 * Returns a Date object n days in the future.
	 */
	Date.prototype.deltaDays = function (n) {
		// relies on the Date object to automatically wrap between months for us
		return new Date(this.getFullYear(), this.getMonth(), this.getDate() + n);
	};

	/* Date.prototype.getSunday()
	 * 
	 * Returns the Sunday nearest in the past to this date (inclusive)
	 */
	Date.prototype.getSunday = function () {
		return this.deltaDays(-1 * this.getDay());
	};

}());

/** Week
 * 
 * Represents a week.
 * 
 * Functions (Methods):
 *	.nextWeek() returns a Week object sequentially in the future
 *	.prevWeek() returns a Week object sequentially in the past
 *	.contains(date) returns true if this week's sunday is the same
 *		as date's sunday; false otherwise
 *	.getDates() returns an Array containing 7 Date objects, each representing
 *		one of the seven days in this month
 */
function Week(initial_d) {
	"use strict";

	this.sunday = initial_d.getSunday();
		
	
	this.nextWeek = function () {
		return new Week(this.sunday.deltaDays(7));
	};
	
	this.prevWeek = function () {
		return new Week(this.sunday.deltaDays(-7));
	};
	
	this.contains = function (d) {
		return (this.sunday.valueOf() === d.getSunday().valueOf());
	};
	
	this.getDates = function () {
		var dates = [];
		for(var i=0; i<7; i++){
			dates.push(this.sunday.deltaDays(i));
		}
		return dates;
	};
}

/** Month
 * 
 * Represents a month.
 * 
 * Properties:
 *	.year == the year associated with the month
 *	.month == the month number (January = 0)
 * 
 * Functions (Methods):
 *	.nextMonth() returns a Month object sequentially in the future
 *	.prevMonth() returns a Month object sequentially in the past
 *	.getDateObject(d) returns a Date object representing the date
 *		d in the month
 *	.getWeeks() returns an Array containing all weeks spanned by the
 *		month; the weeks are represented as Week objects
 */
function Month(year, month) {
	"use strict";
	
	this.year = year;
	this.month = month;
	
	this.nextMonth = function () {
		return new Month( year + Math.floor((month+1)/12), (month+1) % 12);
	};
	
	this.prevMonth = function () {
		return new Month( year + Math.floor((month-1)/12), (month+11) % 12);
	};
	
	this.getDateObject = function(d) {
		return new Date(this.year, this.month, d);
	};
	
	this.getWeeks = function () {
		var firstDay = this.getDateObject(1);
		var lastDay = this.nextMonth().getDateObject(0);
		
		var weeks = [];
		var currweek = new Week(firstDay);
		weeks.push(currweek);
		while(!currweek.contains(lastDay)){
			currweek = currweek.nextWeek();
			weeks.push(currweek);
		}
		
		return weeks;
	};
}

// On content load, set month to display
document.addEventListener("DOMContentLoaded", setMonth, false);

// If you click on table cell, display all events (names, time, location) associated with the date from that cell
document.addEventListener("DOMContentLoaded", function()
{
    $("table td").click(function()
    {
        let d = parseInt($(this).text());
        let month = current_month.month;
        let year = current_month.year;
        let first_day = 1;
        for (let i = 1; i < 36; i++)
        {
            if (parseInt(document.getElementById(i).textContent) == first_day)
            {
                first_day++;
                if (i == parseInt(this.id))
                {
                    // Current month
                    break;
                }
            }
            else if (first_day == 1)
            {
                if (i == parseInt(this.id))
                {
                    // Previous month
                    month = current_month.prevMonth().month;
                    year = current_month.prevMonth().year;
                    break;
                }
            }
            else
            {
                if (i == parseInt(this.id))
                {
                    // Next month
                    month = current_month.nextMonth().month;
                    year = current_month.nextMonth().year;
                    break;
                }
            }
        }

        let m = month;
        let y = year;
        document.getElementById("displayEvent").innerHTML = "You must be logged in to view events";
        $("#eventDisplayBlock").show();
        getEvent(d, m, y);
    })

    // Jump to specific date on calendar
    document.getElementById("jump_date").addEventListener("click", jumpDate, false);

    // Jump to present month
    document.getElementById("jump_curr").addEventListener("click", presMonth, false);

    // Buttons to change to previous or next month
    document.getElementById("prev_month").addEventListener("click", pastMonth, false);
    document.getElementById("next_month").addEventListener("click", futureMonth, false);

    // Add event button
    document.getElementById("addEventBtn").addEventListener("click", addEvent, false);

    // Delete event button
    document.getElementById("delete_btn").addEventListener("click", deleteEvent, false);

    // Edit events
    document.getElementById("edit_btn").addEventListener("click", updateEdit, false);
    document.getElementById("change_btn").addEventListener("click", changeEvent, false);
}, false);

// Set current month
let curr_month = new Date();
let current_month = new Month(curr_month.getFullYear(), curr_month.getMonth()); 
let current_month_copy = current_month;

// Sets the current month with appropriate dates
function setMonth()
{
    // Set current month and year displayed
    document.getElementById("curr_month").innerHTML = (current_month.month + 1) + " / " + current_month.year;

    // Reset event list
    document.getElementById("eventList").innerHTML = "";

    // Set start index associated with ids in calendar.html
    let index = 1;
    let first_day = 1;

    // Grab weeks from current month
    let weeks = current_month.getWeeks();

    // For every week in the current month, grab the date number associated and associate with appropriate id index
    for (let week in weeks)
    {
        let days = weeks[week].getDates();
        for (let day in days)
        {
            if (days[day].getDate() == first_day)
            {
                // Bold only the days from current month
                document.getElementById(index).innerHTML = "<strong>" + days[day].getDate() + "</strong>"; 
                first_day++;

                // Color if event scheduled by user is logged that day
                checkMonthEvents((current_month.month + 1), days[day].getDate(), current_month.year, index);

                // Add any events for this day
                selectMonthEvents((current_month.month + 1), days[day].getDate(), current_month.year, index);
            }
            else if (first_day == 1) // Entries to left of first day of current month are from previous month
            {
                document.getElementById(index).innerHTML = days[day].getDate(); 

                // Color if event scheduled by user is logged that day
                checkMonthEvents(current_month.prevMonth().month + 1, days[day].getDate(), current_month.prevMonth().year, index);

                // Add any events for this day
                selectMonthEvents(current_month.prevMonth().month + 1, days[day].getDate(), current_month.prevMonth().year, index);
            }
            else // Entries to right of last day of current month are from next month
            {
                // Color if event scheduled by user is logged that day
                checkMonthEvents(current_month.nextMonth().month + 1, days[day].getDate(), current_month.nextMonth().year, index);

                // Add any events for this day
                selectMonthEvents(current_month.nextMonth().month + 1, days[day].getDate(), current_month.nextMonth().year, index);

                // Days from previous and next month (if displayed) are not bolded
                document.getElementById(index).innerHTML = days[day].getDate(); 
            }
            index++;
        }
    }

    // In case month can be represented in 4 weeks (i.e. February 2015) where 5th row is next month
    let next_month = current_month.nextMonth();
    let next_week = next_month.getWeeks();
    let next_week_days = next_week[0].getDates();
    let next_index = 0;
    while(index <= 35)
    {
        checkMonthEvents(next_month.month + 1, next_week_days[next_index].getDate(), next_month.year, index);
        document.getElementById(index).innerHTML = next_week_days[next_index].getDate();
        next_index++;
        index++;
    }
};

// Checks a specific date to see if logged in user has event scheduled and colors that cell on table
function checkMonthEvents(month, day, year, id)
{    
    // Make a URL-encoded string for passing POST data:
    const data = { 'month': month, 'day' : day, 'year' : year};

    fetch("colorEvent.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data =>
            {
                if (data.success)
                {
                    document.getElementById(id).style.color = "red"; // if there is an event scheduled, color text to red.
                }
                else
                {
                    document.getElementById(id).style.color = "black"; // if there is no event scheduled, color text to black.
                }
            })
        .catch(err => console.error(err));
};

// Checks a specific date to see if logged in user has event(s) scheduled and adds them to <select> tag in calendar.php to edit / delete
function selectMonthEvents(month, day, year)
{
    // Make a URL-encoded string for passing POST data:
    const data = { 'month': month, 'day' : day, 'year' : year};

    fetch("select_event.php", {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data =>
        {
            if (data.success)
            {
                // Append events to <select id = "eventList">$eventName, $eventID, $month, $day, $year, $time
                data.events.forEach(event => document.getElementById("eventList").innerHTML += "<option value=" + event[1] + ">" + event[0] + " (" + event[2] + "/" + event[3] + "/" + event[4] + " at " + event[5] + ")" + "</option>");
            }
        })
    .catch(err => console.error(err));
}

// Associated with button that increments month
function futureMonth()
{
    // It was found that clicking on the button really quickly can cause ajax requests to overlap (multiple at same time) 
    // for displaying event lists so we prevent the button from being pressed for a fraction of a second to allow ajax requests to not overlap
    document.getElementById("next_month").removeEventListener("click", futureMonth, false);
    setTimeout(ResetFuture, 300); // Delay made up by fact user can just jump to desired date

    // Update Values
    current_month = current_month.nextMonth();
    document.getElementById("displayEvent").innerHTML = "";
    document.getElementById("eventList").innerHTML = "";
    setMonth();
    ResetUpdate();
};

// Associated with button that decrements month
function pastMonth()
{
    // It was found that clicking on the button really quickly can cause ajax requests to overlap (multiple at same time) 
    // for displaying event lists so we prevent the button from being pressed for a fraction of a second to allow ajax requests to not overlap
    document.getElementById("prev_month").removeEventListener("click", pastMonth, false);
    setTimeout(ResetPast, 300); // Delay made up by fact user can just jump to desired date

    // Update Values
    current_month = current_month.prevMonth();
    document.getElementById("displayEvent").innerHTML = "";
    document.getElementById("eventList").innerHTML = "";
    setMonth();
    ResetUpdate();
};

// Jump to present month
function presMonth()
{
    document.getElementById("jump_curr").removeEventListener("click", presMonth, false);
    setTimeout(ResetPresMonth, 500);
    current_month = current_month_copy;
    setMonth();
    ResetUpdate();
}

// Reset presMonth so that you can't spam it
function ResetPresMonth()
{
    document.getElementById("jump_curr").addEventListener("click", presMonth, false);
}

// This and ResetPast() addEventListener to calendar previous and next month buttons
function ResetFuture()
{
    document.getElementById("next_month").addEventListener("click", futureMonth, false);
}

function ResetPast()
{
    document.getElementById("prev_month").addEventListener("click", pastMonth, false);
}

// Adds events to database and lets user know if it was added successfully or not
function addEvent(){
    let eventTitle = $("#addEventTitle").val();
    let eventDate = $("#addEventDate").val();
    let eventTime = $("#addEventTime").val();
    let eventLoc = $("#addEventLoc").val();
    let group = $("#addGroupevent").val();

    const data = {"title": eventTitle,"date" : eventDate, "time" : eventTime, "location" : eventLoc, "group" : group, "token" : window.token};

    fetch("addEvent.php", {
        method:"POST",
        body: JSON.stringify(data),
        headers:{'content-type': 'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        if (data.success)
        {
            alert(data.message);
            $("#addEvent").hide(); // if event is added succesfully, hide the add event form again.
            setMonth();
        }
        else
        {
            alert("Event failed to add: " + data.message);
        }

        // Reset all values in the input field.
        $("#addEventTitle").val('');
        $("#addEventDate").val('');
        $("#addEventTime").val('');
        $("#addEventLoc").val('');
        $("#addGroupevent").val('');
    })
    .catch(error => console.error('Error:',error))
}

// Get all events of specific date and list them out
function getEvent(day, mon, year){
    let d = day;
    let m = mon + 1;
    let y = year;
    const data = {"day" : d, "month" : m, "year" : y};

    fetch ("eventDisplay.php", {
        method: "POST",
        body:JSON.stringify(data),
        headers: {'content-type':'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        if(data.success)
        {
            // Display all events associated with clicked on date
            document.getElementById("displayEvent").innerHTML = "<br><strong>Events for " + m + "/" + d + "/" + y + "</strong><br>";
            data.events.forEach(event => document.getElementById("displayEvent").innerHTML += event + "<br>");
        } 
    })
    .catch(error => console.error('Error:', error))
}

// Delete selected event from dropdown menu
function deleteEvent()
{
    let eventID = $("#eventList").val();
    const data = {"id" : eventID, "token" : window.token};

    fetch ("delete_event.php", {
        method: "POST",
        body:JSON.stringify(data),
        headers: {'content-type':'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        if(data.success)
        {
            // Successfully deleted event
            alert("Event deleted!");
            setMonth();
            ResetUpdate();
        } 
        else
        {
            alert("Event failed to be deleted");
        }
    })
    .catch(error => console.error('Error:', error))
}

// Fill in input fields for edit event form with corresponding values from event the user wants to edit.
function updateEdit()
{
    let eventID = parseInt(document.getElementById("eventList").value);

    if (Object.is(NaN, eventID))
    {
        ResetUpdate();
    }
    else
    {
        const data = {"id" : eventID, "token" : window.token};
        fetch ("edit_display.php", {
            method: "POST",
            body:JSON.stringify(data),
            headers: {'content-type':'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if(data.success)
            {
                // Successfully display event
                document.getElementById("event_title").value = data.event_name;
                document.getElementById("event_date").value = data.year + "-" + data.month + "-" + data.day;
                document.getElementById("event_time").value = data.time;
                document.getElementById("event_loc").value = data.loc;
                document.getElementById("event_ID").value = eventID;
            } 
            else
            {
                ResetUpdate();
            }
        })
        .catch(error => console.error('Error:', error))
    }
}

// If event is selected, any changes you make will show up (plus you will be notified)
function changeEvent()
{
    let eventID = parseInt(document.getElementById("event_ID").value);
    if (Object.is(NaN, eventID))
    {
        // If id = NaN, then nothing happens
        alert("Cannot be edited: No event selected");
    }
    else
    {
        let eventTitle = $("#event_title").val();
        let eventDate = $("#event_date").val();
        let eventTime = $("#event_time").val();
        let eventLoc = $("#event_loc").val();

        // Else call change_event.php
        const data = {"id" : eventID, "title": eventTitle, "date" : eventDate, "time" : eventTime, "location" : eventLoc, "token" : window.token};
        fetch ("change_event.php", {
            method: "POST",
            body:JSON.stringify(data),
            headers: {'content-type':'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if(data.success)
            {
                // Successfully display event
                alert("Event successfully edited");
            } 
            else
            {
                alert("Event failed to be edited: " + data.message);
            }
        })
        .catch(error => console.error('Error:', error))
    }
    
    // Remove input
    $("#event_ID").val('');
    $("#event_title").val('');
    $("#event_date").val('');
    $("#event_time").val('');
    $("#event_loc").val('');
    setMonth();
}

// Jumps to desired month and year on calendar
function jumpDate()
{
    let date_dest = $("#jumpDate").val();
    let index = date_dest.indexOf("-");
    if (index != -1)
    {
        current_month = new Month(parseInt(date_dest.substring(0, index)), parseInt(date_dest.substring(index + 1) - 1));
        setMonth();
        ResetUpdate();
    }
    else
    {
        alert("Need to fill out entire date field to use.");
    }

    ResetUpdate();
}

// Reset all event input field value.
function ResetUpdate()
{
    document.getElementById("event_title").value = "";
    document.getElementById("event_date").value = "";
    document.getElementById("event_time").value = "";
    document.getElementById("event_loc").value = "";
    document.getElementById("event_ID").value = "";
}