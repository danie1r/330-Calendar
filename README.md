# CSE330
Eric Tabuchi (ETabuchi, ID: 501415)
Daniel Ryu (danie1r, ID: 502005)

Link: http://ec2-3-82-122-171.compute-1.amazonaws.com/~ETabuchi/Calendar/calendar.php

List of registered users
| Username    | Password    |
| ----------- | ----------- |
| test        | user        |
| dan         | iel         |

Things About How the Website Works
* Once logged in, refreshing the page will keep the user logged in
* If logged in, you can click on the calendar itself and display all events associated with a specific date
  * EX: If you had an event on March 3, 2022, you can click on that date on the visual calendar and text will appear below stating all event names and times for that day.
* To register a new event as a logged in user, only the Location field is optional. For time, it must be an input of four integers between 0000 - 2359 
  * 0000 = Midnight, 1200 = Noon  12:00 PM, 2359 = 11:59 PM


Creative Portion
* User can create group events where the user can share the event date,time and location to other users.
  * The group input must be separated by ',', and will skip the users who do not exist.
  * For each user in group, the sql statement inserts the event information into the database, and will appear on their on calendar screen.
* User can "jump" to different months instead of clicking on buttons to go to the previous or next month.
  * There is an input box next to the buttons to change the displayed month where you input a desired month and year. Clicking on the submit button next to this input will cause the displayed calendar to "jump" to the inputted month (assuming you inputted something for both fields).
* Dates associated with the current month are bolded while the other days remained unbolded visually.
  * EX: If displaying March 2022, all the days of March shown are bolded but the days belong to either February or April are not bolded.
* If logged in, registered events will be displayed on the calendar with red text. If not logged in, the red text will disappear.
  * If an event is added and the date of the event is currently displayed on the calendar, the calendar will update visually and change its font to red.
  * Likewise, if an event is deleted, the calendar will update and change its font back to black (for the logged in user).
* When your mouse hovers over a date on the calendar, its background color will be highlighted to visually tell the user which cell their mouse is on such that selecting a specific date is easier.
  * EX: Hovering your mouse on the first cell on the calendar will turn the background color to pink and it will turn back to white when you move your mouse out.
* Users can add an optional field when adding / editing events where they can add a location for the event they want to change / add
  * The location is also displayed along with event name and time when you click on the date located on the calendar
  
  
