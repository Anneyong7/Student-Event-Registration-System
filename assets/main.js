/**
 * EduEvents — main.js
 * Role 5: UI/UX & Architecture
 * File: assets/js/main.js
 *
 * What this covers:
 *  1. Login form validation   (matches auth/login.php fields: email, password)
 *  2. Register form validation (matches auth/register.php fields: username, password, confirm_password)
 *  3. Add/Edit event form validation (matches admin/events fields: title, date, description, slots)
 *  4. Delete confirmation for any element with data-confirm attribute
 *  5. Auto-dismiss alerts with class "alert-autohide"
 */

document.addEventListener('DOMContentLoaded', function () {

  // ── 1. LOGIN FORM ─────────────────────────────────────────
  // auth/login.php uses: name="email", name="password"
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      let valid = true;

      const email = document.querySelector('[name="email"]');
      if (email && !isValidEmail(email.value.trim())) {
        showError(email, 'Please enter a valid email address.');
        valid = false;
      } else if (email) {
        clearError(email);
      }

      const password = document.querySelector('[name="password"]');
      if (password && password.value.trim() === '') {
        showError(password, 'Password cannot be empty.');
        valid = false;
      } else if (password) {
        clearError(password);
      }

      if (!valid) e.preventDefault();
    });
  }

  // ── 2. REGISTER FORM ──────────────────────────────────────
  // auth/register.php uses: name="username", name="password", name="confirm_password"
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      let valid = true;

      const username = document.querySelector('[name="username"]');
      if (username && username.value.trim().length < 3) {
        showError(username, 'Username must be at least 3 characters.');
        valid = false;
      } else if (username) {
        clearError(username);
      }

      const password = document.querySelector('[name="password"]');
      if (password && password.value.length < 6) {
        showError(password, 'Password must be at least 6 characters.');
        valid = false;
      } else if (password) {
        clearError(password);
      }

      const confirmPassword = document.querySelector('[name="confirm_password"]');
      if (confirmPassword && password) {
        if (confirmPassword.value !== password.value) {
          showError(confirmPassword, 'Passwords do not match.');
          valid = false;
        } else if (confirmPassword.value !== '') {
          clearError(confirmPassword);
        }
      }

      if (!valid) e.preventDefault();
    });
  }

  // ── 3. ADD / EDIT EVENT FORM ──────────────────────────────
  // admin/events/add-event.php and edit-event.php use:
  // name="title", name="date", name="description", name="slots"
  const eventForm = document.getElementById('eventForm');
  if (eventForm) {
    eventForm.addEventListener('submit', function (e) {
      let valid = true;

      const title = document.querySelector('[name="title"]');
      if (title && title.value.trim().length < 3) {
        showError(title, 'Event title must be at least 3 characters.');
        valid = false;
      } else if (title) {
        clearError(title);
      }

      const eventDate = document.querySelector('[name="date"]');
      if (eventDate) {
        if (!eventDate.value) {
          showError(eventDate, 'Please select an event date.');
          valid = false;
        } else {
          clearError(eventDate);
        }
      }

      const description = document.querySelector('[name="description"]');
      if (description && description.value.trim().length < 5) {
        showError(description, 'Please provide a description.');
        valid = false;
      } else if (description) {
        clearError(description);
      }

      const slots = document.querySelector('[name="slots"]');
      if (slots) {
        const slotsVal = parseInt(slots.value, 10);
        if (isNaN(slotsVal) || slotsVal < 1) {
          showError(slots, 'Slots must be at least 1.');
          valid = false;
        } else {
          clearError(slots);
        }
      }

      if (!valid) e.preventDefault();
    });
  }

  // ── 4. DELETE CONFIRMATION ────────────────────────────────
  // Add  data-confirm="Your message here"  to any delete link or button.
  // admin/events/events.php already has its own confirmDelete() function,
  // but this handles any other elements using data-confirm as well.
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      const msg = el.getAttribute('data-confirm') || 'Are you sure you want to delete this?';
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });

  // ── 5. AUTO-DISMISS ALERTS ────────────────────────────────
  // Add class  alert-autohide  to any Bootstrap alert to make it
  // disappear automatically after 4 seconds.
  document.querySelectorAll('.alert-autohide').forEach(function (alertEl) {
    setTimeout(function () {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
      if (bsAlert) bsAlert.close();
    }, 4000);
  });

  // ── HELPER FUNCTIONS ──────────────────────────────────────
  function showError(input, message) {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');

    // Find or create the feedback div right after the input
    let feedback = input.parentElement.querySelector('.invalid-feedback');
    if (!feedback) {
      feedback = document.createElement('div');
      feedback.className = 'invalid-feedback';
      input.parentElement.appendChild(feedback);
    }
    feedback.textContent = message;
  }

  function clearError(input) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

});
