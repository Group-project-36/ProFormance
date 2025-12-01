<?php
// --- SIMPLE FORM HANDLING ---
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submitted = true;

    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us – ProFormance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #000; /* Black */
            color: #fff; /* White text */
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 40px;
        }

        h1, h2, h3 {
            color: #FFD700; /* Gold */
        }

        .form-box {
            background: #111; 
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(255, 215, 0, 0.2); /* Gold glow */
            margin-bottom: 30px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #444; /* Dark grey */
            background: #222; /* Dark background */
            color: white;
        }

        input::placeholder, textarea::placeholder {
            color: #ccc;
        }

        button {
            background: #FFD700; /* Gold */
            color: #000;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #e6c200; /* Slightly darker gold */
        }

        .success {
            background: #1e3a1f;
            color: #9fff9f;
            padding: 12px;
            margin-bottom: 20px;
            border-left: 4px solid #00ff00;
        }

        .info-box {
            padding: 20px;
            background: #111;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(255, 215, 0, 0.15);
        }

        a {
            color: #FFD700;
        }
        a:hover {
            color: #fff;
        }

    </style>
</head>
<body>

<div class="container">

    <h1>Contact Us – ProFormance</h1>
    <p>Have a question about your order, delivery, or our products? The ProFormance support team is here to help.</p>

    <?php if ($submitted): ?>
        <div class="success">
            Thank you, <?= htmlspecialchars($name) ?>. Your message has been sent.  
            Our ProFormance support team will respond as soon as possible.
        </div>
    <?php endif; ?>

    <!-- Contact Form -->
    <div class="form-box">
        <form method="POST">

            <label>Your Name *</label>
            <input type="text" name="name" required>

            <label>Email Address *</label>
            <input type="email" name="email" required>

            <label>Subject *</label>
            <select name="subject" required>
                <option value="">Choose an option</option>
                <option value="Order Question">Order Question</option>
                <option value="Product Question">Product Question</option>
                <option value="Returns or Exchanges">Returns or Exchanges</option>
                <option value="General Inquiry">General Inquiry</option>
            </select>

            <label>Your Message *</label>
            <textarea name="message" rows="6" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <!-- Information Section -->
    <div class="info-box">
        <h2>Customer Support – ProFormance</h2>

        <p><strong>Email:</strong> support@proformance.com</p>
        <p><strong>Phone:</strong> +44 7450 123456</p>

        <h3>Business Hours</h3>
        <p>Monday – Friday: 9:00 AM – 6:00 PM</p>
        <p>Saturday: 10:00 AM – 4:00 PM</p>
        <p>Sunday: Closed</p>

        <h3>Follow ProFormance</h3>
        <p>
            <a href="#">Instagram</a> |
            <a href="#">Facebook</a> |
            <a href="#">TikTok</a>
        </p>
    </div>

</div>

</body>
</html>
