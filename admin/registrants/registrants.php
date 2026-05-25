<?php
session_start();
require_once '../../auth/db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Fetch all events for the filter dropdown list using PDO
$events_stmt = $pdo->query("SELECT id, title FROM events ORDER BY title ASC");
$events = $events_stmt->fetchAll(PDO::FETCH_ASSOC);

// Base JOIN query mapping to your exact database schema
$sql = "SELECT users.username, users.email, events.title AS event_title, registrations.registered_at
        FROM registrations
        JOIN users ON registrations.user_id = users.id
        JOIN events ON registrations.event_id = events.id";

// Apply dynamic dropdown filtering safely
if ($event_id > 0) {
    $sql .= " WHERE events.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$event_id]);
} else {
    $sql .= " ORDER BY registrations.registered_at DESC";
    $stmt = $pdo->query($sql);
}

$registrants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Registrant Viewer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="events.php">Admin Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="events/events.php">Manage Events</a></li>
                <li class="nav-item"><a class="nav-link active" href="registrants.php">View Registrants</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Registrant Viewer Dashboard</h2>
    <hr>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row align-items-center">
                <div class="col-md-2">
                    <label for="event_id" class="form-label fw-bold mb-0">Filter by Event:</label>
                </div>
                <div class="col-md-6">
                    <select name="event_id" id="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="0">All Events</option>
                        <?php foreach ($events as $row): ?>
                            <option value="<?= $row['id']; ?>" <?= ($event_id == $row['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($row['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Email Address</th>
                        <th>Target Event</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($registrants) > 0): ?>
                        <?php foreach ($registrants as $row): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['username']); ?></strong></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($row['event_title']); ?></span></td>
                                <td><?= date('M d, Y h:i A', strtotime($row['registered_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No records found for the selected event.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
