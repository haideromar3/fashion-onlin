<style>
  body {
    padding-bottom: 0; /* سيتم تعديله عبر JS */
  }

  footer.fixed-footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #1c1c1c;
    color: #ddd;
    z-index: 1030;
    font-size: 0.85rem;
    padding: 8px 0;
    box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.3);
  }

  footer.fixed-footer a {
    color: #ccc;
    transition: color 0.3s;
  }

  footer.fixed-footer a:hover {
    color: #fff;
  }

  footer.fixed-footer i {
    font-size: 1.1rem;
  }
</style>

<footer class="fixed-footer">
  <div class="container text-center">
    <p class="mb-1">&copy; {{ date('Y') }} جميع الحقوق محفوظة لموقعك.</p>

    <div class="d-flex justify-content-center flex-wrap small mb-1 gap-2">
      <span><i class="fas fa-phone-alt me-1"></i> +963 999 999 999</span>
      <span>|</span>
      <span>+963 888 888 888</span>
    </div>

    <div>
      <strong class="me-1">تابعنا:</strong>
      <a href="https://facebook.com" class="me-2" target="_blank"><i class="fab fa-facebook-f"></i></a>
      <a href="https://twitter.com" class="me-2" target="_blank"><i class="fab fa-twitter"></i></a>
      <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</footer>

<script>
  function adjustBodyPadding() {
    const footer = document.querySelector('footer.fixed-footer');
    const footerHeight = footer.offsetHeight;
    document.body.style.paddingBottom = footerHeight + 'px';
  }

  window.addEventListener('load', adjustBodyPadding);
  window.addEventListener('resize', adjustBodyPadding);
</script>
