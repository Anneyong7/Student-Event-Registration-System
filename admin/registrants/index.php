<?php
include 'db.php'; 

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;


$events = $conn->query("SELECT id, event_name FROM events");


$sql = "SELECT users.name, users.email, events.event_name, registrations.registered_at
        FROM registrations
        JOIN users ON registrations.user_id = users.id
        JOIN events ON registrations.event_id = events.id";

if ($event_id > 0) {
    $sql .= " WHERE events.id = $event_id";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registrant Viewer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2 class="mb-4">Registrant Viewer</h2>


  <form method="get" class="mb-3">
    <label for="event_id" class="form-label">Filter by Event:</label>
    <select name="event_id" id="event_id" class="form-select" onchange="this.form.submit()">
      <option value="0">All Events</option>
      <?php while($row = $events->fetch_assoc()): ?>
        <option value="<?= $row['id']; ?>" <?= ($event_id == $row['id']) ? 'selected' : ''; ?>>
          <?= htmlspecialchars($row['event_name']); ?>
        </option>
      <?php endwhile; ?>
    </select>
  </form>

  
  <table class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Event</th>
        <th>Registered At</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['event_name']); ?></td>
            <td><?= htmlspecialchars($row['registered_at']); ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="4" class="text-center">No registrants found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
