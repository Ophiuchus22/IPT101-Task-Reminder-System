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
</html>
