// 
// File name: action_script.js
// Purpose: This JavaScript file manages the reminder scheduling and 
//          notification functionalities for the Reminder App.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This script handles form submission, schedules reminders, 
//                 displays notifications, and stores reminders in the database.
//

document.addEventListener("DOMContentLoaded", function() {
  // Check if browser supports notifications and request permission
    if ("Notification" in window) {
      Notification.requestPermission().then(function(permission) {
        if (Notification.permission !== "granted") {
          alert("Please allow notification access!");
        }
      });
    }
  
    var timeoutIds = [];
    
    // Add event listener to the form submission
    document.getElementById("reminderForm").addEventListener("submit", function(event) {
      event.preventDefault();
      scheduleReminder();
    });
    
    // Function to schedule a new reminder
    function scheduleReminder() {
      var title = document.getElementById("title").value;
      var description = document.getElementById("description").value;
      var date = document.getElementById("date").value;
      var time = document.getElementById("time").value;
      // Combine date and time into a single string and convert to Date object
      var dateTimeString = date + " " + time;
      var scheduledTime = new Date(dateTimeString);
      var currentTime = new Date();
      var timeDifference = scheduledTime - currentTime;
  
      if (timeDifference > 0) {
        // Add reminder to the table
        addReminder(title, description, dateTimeString);
        // Schedule the reminder notification
        var timeoutId = setTimeout(function() {
          document.getElementById("notificationSound").play();
          // Show notification if permission is granted
          if (Notification.permission === "granted") {
            var notification = new Notification(title, {
              body: description,
              requireInteraction: true,
            });
          } else {
            console.error("Notification permission is not granted.");
          }
        }, timeDifference);
  
        timeoutIds.push(timeoutId);
  
        // Store the reminder in the database
        storeReminder(title, description, date, time);
      } else {
        alert("The scheduled time is in the past!");
      }
    }
    
    // Function to add reminder details to the table
    function addReminder(title, description, dateTimeString) {
      var tableBody = document.getElementById("reminderTableBody");
  
      var row = tableBody.insertRow();
  
      var titleCell = row.insertCell(0);
      var descriptionCell = row.insertCell(1);
      var dateTimeCell = row.insertCell(2);
      var actionCell = row.insertCell(3);
  
      titleCell.innerHTML = title;
      descriptionCell.innerHTML = description;
      dateTimeCell.innerHTML = dateTimeString;
      actionCell.innerHTML =
        '<button onclick="deleteReminder(this)">Delete</button>';
    }  
    
    // Function to store the reminder details in the database
    function storeReminder(title, description, date, time) {
      fetch('store_reminder.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          title: title,
          description: description,
          date: date,
          time: time
        })
      }).then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log("Reminder stored successfully");
        } else {
          console.error("Failed to store reminder: " + data.error);
        }
      }).catch(error => {
        console.error("Error storing reminder: " + error);
      });
    }
  });
