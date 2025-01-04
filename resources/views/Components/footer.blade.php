<style>
    /* General Footer Styles */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    .footer {
        background-color: rgb(236, 234, 234);
        text-align: center;
        font-family: 'Poppins', sans-serif;
        padding: 20px 20px 0 20px;
    }
    .footer-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 10px;
    }

    /* Footer Sections */
    .footer-section {
        flex: 1;
        min-width: 150px;
        margin: 10px;
        text-align: left;
    }

    .footer-logo {
        max-height: 70px;
        margin-bottom: 10px;
    }

    /* Links Styling */
    .footer-links {
        list-style: none;
        padding: 0;
        text-decoration: none;
        color:black;
    }

    .footer-links li {
        margin: 5px 0;
        text-decoration: none;
    }

    .footer-links a {
        text-decoration: none;
    }

    .footer-links a:hover {
        text-decoration: none;
    }

    /* Social Media Links */
    .social-link {
        margin: 0 10px;
        color: white;
        text-decoration: none;
    }

    .social-link:hover {
        text-decoration: underline;
    }

    /* Footer Bottom */
    .footer-bottom {
        font-size: 14px;
        background:#228b22;
    }

</style>


<footer class="footer mt-5">
    <div class="footer-container">
      <!-- Logo and Description -->
      <div class="footer-section text-center">
        <img src="{{ asset('images/assets/bicollectionlogowname2.png') }}" alt="" class="footer-logo">
        <p>BiCollection is your go-to platform for authentic Bicol native products. Explore, shop, and support local merchants.</p>
      </div>
      <div class="footer-section text-center">
        <h5>USABLE LINKS</h5>
        <ul class="footer-links">
            <li><a href="terms-of-use.html">Dashboard</a></li>
            <li><a href="privacy-policy.html">Sell on Bicollection</a></li>
            <li><a href="terms-of-use.html">Customer Support</a></li>
            <li><a href="terms-of-use.html">About Us</a></li>
          </ul>
      </div>

      <!-- Terms and Policies -->
      <div class="footer-section text-center">
        <h5>TERMS AND POLICIES</h5>
        <ul class="footer-links">
          <li><a href="privacy-policy.html">Privacy Policy</a></li>
          <li><a href="terms-of-use.html">Terms of Use</a></li>
        </ul>
      </div>

      <!-- Contact Us -->
      <div class="footer-section text-center">
        <h5>CONTACT US</h5>
        <p style="font-size:16px;">
          📍 123 Bicol Street, Legazpi City, Albay <br>
          ☎ +63 912 345 6789 <br>
          ✉ <a style="font-size:14px;" href="mailto:bicollection.noreply@gmail.com">bicollection.noreply@gmail.com</a>
        </p>
      </div>

    </div>

    <!-- Footer Bottom Text -->

  </footer>
  <div class="footer-bottom text-center">
    <p style="margin: 0; color:#fafafa">&copy; 2025 BiCollection. All rights reserved.</p>
  </div>
