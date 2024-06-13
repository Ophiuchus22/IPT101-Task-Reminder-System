<!-- 
// File name: reminder.php
// Purpose: This HTML file structures the Reminder App, providing the layout and essential elements for user interaction.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This file defines the basic UI for a task reminder application, including a form to input class="form-control" reminders and a table to display them.
-->

<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Connect to the database
include "db.php";

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session

// Fetch reminders for the logged-in user
$sql = "SELECT * FROM reminders WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch reminders and output them in your HTML
$reminders = [];
while ($row = $result->fetch_assoc()) {
    $reminders[] = $row;
}

// Fetch the username for the logged-in user
$user_sql = "SELECT username FROM account WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_row = $user_result->fetch_assoc()) {
    $username = $user_row['username'];
} else {
    // Handle the case where the username is not found
    $username = "Unknown User";
}

$user_stmt->close();
$stmt->close();
?>


<!DOCTYPE html>
<html>

<head>
  <title>Reminder App</title>
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

    .sidebar {
      position: fixed;
      top: 0;
      left: -200px; /* Start from the left outside of the screen */
      height: 100%;
      width: 200px;
      background-color: #6c757d;
      padding: 20px;
      overflow: auto;
      transition: all 0.3s ease;
    }

    .sidebar.active {
      transform: translateX(200px); /* Slide in from the left */
    }

    .menu-btn {
      position: fixed;
      top: 10px;
      right: 10px;
    }

    .sidebar-button {
      text-align: left;
      width: 100%;
      margin-bottom: 10px;
      background-color: #6c757d;
      color: white;
      border: none;
    }

    .sidebar-button:hover {
      background-color: #5a6268;
    }

    .text-center {
      text-align: center;
    }
    
  </style>
</head>

<body>

  <button class="btn btn-primary menu-btn">Menu</button>

  <div class="sidebar">
    <h3 class="text-white text-center"><?php echo htmlspecialchars($username); ?></h3>
    <br> <br>

    <form id="changePasswordForm" method="POST" action="change_pass.php">
      <button type="button" class="sidebar-button" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
        Change Password
      </button>
    </form>

    <form action="logout.php" method="post">
        <button type="submit" class="sidebar-button">Logout</button>
    </form>
  </div>

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

<script>
  $(document).ready(function() {
    $('.menu-btn').click(function() {
      $('.sidebar').toggleClass('active');
      if ($('.sidebar').hasClass('active')) {
        $('.sidebar').css('left', '-200px'); // Slide in from the left
      } else {
        $('.sidebar').css('left', '-200px'); // Slide out to the left
      }
    });
  });
</script>

<script>
  document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the form from being submitted in the traditional way

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'change_pass.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200) {
        // Handle the response from the server
        // You might want to update the error and success messages here
      }
    };
    xhr.send(new FormData(e.target));
  });
</script>

<script>
  $(document).ready(function() {
    // Check if there's an error or success message
    var errorMessage = "<?php echo isset($_SESSION['user_pass_error']) ? $_SESSION['user_pass_error'] : ''; ?>";
    var successMessage = "<?php echo isset($_SESSION['user_pass_success']) ? $_SESSION['user_pass_success'] : ''; ?>";

    // If there's an error or success message, show the modal
    if (errorMessage !== '' || successMessage !== '') {
      $('#changePasswordModal').modal('show');
    }

    // Remove the message after 4 seconds
    setTimeout(function() {
      $('.alert').remove();
    }, 4000); // 4000 milliseconds = 4 seconds
  });
</script>



</body>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if (isset($_SESSION['user_pass_error'])): ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['user_pass_error']; unset($_SESSION['user_pass_error']); ?>
          </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_pass_success'])): ?>
          <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['user_pass_success']; unset($_SESSION['user_pass_success']); ?>
          </div>
        <?php endif; ?>
        <form id="changePasswordForm" method="POST" action="change_pass.php">
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Current Password" required>
            <label for="currentPassword">Current Password</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="New Password" required>
            <label for="newPassword">New Password</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
            <label for="confirmPassword">Confirm Password</label>
          </div>
          <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
      </div>
    </div>
  </div>
</div>

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