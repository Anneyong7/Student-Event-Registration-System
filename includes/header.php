<?php
/**
 * includes/header.php
 * Shared page header. Include at the TOP of every PHP page.
 *
 * REQUIRED: Call session_start() BEFORE including this file.
 *
 * USAGE (from /auth/, /student/, /admin/events/, /admin/registrants/):
 *   session_start();
 *   $pageTitle = "Dashboard";   // optional, defaults to "EduEvents"
 *   include '../includes/header.php';
 *
 * Pages inside /admin/events/ or /admin/registrants/ are 2 levels deep,
 * so set $rootPath before including:
 *   $rootPath = '../../';
 *   include '../../includes/header.php';
 *
 * Pages inside /auth/ or /student/ are 1 level deep — no $rootPath needed.
 */

if (!isset($pageTitle)) $pageTitle = "EduEvents";
if (!isset($rootPath))  $rootPath  = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($pageTitle); ?> &mdash; EduEvents</title>

  <!-- Bootstrap 5.3 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />

  <!-- Custom stylesheet -->
  <link
    rel="stylesheet"
    href="<?php echo $rootPath; ?>assets/css/style.css"
  />
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<main class="edu-main">
