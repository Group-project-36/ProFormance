<!DOCTYPE html>
<form action="registration_page.php">
<head>
    <meta charset="UTF-8">
  <title>Change Password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Change Password</h2> 
      <label for="current-password">
Current Password</label>
      <input type="password" id="current-password" 
        name="current-password" required>
<label for="new-password">
    New Password</label>
      <input type="password" id="new-password" 
        name="new-password" required>
<label for="confirm-password">
  Confirm New Password</label>
      <input type="password" id="confirm-password" 
        name="confirm-password" required>
<button type="submit">
  Update Password</button>
    </form>
  </div>
</body>
</html>
