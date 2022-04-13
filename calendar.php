<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="calendar.css">
    <title> Calendar </title>
    <script src = "calendar.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
    <!-- Welcome message -->
    <div id = "welcome_guest" class = "welcomemsg"> Welcome Guest! </div>
    <div id = "welcome_reg" class = "welcomemsg"></div>

    <br>

    <!-- Login, password, register user -->
    <div id = "login_form">
        <strong> Login User </strong> <br>
        Username: <input type = "text" id = "user_input"/>
        Password: <input type = "password" id = "pass_input"/>
        <button id = "login_btn" type = "button">Login</button>
    </div>

    <!-- Logout -->
    <div id = "logout_form">
        <button id = "logout_btn" type = "button">Logout</button>
    </div>

    <!-- Button to prompt register user form -->
    <button id = "register_btn" type = "button">Register a New User</button>

    <!-- Register new user form -->
    <div id = "registerNew">
        <strong> Register New User </strong> <br>
        Username: <input type = "text" id = "reg_user"/>
        Password: <input type = "password" id = "reg_pass"/>
        <button id = "create_btn" type = "button">Create</button>
    </div>

   

    <!-- Two buttons to go to next or previous month + Label for current month displayed -->
    <div id="topHeader">
        <button id = "prev_month" class="top"> &#8592; </button>
        <div id = "curr_month" class="top" style="font-weight:bold;font-size:20px;"></div>
        <button id = "next_month" class="top"> &#8594; </button>
        <input type="month" id="jumpDate" class = "top"/>
        <button id = "jump_date" class = "top"> Jump </button>
        <button id = "jump_curr" class = "top"> Return to present month </button>
    </div>

    <!-- Displayed month represented by table displaying -->
    <table>
        <tr>
            <th>Sunday</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
        </tr>
        <tr>
            <td id = '1'></td>
            <td id = '2'></td>
            <td id = '3'></td>
            <td id = '4'></td>
            <td id = '5'></td>
            <td id = '6'></td>
            <td id = '7'></td>
        </tr>
        <tr>
            <td id = '8'></td>
            <td id = '9'></td>
            <td id = '10'></td>
            <td id = '11'></td>
            <td id = '12'></td>
            <td id = '13'></td>
            <td id = '14'></td>
        </tr>
        <tr>
            <td id = '15'></td>
            <td id = '16'></td>
            <td id = '17'></td>
            <td id = '18'></td>
            <td id = '19'></td>
            <td id = '20'></td>
            <td id = '21'></td>
        </tr>
        <tr>
            <td id = '22'></td>
            <td id = '23'></td>
            <td id = '24'></td>
            <td id = '25'></td>
            <td id = '26'></td>
            <td id = '27'></td>
            <td id = '28'></td>
        </tr>
        <tr>
            <td id = '29'></td>
            <td id = '30'></td>
            <td id = '31'></td>
            <td id = '32'></td>
            <td id = '33'></td>
            <td id = '34'></td>
            <td id = '35'></td>
        </tr>
    </table>
    
    <br> 
    <!-- Section here for displaying user events of current month -->
    <div id ="eventDisplayBlock">
        <div id = "displayEvent" style = "font-size:20px;"> </div> <br> <!-- Displays event(s) for specific date --> 
    </div>

    <!-- Section here to add event -->
    <button id = "addtrigger_btn" type = "button">Click to add a new event</button>
    <div id="addEvent" style="font-size:15px;">
        <strong>Add Event</strong> <br>
        Title: <input type="text" id="addEventTitle"/>
        Date: <input type="date" id="addEventDate"/>
        Time (EX: 0000 = 12:00 AM, 2359 = 11:59 PM): <input type="text" id="addEventTime"/><br>
        Location (optional): <input type="text" id = "addEventLoc"/><br>
        Enter Group (separate by comma): <input type="text" id = "addGroupevent"/><br>
        <button id = "addEventBtn" type = "button">Add Event</button>
    </div>

    <!-- Display events to delete and be able to delete the event. -->
    <div id = "event_list" style = "margin-top:20px;"> <!-- Displays event(s) for entire month -->
            <select id = "eventList"> </select>
            <button id = "delete_btn" type = "button"> Delete </button>
    </div>


    <!-- Section for displaying contents of any event in event_list so you can edit them -->
    <div id = "edit_event">
        <br>
        <button id = "edit_btn" type = "button"> Edit Selected Event </button> <br>
        Title: <input type = "text" id = "event_title"/> <br>
        Date: <input type = "date" id = "event_date"/> <br>
        Time (EX: 0000 = 12:00 AM, 2359 = 11:59 PM): <input type = "text" id = "event_time"/> <br>
        Location (optional): <input type = "text" id = "event_loc"/> <br>
        <button id = "change_btn" type = "button"> Make Changes </button>
        <input type = "hidden" id = "event_ID"/> 
    </div>

    <script src = "user_management.js"></script>
</body>
</html>