<!-- This file is used to store topbar (right) items -->


{{-- <li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-bell"></i><span class="badge badge-pill badge-danger">5</span></a></li>
<li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-list"></i></a></li>
<li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-map"></i></a></li> --}}


<div class="notification-container mr-5 text-center">
    <div class="notification-icon" onclick="showNotifications()">
      <i class="fas fa-bell"></i>
      <span class="notification-count" id="notification-count"></span>
    </div>
  </div>

  <div class="date-time">
    <a class="text-white mr-3 p-2" href="javascript:;"><i class="la la-calendar"> </i> {{current_nepali_date_formatted()}} </a></br>
    <i class="la la-clock-o pl-2 pr-2"> </i><span class="text-white" id="txt"> Loading...</span>
  </div>

  <div id="notification-list" class="notification-list mr-5">
    <div class="notification-header">
      <button class="close-btn" onclick="hideNotifications()"><i class="las la-times-circle"></i></button>
    </div>
    <!-- The list of notifications will be displayed here -->
  </div>

  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
      @font-face {
      font-family: "Kalimati";
      src: url("/fonts/Kalimati.ttf") format("truetype");
  }
    .class-lc{
      font-family: Kalimati;
    }
    .active_lang {
            background: rgb(154, 154, 214);
            border-radius: 5px;
            margin: 2px;
            padding:5px 7px 10px 7px;
        }
        .date-time {
            color: white;
            font: small-caps bold 14px kalimati, serif;
            /* padding: 0 2.25rem; */
        }


  </style>

<style>
.notification-container {
    height: 40px;
    width: 40px;
    position: relative;
    display: flex;

    justify-content: center;
    align-items: center;
    /* position: relative;
    padding: 5px;
    left: 60%; */
    border-radius: 60%;
    background: #fff;
}

.notification-icon {
    background: none;
    border: none;
    cursor: pointer;
}

.notification-icon i {
  font-size: 25px;
  color: #32579F;
  cursor: pointer;
}
.notification-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  padding: 5px 10px;
  background-color: #f8f8f8;
  border-bottom: 1px solid #ddd;
}

.notification-count {
  position: absolute;
  display: none;
  top: -15px;
  right: -15px;
  background-color: red;
  color: white;
  font-size: 11px;
  padding: 2px 6px;
  border-radius: 50%;
}

.notification-list {
  display: none;
  position: absolute;
    top: 100%;
    right: 0;
  transform: translateX(-90%);
  background-color: #f5f5f5;
  border: 1px solid #ddd;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  min-width: 300px;
  max-height: 650px; /* Set a maximum height for the scrollable behavior */
  overflow-y: auto; /* Enable vertical scrolling if content exceeds the max-height */
  z-index: 9999;
}
.close-btn {
  position: absolute;
  top: 5px;
  right: 5px;
  border-radius: 80%;
  background-color:red;
  color:white;
  border: none;
  cursor: pointer;
  font-size: 12px;
}
.notification-item {
  padding: 5px;
  border-bottom: 1px solid #ddd;
}

.mark-all-read-btn {
  background-color: #4caf50;
  color: rgb(47, 10, 134);
  border: none;
  cursor: pointer;
  padding: 5px 10px;
  border-radius: 3px;
  font-size: 14px;
}

.mark-all-read-btn:hover {
  background-color: #45a049;
}
.tick::before {
    content: "\2714"; /* Unicode for a checkmark symbol */
    color: green ;
    margin-right: 5px;
}

.cross::before {
    content: "\2718"; /* Unicode for a cross mark symbol */
    color: red;
    margin-right: 5px;
}
.small-font{
  font-size: 14px;
}
.extra-small-font{
  font-size: 9px;

}
.extra-medium-font{
  font-size: 10px;

}

.notification-item.unread {
  background-color: #e7f3ff; /* Light blue color or any color of your choice */
}
.notification-item a.notification-link.custom-link,
.notification-details a.notification-link.custom-link {
  color: blue;
  text-decoration: none !important;
  /* Add any other styles you want for the links */
}
a.custom-link:hover{
  text-decoration: none !important;
}
.detail-container{
  color:darkblue;
}



</style>

<script>
  function startTime() {
      var today = new Date();
      var h = today.getHours();
      var ampm = h >= 12 ? 'PM' : 'AM';
      h = h % 12;
      h = h ? h : 12;
      var m = today.getMinutes();
      var s = today.getSeconds();
      m = checkTime(m);
      s = checkTime(s);
      document.getElementById('txt').innerHTML =
          h + ":" + m + ":" + s + " " + ampm;
      var t = setTimeout(startTime, 500);
  }

  function checkTime(i) {
      if (i < 10) {
          i = "0" + i
      }; // add zero in front of numbers < 10
      return i;
  }

  window.onload = startTime();

</script>
<!-- Include your main JavaScript libraries first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.min.js"></script>

<script>
  // pusher
  // Echo.channel('agenda-updates')
  //   .listen('.AgendaApprovedRejected', (event) => {
  //       // Handle the event data here
  //       console.log('Received event:', event);
  //   });
  // window.Echo = new Echo({ ... });

  // Echo.private(`data.${roles_id}`)
  //   .listen('AgendaApprovedRejected', (e) => {
  //       console.log(e);
  //   });

// Start listening for events
// require('./channels');

// Subscribe to the channel
// const channel = Echo.channel('posts');

// // Listen for the event
// channel.listen('AgendaApprovedRejected', (event) => {
//     console.log('New Notification created:', event.data);
//     // Update the UI with the new post data
// });


</script>
<script>
  let notificationListVisible = false;
  function showNotifications() {
    const notificationList = document.getElementById("notification-list");
    notificationList.innerHTML = ""; // Clear previous notifications

    $.ajax({
        url: '/admin/notifications',
        method: 'GET',
        dataType: 'json',
        success: function(data) {

          var notifications = data.notifications;
          // Process the returned data (notifications)
          // For example, update the notification list in the UI
          if(notifications.length > 0){
              notifications.forEach((notification) => {
              const notificationItem = document.createElement("div");
              notificationItem.classList.add("notification-item", "small-font");
              notificationItem.classList.add("notification-item");
              notificationItem.textContent = notification.data;

              // Check the status_id and add appropriate class for styling
              if (notification.status_id === 1) {
                  notificationItem.classList.add("tick"); // Assuming you have a CSS class for the green tick
              } else {
                  notificationItem.classList.add("cross"); // Assuming you have a CSS class for the cross mark
              }

              // if(notification.read_at === null){
              //   notificationItem.classList.add("unread");
              // }else{
              //   notificationItem.classList.remove("unread");

              // }

              // Add the additional details (time, role name, and user name)
              const detailsContainer = document.createElement("div");
              detailsContainer.classList.add("detail-container");

              detailsContainer.classList.add("notification-details");

              const timeElement = document.createElement("small");
              timeElement.classList.add("small");
              timeElement.textContent = formatHumanReadableDate(notification.created_at);

              const roleElement = document.createElement("span");
              roleElement.classList.add("role-name");
              roleElement.classList.add("extra-medium-font");
              roleElement.textContent = notification.from_role_name;

              const userElement = document.createElement("strong");
              userElement.classList.add("extra-small-font");

              userElement.textContent = notification.from_user_name;

              // Append the elements to the detailsContainer in the desired order
              detailsContainer.appendChild(timeElement);
              detailsContainer.appendChild(document.createTextNode(" ago from "));
              detailsContainer.appendChild(roleElement);
              detailsContainer.appendChild(document.createTextNode(" - "));
              detailsContainer.appendChild(userElement);

              //  Create the anchor tag
              const anchorElement = document.createElement("a");
              anchorElement.classList.add("custom-link");
              let comma_separated_ids

              if(notification.type == 'Agenda'){
                anchorElement.setAttribute("href", "{{ route('agenda.index') }}");

              }else if(notification.type == 'MeetingRequest'){

                anchorElement.setAttribute("href", "{{ route('ec-meeting-request.index') }}");

              }else if (notification.type == 'MeetingMinute'){
                anchorElement.setAttribute("href", "{{ route('meeting-minute-detail.index') }}");

              }else{
                anchorElement.setAttribute("href", "admin/agenda");

              }

              // Add the "notification-link" class to the anchor element
              anchorElement.classList.add("notification-link");


               // Add a click event listener to the anchor element to handle the click on the notification item
              anchorElement.addEventListener("click", (event) => {
                event.preventDefault();
                // Perform additional actions here, like opening a modal or navigating to a specific page
                // For example, you can use the `notification.type` to determine the action to take.
                if (notification.type === 'Agenda') {
                  markNotificationAsRead(data.noty_unread_agenda_ids);
                  comma_separated_ids = data.unread_agenda_ids.map(item => item.agenda_id).join(',');
                  navigateToLink(anchorElement.getAttribute("href"));
                  // setColorForAgendasRow(comma_separated_ids);
                } else if (notification.type === 'MeetingRequest') {
                  markNotificationAsRead(data.noty_unread_meeting_request_ids);
                  comma_separated_ids = data.unread_meeting_request_ids.map(item => item.meeting_request_id).join(',');
                  navigateToLink(anchorElement.getAttribute("href"));
                  // setColorForMeetingRequestRow(comma_separated_ids);
                } else if (notification.type === 'MeetingMinute') {
                  markNotificationAsRead(data.noty_unread_meeting_minute_ids);
                  comma_separated_ids = data.unread_meeting_minute_ids.map(item => item.meeting_minute_id).join(',');
                  navigateToLink(anchorElement.getAttribute("href"));
                  // setColorForMeetingMinuteRow(comma_separated_ids);
                }

                // Optionally, you can close the notification list after clicking a notification item
                hideNotifications();
              });

              // Append the detailsContainer to the notificationItem
              notificationItem.appendChild(detailsContainer);

              // Append the notificationItem to the anchorElement
              anchorElement.appendChild(notificationItem);

              // Append the anchorElement to the notificationList
              notificationList.appendChild(anchorElement);
            });
          }else{
              const notificationItem = document.createElement("div");
              notificationItem.classList.add("notification-item");
              notificationItem.textContent = "No Any Notification";
              notificationList.appendChild(notificationItem);
              const notificationCountElement = document.getElementById('notification-count');
              notificationCountElement.remove();
              
          }
        },
        error: function(error) {
          console.error('Failed to fetch notifications:');
        }
      });

    const closeButton = document.createElement("button");
    closeButton.classList.add("close-btn");
    closeButton.textContent = "X";
    closeButton.onclick = hideNotifications;
    notificationList.appendChild(closeButton);

    notificationList.style.display = notificationListVisible ? "none" : "block";
    notificationListVisible = !notificationListVisible;

    // Add a click event listener to the window to close the notification list when clicked outside
    if (notificationListVisible) {
      window.addEventListener("click", hideNotificationsOnClickOutside);
    } else {
      window.removeEventListener("click", hideNotificationsOnClickOutside);
    }

  }

  function formatHumanReadableDate(created_at) {
    const date = new Date(created_at);
    const currentDate = new Date();
    const timeDifferenceInSeconds = Math.floor((currentDate - date) / 1000);

    if (timeDifferenceInSeconds < 60) {
        return timeDifferenceInSeconds + "s";
    } else if (timeDifferenceInSeconds < 3600) {
        const minutes = Math.floor(timeDifferenceInSeconds / 60);
        return minutes + "m";
    } else if (timeDifferenceInSeconds < 86400) {
        const hours = Math.floor(timeDifferenceInSeconds / 3600);
        return hours + "h";
    } else if (timeDifferenceInSeconds < 604800) {
        const days = Math.floor(timeDifferenceInSeconds / 86400);
        return days + "d";
    } else {
        const options = {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            hour12: false,
        };
        return date.toLocaleString(undefined, options);
    }
}


function hideNotifications() {
  const notificationList = document.getElementById("notification-list");
  notificationList.style.display = "none";
  notificationListVisible = false;
  window.removeEventListener("click", hideNotificationsOnClickOutside);
}
function hideNotificationsOnClickOutside(event) {
  const notificationList = document.getElementById("notification-list");
  const notificationIcon = document.querySelector(".notification-icon");

  if (!notificationList.contains(event.target) && !notificationIcon.contains(event.target)) {
    hideNotifications();
  }
}

// function to mark the notification as read on the server-side
function markNotificationAsRead(notificationIds) {
    // Make an AJAX request to update the notification status to "read" on the server
    const requestData = {
        notification_ids: notificationIds
    };
    $.ajax({
        url: '/admin/notifications/mark-as-read',
        method: 'POST',
        data: requestData,
        success: function(response) {
            if(response.status == 'success'){
              console.log('Notification marked as read:', notificationId);
            }
        },
        error: function(error) {
            console.error('Failed to mark notification as read:', error);
        }
    });
}

// Function to handle navigation manually without reloading the page
function navigateToLink(url) {
    // Use appropriate methods to handle navigation based on your application's routing mechanism
    // For example, if you're using a router library like Vue Router or React Router, use its methods
    // If you're using vanilla JavaScript with no routing library, you can use the following:
    window.location.href = url;
}

function setColorForAgendasRow(ids) {
    // Make an AJAX request to update the notification status to "read" on the server
    $.ajax({
        url: '/admin/meeting-agenda-rows',
        method: 'POST',
        data: { ids: ids },
        success: function(response) {
          response.forEach(function(rowData) {
          var rowId = rowData.id;
          var rowColor = rowData.color;
          $(`tr[data-id="${rowId}"]`).css('background-color', rowColor);
        });
        },
        error: function(error) {
            console.error('Failed to set row color', error);
        }
    });
}
function setColorForMeetingRequestRow(ids) {
    // Make an AJAX request to update the notification status to "read" on the server
    $.ajax({
        url: '/admin/meeting-request-rows',
        method: 'POST',
        data: { ids: ids },
        success: function(response) {
            
        },
        error: function(error) {
            console.error('Failed to set row color', error);
        }
    });
}

function setColorForMeetingMinuteRow(ids) {
    // Make an AJAX request to update the notification status to "read" on the server
    $.ajax({
        url: '/admin/meeting-minute-rows',
        method: 'POST',
        data: { ids: ids },
        success: function(response) {
            
        },
        error: function(error) {
            console.error('Failed to set row color', error);
        }
    });
}




</script>