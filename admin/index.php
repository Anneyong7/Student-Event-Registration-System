<?php
include 'db.php'; // DB connection


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['name'];
    $user_email = $_POST['email'];
    $event_id = intval($_POST['event_id']);

    
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?) 
                            ON DUPLICATE KEY UPDATE name=VALUES(name)");
    $stmt->bind_param("ss", $user_name, $user_email);
    $stmt->execute();
    $user_id = $conn->insert_id ?: $conn->query("SELECT id FROM users WHERE email='$user_email'")->fetch_assoc()['id'];

    
    $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id, registered_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $event_id);
    $stmt->execute();

    $success = "You have successfully registered!";
}


$events = $conn->query("SELECT id, event_name FROM events");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Event Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2 class="mb-4">Student Event Registration</h2>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success; ?></div>
  <?php endif; ?>


  <form method="post" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="event_id" class="form-label">Select Event</label>
      <select name="event_id" id="event_id" class="form-select" required>
        <option value="">Choose an event...</option>
        <?php while($row = $events->fetch_assoc()): ?>
          <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['event_name']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
  </form>

</body>
</html>
