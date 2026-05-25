<?php
session_start();
require_once '../auth/db.php';

// Ensure the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle the one-click registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Check if the student is already registered
    $check_stmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->execute([$user_id, $event_id]);

    if ($check_stmt->rowCount() > 0) {
        $message = "<div class='alert alert-warning'>You are already registered for this event.</div>";
    } else {
        // Insert new registration
        $insert_stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        if ($insert_stmt->execute([$user_id, $event_id])) {
            $message = "<div class='alert alert-success'>Successfully registered!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error during registration.</div>";
        }
    }
}

// Fetch all upcoming events
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Student Portal</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">All Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="my-events.php">My Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="../auth/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Upcoming Events</h2>
    <hr>

    <?php echo $message; ?>

    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            Date: <?php echo htmlspecialchars($event['event_date']); ?>
                        </h6>
                        <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                        <p class="card-text"><small>Slots: <?php echo htmlspecialchars($event['slots']); ?></small></p>
                        
                        <form method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (count($events) == 0): ?>
            <div class="col-12">
                <div class="alert alert-info">No events are available at this time.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>