<style>
.footer {
    background-color: #449c44; 
    color: #fff; 
    padding: 40px 20px;
    font-family: 'Futura PT', sans-serif; 
}

.footer-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    max-width: 1300px;
    margin: auto;
}

.footer-section {
    flex: 1 1 150px;
    margin: 15px;
    min-width: 150px; 
}

.footer-logo {
    max-width: 250px;
    margin-bottom: 10px;
}

.footer-text {
    color: #000; 
    font-size: 18px;
    line-height: 2; 
}

.footer-list {
    list-style: none;
    line-height: 1.6; 
    padding: 0;
}

.footer-link {
    color: #fffd8a; 
    text-decoration: none;
    font-size: 18px;
}

.footer-link:hover {
    color: #ffd700;
}

.footer-icon {
    color: #fffd8a;
    text-decoration: none;
    font-size: 20px;
    display: flex;
    align-items: center;
}

.footer-icon:hover{
    color: #ffd700;
}

.footer-address {
    color: #000;
    font-size: 18px;
    line-height: 1; 
}
.logo-img {
    width: 200px;    /* Adjust the width as needed */
    height: auto;    /* Maintain aspect ratio */
}

@media (max-width: 700px) {
    .footer-container {
        flex-direction: column;
        align-items: center;
    }

    .footer-section {
        text-align: center;
    }
}
</style>


<footer class="footer mt-4">
    <div class="footer-container">
        <!-- Logo and About -->
        <div class="footer-section text-center">
            <img src="{{ asset('images/assets/bicollectionlogowname2.png') }}" alt="BiCollection Logo" class="logo-img">
            <p class="footer-text">
                BiCollection is your go-to platform for authentic Bicol native products. Explore, shop, and support local merchants.
            </p>
        </div>

        <!-- Other Links -->
        <div class="footer-section">
            <h4>Terms and Policies</h4>
            <ul class="footer-list">
                <li><a href="#privacy-policy" class="footer-link">Privacy Policy</a></li>
                <li><a href="#terms-of-use" class="footer-link">Terms of Use</a></li>
            </ul>
        </div>

        <!-- Follow Us -->
        <div class="footer-section">
            <h4>Follow Us</h4>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <div>
                <p>
                    <a href="#" class="fa fa-facebook footer-icon"> Facebook</a>
                </p>
                <p>
                    <a href="#" class="fa fa-instagram footer-icon"> Instagram</a>
                </p>
                <p>
                    <a href="#" class="fa fa-linkedin footer-icon"> LinkedIn</a>
                </p>
            </div>
        </div>

        <!-- Address -->
        <div class="footer-section">
            <h4>Address</h4>
            <p class="footer-address">123 Bicol Street, Legazpi City, Albay</p>
            <p class="footer-address">📞 +63 912 345 6789</p>
            <p class="footer-address">📧 bicollection.noreply@gmail.com</p>
        </div>
    </div>
    <div style="text-align: center; color: #ffffff;">
        &copy; 2024 BiCollection. All rights reserved.
    </div>     
</footer>