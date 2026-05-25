<?php
if (!isset($pageTitle)) $pageTitle = "EduEvents";
if (!isset($rootPath))  $rootPath  = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($pageTitle); ?> &mdash; NTC Events</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="<?php echo $rootPath; ?>assets/style.css" />
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<main class="edu-main">
