<?php
/**
 * includes/footer.php
 * Shared page footer. Include at the BOTTOM of every PHP page.
 *
 * USAGE:
 *   include '../includes/footer.php';
 *   (use '../../includes/footer.php' for pages 2 levels deep)
 */
?>

</main><!-- /.edu-main -->

<footer class="edu-footer">
  <div class="container">
    <div class="row align-items-center py-3">
      <div class="col-md-6 text-center text-md-start mb-1 mb-md-0">
        <span class="edu-footer-brand">EduEvents</span>
        <span class="edu-footer-sep mx-2">&mdash;</span>
        <span class="edu-footer-tag">Student Event Registration System</span>
      </div>
      <div class="col-md-6 text-center text-md-end">
        <small class="text-muted">&copy; <?php echo date('Y'); ?></small>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap 5.3 JS Bundle -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>

<!-- Custom JS -->
<script src="<?php echo $rootPath ?? '../'; ?>assets/js/main.js"></script>

</body>
</html>
