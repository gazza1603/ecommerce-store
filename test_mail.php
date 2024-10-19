<?php
$to = "your-email@gmail.com";
$subject = "Test Email";
$message = "This is a test email from XAMPP.";
$headers = "From: no-reply@ecommerce-store.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
?>
