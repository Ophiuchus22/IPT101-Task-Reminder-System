<!-- 
// File name: reminder.php
// Purpose: This HTML file structures the Reminder App, providing the layout and essential elements for user interaction.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This file defines the basic UI for a task reminder application, including a form to input reminders and a table to display them.
-->

<!DOCTYPE html>
<html>
  <head>
    <title>Reminder App</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <body>
    <div class="container">
      <h2 style="text-align: center">Task Reminder</h2>

      <form id="reminderForm">
        <label for="title">Title : </label>
        <input type="text" id="title" name="title" required />
        <label for="description">Description :</label>
        <input type="text" id="description" name="description" required />
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required />
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required />
        <button type="submit" id="button" style="width: 100%">Schedule Reminder</button>
      </form>

      <table border="1">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date & Time</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="reminderTableBody"></tbody>
      </table>
    </div>
      
    <audio id="notificationSound" src="notificationsoundeffect.mp3"></audio>
    <script src="action_script.js"></script>
  </body>

  <div id="editModal" class="modal">
      <!-- Edit Reminder Modal -->
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Reminder</h2>
        <form id="editForm">
          <label for="editTitle">Title:</label>
          <input type="text" id="editTitle" name="title" required><br>
          <label for="editDescription">Description:</label>
          <input type="text" id="editDescription" name="description" required><br>
          <label for="editDateTime">Date and Time (YYYY-MM-DD/HH:MM):</label>
          <input type="text" id="editDateTime" name="dateTime" required><br>
          <button type="submit">Save</button>
        </form>
      </div>
    </div>
    
</html>
