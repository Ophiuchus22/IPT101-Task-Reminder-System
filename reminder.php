<!-- 
// File name: reminder.php
// Purpose: This HTML file structures the Reminder App, providing the layout and essential elements for user interaction.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This file defines the basic UI for a task reminder application, including a form to input class="form-control" reminders and a table to display them.
-->

<!DOCTYPE html>
<html>

<head>
  <title>Reminder App</title>
  <!-- <link rel="stylesheet" type="text/css" href="styles.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
      
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
      body {
    font-family: 'Poppins', sans-serif;
}
    </style>
</head>

<body class="overflow-hidden">

  <div class="row justify-content-center p-3">
    <div class="col-sm-5">
      <h2 class="m-5" style="text-align: center">Task Reminder</h2>
      <form id="reminderForm" method="POST">
        <div class="form-floating mb-3">
          <input type="text" class="form-control bg-light" id="title" name="title" placeholder="Title" required>
          <label for="title">Title</label>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control bg-light" id="description" name="description" placeholder="Description"
            required>
          <label for="description">Description</label>
        </div>

        <div class="form-floating mb-3">
          <input type="date" class="form-control bg-light" id="date" name="date" placeholder="Date" required>
          <label for="date">Date</label>
        </div>

        <div class="form-floating mb-3">
          <input type="time" class="form-control bg-light" id="time" name="time" placeholder="Time" required>
          <label for="time" class="">Time</label>
        </div>

        <button type="submit" id="button" class="btn btn-primary mt-2" style="width: 100%">Schedule Reminder</button>
      </form>

      <table class="table table-bordered mt-2">
        <thead>
          <tr>
          <th hidden>ID</th>
            
          <th>Title</th>
            <th>Description</th>
            <th>Date & Time</th>
            <th>Status</th>

            <th>Action</th>
          </tr>
        </thead>
        <tbody id="reminderTableBody"></tbody>
      </table>
    </div>
  </div>

  <audio id="notificationSound" src="notificationsoundeffect.mp3"></audio>
  <script src="action_script.js"></script>
</body>



<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ">
        <form id="editForm">
          <div class="form-floating mb-3">
            <input type="text" class="form-control  bg-light" id="editTitle" name="title" placeholder="Title" required>
            <label for="editTitle">Title</label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" class="form-control  bg-light" id="editDescription" name="description"
              placeholder="Description" required>
            <label for="editDescription">Description</label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" class="form-control bg-light" id="editDateTime" name="dateTime"
              placeholder="YYYY-MM-DD/HH:MM" required>
            <label for="editDateTime">Date and Time (YYYY-MM-DD/HH:MM)</label>
          </div>

          <button class="btn btn-primary w-100" type="submit">Save</button>
        </form>

      </div>

    </div>
  </div>
</div>
<div id="" class="modal">
  <!-- Edit Reminder Modal -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Edit Reminder</h2>

  </div>
</div>

</html>