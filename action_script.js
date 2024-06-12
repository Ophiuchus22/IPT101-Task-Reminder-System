// 
// File name: action_script.js
// Purpose: This JavaScript file manages the reminder scheduling and 
//          notification functionalities for the Reminder App.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This script handles form submission, schedules reminders, 
//                 displays notifications, and stores reminders in the database.
//

document.addEventListener("DOMContentLoaded", function () {
  // Check if browser supports notifications and request permission
  if ("Notification" in window) {
    Notification.requestPermission().then(function (permission) {
      if (Notification.permission !== "granted") {
        Notification.requestPermission().then(function (permission) {
          if (permission === "granted") {
            fetchReminders();
          } else {
            alert("Please allow notification access!");
          }
        });
      } else {
        fetchReminders();
      }

    });
  }

  var timeoutIds = [];

  // Add event listener to the form submission
  document.getElementById("reminderForm").addEventListener("submit", function (event) {
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
      var timeoutId = setTimeout(function () {
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

  // Function to add reminder details to the table
  function addReminder(id,title, description, dateTimeString,status) {
    var tableBody = document.getElementById("reminderTableBody");

    var row = tableBody.insertRow();
    var idCell = row.insertCell(0);

    var titleCell = row.insertCell(1);
    var descriptionCell = row.insertCell(2);
    var dateTimeCell = row.insertCell(3);
    var statusCell = row.insertCell(4);

    var actionCell = row.insertCell(5);
    idCell.classList.add('collapse');

    idCell.innerHTML = id;

    titleCell.innerHTML = title;
    descriptionCell.innerHTML = description;
    dateTimeCell.innerHTML = dateTimeString;
    statusCell.innerHTML = status
    actionCell.innerHTML =
      '<button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editReminder(this)"><i class="bi bi-pencil-square"></i></button><button class="btn btn-danger" onclick="deleteReminder(this)"><i class="bi bi-trash3-fill"></i></button>';
  }
  
  function storeReminder(title, description, date, time) {
    const formData = new FormData();
    formData.append('title', title);
    formData.append('description', description);
    formData.append('date', date);
    formData.append('time', time);

    fetch('store_reminder.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log("Reminder stored successfully");
          window.location.reload();
        } else {
          console.error("Failed to store reminder: " + data.error);
        }
      })
      .catch(error => {
        console.error("Error storing reminder: " + error);
      });
  }






  function fetchReminders() {
    fetch('fetch.php')
      .then(response => response.json())
      .then(data => {
        data.forEach(reminder => {
          var dateTimeString = reminder.date + " " + reminder.time;
          addReminder(reminder.id,reminder.title, reminder.description, dateTimeString,reminder.status);
          reScheduleReminder(reminder.title, reminder.description, reminder.date, reminder.time);
        });
      })
      .catch(error => {
        console.error("Error fetching reminders: " + error);
      });
  }

  function reScheduleReminder(title, description, date, time) {
    var dateTimeString = date + " " + time;
    var scheduledTime = new Date(dateTimeString);
    var currentTime = new Date();
    var timeDifference = scheduledTime - currentTime;

    if (timeDifference > 0) {
      var timeoutId = setTimeout(function () {
        document.getElementById("notificationSound").play();
        if (Notification.permission === "granted") {
          var notification = new Notification(title, {
            body: description,
            requireInteraction: true,
          });
          setTimeout(function() {
            window.location.reload();
          }, 1000);
        } else {
          console.error("Notification permission is not granted.");
        }
      }, timeDifference);
    

      timeoutIds.push(timeoutId);


  
    } else {
      console.log("The scheduled time for this reminder is in the past!");
    }
  }






  // Function to edit a reminder
  window.editReminder = function (button) {
    var row = button.parentNode.parentNode;
    var titleCell = row.cells[1];
    
    var descriptionCell = row.cells[2];
    var dateTimeCell = row.cells[3];
    // var modal = document.getElementById("editModal");
    // var closeButton = document.getElementsByClassName("close")[0];

    document.getElementById("editTitle").value = titleCell.innerText;
    document.getElementById("editDescription").value = descriptionCell.innerText;
    document.getElementById("editDateTime").value = dateTimeCell.innerText;

    // modal.style.display = "block";

    // closeButton.onclick = function () {
    //   modal.style.display = "none";
    // };

    // window.onclick = function (event) {
    //   if (event.target == modal) {
    //     modal.style.display = "none";
    //   }
    // };

    var form = document.getElementById("editForm");
    form.onsubmit = function (event) {
      event.preventDefault();
      handleEditFormSubmit( row.cells[0],titleCell, descriptionCell, dateTimeCell);
    };
  };

  // Function to handle form submission
  function handleEditFormSubmit(idCell,titleCell, descriptionCell, dateTimeCell) {
    var title = document.getElementById("editTitle").value;
    var description = document.getElementById("editDescription").value;
    var dateTimeString = document.getElementById("editDateTime").value;

    if (title && description && dateTimeString) {
      titleCell.innerText = title;
      descriptionCell.innerText = description;
      dateTimeCell.innerText = dateTimeString;

      var [date, time] = dateTimeString.split(" ");
      updateReminder(idCell.innerText,title, description, date, time);
      // This will reload the current page
      window.location.reload();

      var modal = document.getElementById("editModal");
      modal.style.display = "none";
    }
  }

  // Function to update reminder details in the database
  function updateReminder(id,title, description, date, time) {
    fetch('update_reminder.php?id='+id, {
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
          console.log("Reminder updated successfully");
        } else {
          console.error("Failed to update reminder: " + data.error);
        }
      }).catch(error => {
        console.error("Error updating reminder: " + error);
      });
  }


  // Function to delete a reminder
  window.deleteReminder = function (button) {
    var row = button.parentNode.parentNode;
    var title = row.cells[1].innerHTML;
    var description = row.cells[2].innerHTML;
    var dateTimeString = row.cells[3].innerHTML;
    var [date, time] = dateTimeString.split(" ");

    if (confirm("Are you sure you want to delete this reminder?")) {
      row.remove();
      deleteReminderFromDB(title, description, date, time);
    }
  };

  // Function to delete reminder details from the database
  function deleteReminderFromDB(title, description, date, time) {
    fetch('delete_reminder.php', {
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
          console.log("Reminder deleted successfully");
        } else {
          console.error("Failed to delete reminder: " + data.error);
        }
      }).catch(error => {
        console.error("Error deleting reminder: " + error);
      });
  }

});


