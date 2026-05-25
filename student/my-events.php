<?php
session_start();
require_once '../auth/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT events.*, registrations.registered_at 
        FROM events 
        JOIN registrations ON events.id = registrations.event_id 
        WHERE registrations.user_id = ? 
        ORDER BY events.event_date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$my_events = $stmt->fetchAll();

$pageTitle = "My Registered Events";
$rootPath = '../';
include '../includes/header.php';
?>

<div class="container">
    <div class="page-heading">
        <h2>My Registered Events</h2>
        <p>Review the university events you have committed to attend</p>
    </div>

    <div class="row">
        <?php if (count($my_events) > 0): ?>
            <?php foreach ($my_events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-start border-4 border-success">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-success"><?php echo htmlspecialchars($event['title']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                📅 Event Date: <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                            </h6>
                            <p class="card-text text-secondary"><?php echo htmlspecialchars($event['description']); ?></p>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="alert alert-success p-2 mb-2 text-center small">
                                Registered on: <?php echo date('M d, Y h:i A', strtotime($event['registered_at'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center py-4">
                    You have not registered for any events yet. <a href="dashboard.php" class="alert-link fw-bold">Browse events here</a>.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>