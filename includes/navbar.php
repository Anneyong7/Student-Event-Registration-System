<?php
/**
 * includes/navbar.php
 * Dynamic navbar — reads $_SESSION to show the right links.
 * Included automatically by header.php. Do not include separately.
 *
 * session_start() must be called BEFORE including header.php.
 */

$isLoggedIn  = isset($_SESSION['user_id']);
$isAdmin     = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$displayName = isset($_SESSION['name'])
               ? htmlspecialchars($_SESSION['name'])
               : (isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Account');

// Current file for active-link detection
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg edu-navbar sticky-top">
  <div class="container">

    <!-- Brand -->
    <a class="navbar-brand edu-brand" href="<?php echo $rootPath ?? '../'; ?>index.php">
      <svg class="brand-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
           fill="currentColor" viewBox="0 0 16 16">
        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2
                 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2
                 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1
                 0 0 0 1-1V5z"/>
        <path d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5
                 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0
                 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-2 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1
                 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5
                 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
      </svg>
      EduEvents
    </a>

    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#eduNavbar"
            aria-controls="eduNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="eduNavbar">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

        <?php if (!$isLoggedIn): ?>
          <!-- GUEST: show Login + Register -->
          <li class="nav-item">
            <a class="nav-link edu-nav-link <?php echo $currentPage === 'login.php' ? 'active' : ''; ?>"
               href="<?php echo $rootPath ?? '../'; ?>auth/login.php">
              Login
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link edu-nav-cta <?php echo $currentPage === 'register.php' ? 'active' : ''; ?>"
               href="<?php echo $rootPath ?? '../'; ?>auth/register.php">
              Register
            </a>
          </li>

        <?php elseif ($isAdmin): ?>
          <!-- ADMIN links -->
          <li class="nav-item">
            <a class="nav-link edu-nav-link <?php echo $currentPage === 'events.php' ? 'active' : ''; ?>"
               href="<?php echo $rootPath ?? '../'; ?>admin/events/events.php">
              Manage Events
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link edu-nav-link <?php echo $currentPage === 'registrants.php' ? 'active' : ''; ?>"
               href="<?php echo $rootPath ?? '../'; ?>admin/registrants/registrants.php">
              Registrants
            </a>
          </li>
          <!-- Admin dropdown -->
          <li class="nav-item dropdown ms-lg-2">
            <a class="nav-link edu-user-pill dropdown-toggle" href="#"
               role="button" data-bs-toggle="dropdown" aria-expanded="false">
              &#9679; <?php echo $displayName; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end edu-dropdown">
              <li><span class="dropdown-item-text text-muted small px-3 py-1">Admin account</span></li>
              <li><hr class="dropdown-divider edu-divider"></li>
              <li>
                <a class="dropdown-item edu-dropdown-item logout-item"
                   href="<?php echo $rootPath ?? '../'; ?>auth/logout.php">
                  Logout
                </a>
              </li>
            </ul>
          </li>

        <?php else: ?>
          <!-- STUDENT links -->
          <li class="nav-item">
            <a class="nav-link edu-nav-link <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>"
               href="<?php echo $rootPath ?? '../'; ?>student/dashboard.php">
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link edu-nav-link <?php echo $currentPage === 'my-events.php' ? 'active' : ''; ?>"
               href="<?php echo $rootPath ?? '../'; ?>student/my-events.php">
              My Events
            </a>
          </li>
          <!-- Student dropdown -->
          <li class="nav-item dropdown ms-lg-2">
            <a class="nav-link edu-user-pill dropdown-toggle" href="#"
               role="button" data-bs-toggle="dropdown" aria-expanded="false">
              &#9679; <?php echo $displayName; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end edu-dropdown">
              <li><span class="dropdown-item-text text-muted small px-3 py-1">Student account</span></li>
              <li><hr class="dropdown-divider edu-divider"></li>
              <li>
                <a class="dropdown-item edu-dropdown-item logout-item"
                   href="<?php echo $rootPath ?? '../'; ?>auth/logout.php">
                  Logout
                </a>
              </li>
            </ul>
          </li>

        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
