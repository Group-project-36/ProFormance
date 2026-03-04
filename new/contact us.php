<?php
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submitted = true;

    $name = $_POST["name"] ?? "";
    $email = $_POST["email"] ?? "";
    $subject = $_POST["subject"] ?? "";
    $message = $_POST["message"] ?? "";
}
?>

<?php
$title = 'Contact – ProFormance';
include __DIR__ . '/../header.php';
?>

<main class="page page--contact">

  <header class="page-hero">
    <p class="hero-tag">PROFORMANCE SUPPORT</p>
    <h1 class="hero-title">Contact</h1>
    <p class="hero-subtitle">
      Fast help. Real answers. Whether it’s an order update, returns, or product advice —
      we’ll get you back to training with zero hassle.
    </p>
  </header>

  <?php if ($submitted): ?>
    <div class="alert alert--success" role="status" aria-live="polite">
      <strong>Message sent.</strong>
      Thanks, <?= htmlspecialchars($name) ?> — our team will respond as soon as possible.
    </div>
  <?php endif; ?>

  <!-- Premium layout: two-column on desktop (later via global CSS) -->
  <section class="contact-shell">

    <!-- Left: form card -->
    <section class="card card--form" aria-labelledby="contactFormTitle">
      <header class="card-header">
        <h2 id="contactFormTitle">Send us a message</h2>
        <p class="card-subtitle">
          Fill this in and we’ll reply to <strong><?= htmlspecialchars($email ?: "your email") ?></strong>.
        </p>
      </header>

      <form method="POST" class="form" novalidate>

        <div class="form-grid">

          <div class="field">
            <label for="name">Your name <span aria-hidden="true">*</span></label>
            <input id="name" type="text" name="name" placeholder="e.g., Ayaan Sabeel" required>
          </div>

          <div class="field">
            <label for="email">Email address <span aria-hidden="true">*</span></label>
            <input id="email" type="email" name="email" placeholder="e.g., ayan@email.com" required>
          </div>

        </div>

        <div class="field">
          <label for="subject">Subject <span aria-hidden="true">*</span></label>
          <select id="subject" name="subject" required>
            <option value="" selected disabled>Choose an option</option>
            <option value="Order Question">Order question</option>
            <option value="Product Question">Product question</option>
            <option value="Returns or Exchanges">Returns / exchanges</option>
            <option value="General Inquiry">General inquiry</option>
          </select>
        </div>

        <div class="field">
          <label for="message">Your message <span aria-hidden="true">*</span></label>
          <textarea id="message" name="message" rows="7" placeholder="Tell us what you need help with…" required></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn--primary">Send message</button>
          <p class="form-note">
            Typical response time: <strong>within 24 hours</strong> (Mon–Fri).
          </p>
        </div>

      </form>
    </section>

    <aside class="card card--info" aria-labelledby="supportTitle">
      <header class="card-header">
        <h2 id="supportTitle">Support details</h2>
        <p class="card-subtitle">
          Prefer email or phone? Here’s the fastest way to reach us.
        </p>
      </header>

      <div class="info-stack">

        <section class="info-block">
          <h3>Contact</h3>
          <p><strong>Email:</strong> <a href="mailto:support@proformance.com">support@proformance.com</a></p>
          <p><strong>Phone:</strong> <a href="tel:+447450123456">+44 7450 123456</a></p>
        </section>

        <section class="info-block">
          <h3>Business hours</h3>
          <ul class="hours">
            <li><span>Monday – Friday</span> <span>9:00 AM – 6:00 PM</span></li>
            <li><span>Saturday</span> <span>10:00 AM – 4:00 PM</span></li>
            <li><span>Sunday</span> <span>Closed</span></li>
          </ul>
        </section>

        <section class="info-block">
          <h3>Quick help</h3>
          <ul class="quick-links">
            <li><a href="/orders">Track an order</a></li>
            <li><a href="/returns">Returns & exchanges</a></li>
            <li><a href="/size-guide">Sizing guide</a></li>
          </ul>
        </section>

        <section class="info-block">
          <h3>Follow</h3>
          <nav class="social-links" aria-label="ProFormance social links">
            <a href="#" rel="noopener">Instagram</a>
            <a href="#" rel="noopener">Facebook</a>
            <a href="#" rel="noopener">TikTok</a>
          </nav>
        </section>

      </div>
    </aside>

  </section>


  <section class="fineprint">
    <p>
      For security, please don’t include card details in your message.
    </p>
  </section>

</main>

<?php include __DIR__ . '/../footer.php'; ?>