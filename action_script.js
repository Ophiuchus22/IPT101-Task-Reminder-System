document.addEventListener("DOMContentLoaded", function() {
    if ("Notification" in window) {
      Notification.requestPermission().then(function(permission) {
        if (Notification.permission !== "granted") {
          alert("Please allow notification access!");
        }
      });
    }
  
    var timeoutIds = [];
  
    document.getElementById("reminderForm").addEventListener("submit", function(event) {
      event.preventDefault();
      scheduleReminder();
    });
  
    function scheduleReminder() {
      var title = document.getElementById("title").value;
      var description = document.getElementById("description").value;
      var date = document.getElementById("date").value;
      var time = document.getElementById("time").value;
  
      var dateTimeString = date + " " + time;
      var scheduledTime = new Date(dateTimeString);
      var currentTime = new Date();
      var timeDifference = scheduledTime - currentTime;
  
      if (timeDifference > 0) {
        addReminder(title, description, dateTimeString);
  
        var timeoutId = setTimeout(function() {
          document.getElementById("notificationSound").play();
  
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
  
    function deleteReminder(button) {
        var row = button.closest("tr");
        var reminderId = row.dataset.id; // Assuming you have a unique identifier for each reminder
    
        fetch('delete_reminder.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: reminderId
            })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Reminder deleted successfully");
                row.remove(); // Remove the row from the UI after successful deletion
            } else {
                console.error("Failed to delete reminder: " + data.error);
            }
        }).catch(error => {
            console.error("Error deleting reminder: " + error);
        });
    }    
    
  
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
