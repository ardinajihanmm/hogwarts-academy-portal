<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome Back — Hogwarts</title>
  <link rel="stylesheet" href="../assets/css/login.css" />
</head>
<body>
 
<div class="cursor-dot"  id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>
<div id="particles"></div>
 
<nav>
  <div class="nav-crest">
    <img src="../assets/img/logo.png" alt="Hogwarts Crest" />
  </div>
  <span class="nav-name">HOGWARTS</span>
</nav>
 
<main>
  <div class="card" id="loginCard">
 
    <div class="card-crest">
      <img src="../assets/img/logo.png" alt="Hogwarts Crest" />
    </div>
 
    <h1 class="card-title">WELCOME BACK</h1>
    <hr class="divider" />
    <p class="card-desc">
      Authorized witches and wizards may enter the academy portal
      using their enchanted credentials.
    </p>
 
    <div class="field">
      <label for="username">Username</label>
      <div class="field-wrap">
        <input type="text" id="username" placeholder="Your wizard name..." autocomplete="off" />
        <span class="field-status" id="usernameStatus"></span>
      </div>
      <p class="hint" id="usernameHint"></p>
    </div>
 
    <!-- Password -->
    <div class="field">
      <label for="password">Password</label>
      <div class="field-wrap">
        <div class="input-wrap">
          <input type="password" id="password" placeholder="Your enchanted password..." />
          <button type="button" class="toggle-pw" data-target="password">👁</button>
        </div>
        <span class="field-status" id="passwordStatus"></span>
      </div>
      <p class="hint" id="passwordHint"></p>
    </div>
 
    <div class="row-options">
      <label class="check-label">
        <input type="checkbox" id="remember" />
        Remember me
      </label>
      <a href="#" class="forgot-link">Forgot Password</a>
    </div>
 
    <button class="btn-submit" id="loginBtn" onclick="handleLogin()">
      LOGIN
    </button>
 
    <p class="card-footer">Don't Have an account? <a href="register.php">Sign in</a></p>
 
  </div>
</main>
 
<script src="../assets/js/main.js"></script>
<script src="../assets/js/login.js"></script>
</body>
</html>