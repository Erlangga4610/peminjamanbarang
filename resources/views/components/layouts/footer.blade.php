<div>
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>PinjamIn</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <p>Designed by <strong>Gill</srong> - Created with ❤️</p>
      
      <!-- Social Icons -->
      <div class="social-icons">
        <a href="https://github.com/[GitHub]" target="_blank">
          <i class="fab fa-github"></i> <!-- GitHub Icon -->
        </a>
        <a href="https://www.linkedin.com/in/[LinkedIn]" target="_blank">
          <i class="fab fa-linkedin"></i> <!-- LinkedIn Icon -->
        </a>
      </div>
    </div>
  </footer><!-- End Footer -->
</div>

<script>
  // Set the current year in the footer
  document.getElementById("year").textContent = new Date().getFullYear();
</script>

<style>
  /* Footer Styling */
  .footer {
    background-color: #f1f1f1; /* Latar belakang lebih terang */
    text-align: center;
    padding: 20px;
    font-family: 'Arial', sans-serif;
    color: #333;
    margin-top: 30px; /* Memberikan jarak dari konten utama */
  }

  .footer p {
    margin: 5px 0;
    font-size: 14px; /* Ukuran font lebih kecil */
  }

  .footer p strong {
    font-weight: bold;
    color: #2d8eff; /* Warna biru cerah untuk nama */
  }

  .footer .social-icons {
    margin-top: 10px;
  }

  .footer .social-icons a {
    text-decoration: none;
    margin: 0 10px;
  }

  .footer .social-icons i {
    font-size: 24px; /* Ukuran ikon */
    transition: transform 0.3s ease-in-out;
  }

  .footer .social-icons i:hover {
    transform: scale(1.2); /* Efek hover pada ikon */
  }

  /* Responsif untuk tampilan mobile */
  @media (max-width: 600px) {
    .footer p {
      font-size: 12px; /* Menyesuaikan ukuran font untuk layar kecil */
    }

    .footer .social-icons a {
      margin: 0 5px;
    }
  }
</style>
