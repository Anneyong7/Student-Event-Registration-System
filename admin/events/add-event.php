<?php
session_start();
require_once '../../auth/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $event_date = $_POST['date'];
    $description = trim($_POST['description']);
    $slots = intval($_POST['slots']);

    if (empty($title) || empty($event_date) || empty($description) || $slots < 1) {
        $error = "Please fill in all fields with valid configurations.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO events (title, event_date, description, slots) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $event_date, $description, $slots])) {
            header("Location: events.php?msg=Event created successfully!");
            exit();
        } else {
            $error = "Error creating event.";
        }
    }
}

$pageTitle = "Create Event";
$rootPath = '../../';
include '../../includes/header.php';
?>

<div class="container d-flex justify-content-center">
    <div class="card shadow-sm w-100" style="max-width: 600px;">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0">Create New Event</h4>
        </div>
        <div class="card-body p-4">
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form id="eventForm" action="add-event.php" method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label">Event Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Event Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label">Available Slots</label>
                    <input type="number" name="slots" class="form-control" min="1" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="events.php" class="btn btn-secondary">Go Back</a>
                    <button type="submit" class="btn btn-primary">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
