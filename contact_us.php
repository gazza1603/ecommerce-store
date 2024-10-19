<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Shadow Wood Designs</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<?php include 'partials/header.php'; ?>

<div class="contact-container">
    <h2>Get in Touch with Us</h2>
    <p>You can reach us through any of the platforms below:</p>

    <div class="contact-buttons">
        <a href="https://www.facebook.com/shadowwooddesigns" target="_blank" class="contact-button facebook">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" 
                alt="Facebook Icon"> Facebook
        </a>
        <a href="mailto:shadowwood@example.com" class="contact-button email">
            <img src="https://cdn-icons-png.flaticon.com/512/281/281769.png" alt="Email Icon"> Email Us
        </a>
        <a href="https://wa.me/1234567890" target="_blank" class="contact-button whatsapp">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp Icon"> WhatsApp
        </a>
        <script>
            function showAlert(platform) {
                alert(`You are being redirected to our ${platform} page.`);
            }
            </script>
    </div>
</div>

<?php include 'partials/footer.php'; ?>

</body>
</html>
