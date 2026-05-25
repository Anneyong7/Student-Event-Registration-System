<?php
session_start();
require_once '../../auth/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

$events_stmt = $pdo->query("SELECT id, title FROM events ORDER BY title ASC");
$events = $events_stmt->fetchAll();

$sql = "SELECT users.username, users.email, events.title AS event_title, registrations.registered_at
        FROM registrations
        JOIN users ON registrations.user_id = users.id
        JOIN events ON registrations.event_id = events.id";

if ($event_id > 0) {
    $sql .= " WHERE events.id = ? ORDER BY registrations.registered_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$event_id]);
} else {
    $sql .= " ORDER BY registrations.registered_at DESC";
    $stmt = $pdo->query($sql);
}

$registrants = $stmt->fetchAll();

$pageTitle = "Registrant Viewer";
$rootPath = '../../';
include '../../includes/header.php';
?>

<div class="container">
    <div class="page-heading">
        <h2>Registrant Viewer Dashboard</h2>
        <p>Monitor real-time course and sports event student capacities</p>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row align-items-center">
                <div class="col-md-3 col-lg-2">
                    <label for="event_id" class="form-label fw-bold mb-md-0">Filter by Event:</label>
                </div>
                <div class="col-md-6">
                    <select name="event_id" id="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="0">All System Events</option>
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

    <div class="table-card">
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
                        <td colspan="4" class="text-center text-muted py-4">No records found for the selected event filters.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
