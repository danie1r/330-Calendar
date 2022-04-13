// Add new user
function registerAjax(event)
{
    const reg_user = document.getElementById("reg_user").value;
    const reg_pass = document.getElementById("reg_pass").value;
    
    // Make a URL-encoded string for passing POST data:
    const data = { 'reg_user': reg_user, 'reg_pass': reg_pass };

    fetch("register_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data =>
            {
                // Account creation successful.
                if (data.success)
                {
                    $("#registerNew").hide();
                    alert("Your account has been created!");
                }
                else
                {
                    alert("Account creation failed: " + data.message);
                }

                // Reset input given
                $("#reg_user").val('');
                $("#reg_pass").val('');
            })
        .catch(err => console.error(err));
};

// Logs in user
function loginAjax(event) {
    const username = document.getElementById("user_input").value; // Get the username from the form
    const password = document.getElementById("pass_input").value; // Get the password from the form

    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password': password };

    fetch("login_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: 
            {   
                'content-type': 'application/json', 
            }
        })
        .then(response => response.json())
        .then(data =>
            {
                if (data.success)
                {
                    alert("Login Successful!");

                    // Update current month visually
                    setMonth(); 

                    // Hide and show elements for logged in users
                    $("#addtrigger_btn").show();
                    $("#registerNew").hide();
                    $("#welcome_guest").hide();
                    $("#welcome_reg").text("Welcome " + data.username + "!");
                    $("#welcome_reg").show();
                    $("#login_form").hide();
                    $("#logout_form").show();
                    $("#addEvent").hide();
                    $("#event_list").show();
                    $("#edit_event").show();
                    document.getElementById("displayEvent").innerHTML = "";
                    window.token = data.token;
                }
                else
                {
                    alert("Login FAILED: " + data.message);

                    // Hide elements and inputs because log in failed
                    document.getElementById("displayEvent").innerHTML = "";
                    $("#addtrigger_btn").hide();
                    $("#registerNew").hide();
                    $("#eventDisplayBlock").hide();
                    $("#eventList").hide();
                    $("#user_input").val('');
                    $("#pass_input").val('');
                }
            })
        .catch(err => console.error(err));
};

// Logs out user
function logoutAjax(event)
{
    // Call logout php function to destroy session
    fetch("logout_ajax.php", { method: 'GET' })

    // Hide and show elements that guest should not see
    $("#addtrigger_btn").hide();
    $("#registerNew").hide();
    $("#welcome_guest").show();
    $("#welcome_reg").text("");
    $("#welcome_reg").hide();
    $("#login_form").show();
    $("#user_input").val('');
    $("#pass_input").val('');
    $("#logout_form").hide();
    $("#addEvent").hide();
    $("#event_list").hide();
    document.getElementById("displayEvent").innerHTML = "";
    $("#eventDisplayBlock").hide();
    $("#edit_event").hide();
    document.getElementById("event_title").value = "";
    document.getElementById("event_date").value = "";
    document.getElementById("event_time").value = "";
    document.getElementById("event_loc").value = "";
    document.getElementById("event_ID").value = "";
    ResetCalendar();

    // Update current month visually
    setMonth(); 
    setTimeout(setMonth, 100);
};

function checkUser(event)
{
    const data = { 'x': "hi", 'y': "there" };
    fetch("check_user.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: 
            {   
                'content-type': 'application/json', 
            }
        })
        .then(response => response.json())
        .then(data =>
            {
                if (data.success)
                {
                    // Session username exists (so user is logged in)
                    $("#addtrigger_btn").show();
                    $("#registerNew").hide();
                    $("#welcome_guest").hide();
                    $("#welcome_reg").text("Welcome " + data.username + "!");
                    $("#welcome_reg").show();
                    $("#login_form").hide();
                    $("#logout_form").show();
                    $("#addEvent").hide();
                    $("#event_list").show();
                    $("#edit_event").show();
                    setTimeout(setMonth, 100);
                    document.getElementById("displayEvent").innerHTML = "";
                    window.token = data.token;
                }
                else
                {
                    // User not logged in
                    $("#addtrigger_btn").hide();
                    $("#registerNew").hide();
                    $("#welcome_guest").show();
                    $("#welcome_reg").text("");
                    $("#welcome_reg").hide();
                    $("#login_form").show();
                    $("#logout_form").hide();
                    $("#addEvent").hide();
                    document.getElementById("displayEvent").innerHTML = "";
                    $("#eventDisplayBlock").hide();
                    $("#event_list").hide();
                    $("#edit_event").hide();
                    setTimeout(setMonth, 100);
                    ResetCalendar();
                }
            })
        .catch(err => console.error(err));
}

// Change font color of calendar dates to black when not signed in
function ResetCalendar()
{
    for (let index = 1; index <= 35; index++)
    {
        document.getElementById(index).style.color = "black";
    }
    setTimeout(setMonth, 100);
}

document.addEventListener("DOMContentLoaded", checkUser, false);

// Button functions
document.getElementById("login_btn").addEventListener("click", loginAjax, false); 
document.getElementById("logout_btn").addEventListener("click", logoutAjax, false); 
document.getElementById("create_btn").addEventListener("click", registerAjax, false);
document.getElementById("register_btn").addEventListener("click", function(){$("#registerNew").show()})
document.getElementById("addtrigger_btn").addEventListener("click", function(){$("#addEvent").show()})
