<?php
session_start();
require_once '../auth/db.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch ONLY the events this specific student registered for
$sql = "SELECT events.*, registrations.registered_at 
        FROM events 
        JOIN registrations ON events.id = registrations.event_id 
        WHERE registrations.user_id = ? 
        ORDER BY events.event_date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$my_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Student Portal</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">All Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="my-events.php">My Registered Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../auth/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>My Registered Events</h2>
    <hr>

    <div class="row">
        <?php if (count($my_events) > 0): ?>
            <?php foreach ($my_events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-success">
                        <div class="card-body">
                            <h5 class="card-title text-success"><?php echo htmlspecialchars($event['title']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Date: <?php echo htmlspecialchars($event['event_date']); ?>
                            </h6>
                            <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                            <div class="alert alert-success p-2 mb-0 text-center">
                                <small>Registered on: <?php echo htmlspecialchars($event['registered_at']); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    You have not registered for any events yet. <a href="dashboard.php">Browse events here</a>.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>