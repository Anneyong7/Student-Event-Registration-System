<?php
session_start();
require_once '../auth/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $event_id = intval($_POST['event_id']);

    $check_stmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->execute([$user_id, $event_id]);

    if ($check_stmt->rowCount() > 0) {
        $message = "<div class='alert alert-warning alert-dismissible fade show'>You are already registered for this event.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $slot_stmt = $pdo->prepare("SELECT slots FROM events WHERE id = ?");
        $slot_stmt->execute([$event_id]);
        $event_info = $slot_stmt->fetch();

        if ($event_info && $event_info['slots'] > 0) {
            $insert_stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
            $update_stmt = $pdo->prepare("UPDATE events SET slots = slots - 1 WHERE id = ?");

            if ($insert_stmt->execute([$user_id, $event_id]) && $update_stmt->execute([$event_id])) {
                $message = "<div class='alert alert-success alert-dismissible fade show'>Successfully registered for this event!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
            } else {
                $message = "<div class='alert alert-danger'>Error processing registration.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Registration failed. This event is fully booked!</div>";
        }
    }
}

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();

$pageTitle = "Student Dashboard";
$rootPath = '../';
include '../includes/header.php';
?>

<div class="container">
    <div class="page-heading">
        <h2>Upcoming Campus Events</h2>
        <p>Browse and sign up for available university contests and sessions</p>
    </div>

    <?php echo $message; ?>

    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($event['title']); ?></h5>
                        <h6 class="card-subtitle mb-3 text-muted">
                            📅 Date: <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                        </h6>
                        <p class="card-text text-secondary flex-grow-1"><?php echo htmlspecialchars($event['description']); ?></p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <span class="small fw-bold <?php echo ($event['slots'] > 0) ? 'text-success' : 'text-danger'; ?>">
                                Slots Left: <?php echo htmlspecialchars($event['slots']); ?>
                            </span>
                            
                            <form method="POST" class="m-0">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-primary px-3" <?php echo ($event['slots'] <= 0) ? 'disabled' : ''; ?>>
                                    <?php echo ($event['slots'] > 0) ? 'Register Now' : 'Fully Booked'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (count($events) == 0): ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-4">No campus events are currently scheduled. Check back soon!</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>