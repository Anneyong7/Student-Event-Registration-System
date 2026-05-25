document.addEventListener('DOMContentLoaded', function () {

  // ── 1. LOGIN FORM VALIDATION ──────────────────────────────
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      let valid = true;

      const username = document.querySelector('[name="username"]');
      if (username && username.value.trim() === '') {
        showError(username, 'Username field cannot be left blank.');
        valid = false;
      } else if (username) {
        clearError(username);
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

  // ── 2. REGISTER FORM VALIDATION ───────────────────────────
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

  // ── 3. ADD / EDIT EVENT FORM VALIDATION ───────────────────
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
        showError(description, 'Please provide a clear description.');
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

  // ── 4. GLOBAL DELETE CONFIRMATIONS ────────────────────────
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      const msg = el.getAttribute('data-confirm') || 'Are you sure you want to proceed?';
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });

  // ── 5. AUTO-DISMISS INTERFACES ───────────────────────────
  document.querySelectorAll('.alert-dismissible').forEach(function (alertEl) {
    setTimeout(function () {
      if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
        if (bsAlert) bsAlert.close();
      }
    }, 4000);
  });

  // ── HELPERS ───────────────────────────────────────────────
  function showError(input, message) {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');

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
});
