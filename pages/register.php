<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Wizard Registration — Hogwarts</title>
  <link rel="stylesheet" href="../assets/css/register.css" />
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
  <div class="card">
 
    <div class="card-crest">
      <img src="../assets/img/logo.png" alt="Hogwarts Crest" />
    </div>
 
    <h1 class="card-title">WIZARD REGISTRATION</h1>
    <hr class="divider" />
    <p class="card-desc">
      Every young witch and wizard must complete this registration before
      receiving their Hogwarts acceptance letter. False identities and duplicate
      registrations are strictly prohibited by the Ministry of Magic.
    </p>
 
    <div class="field">
      <label for="username">Username</label>
      <div class="field-wrap">
        <input type="text" id="username" placeholder="e.g. HarryPotter7" autocomplete="off" />
        <span class="field-status" id="usernameStatus"></span>
      </div>
      <p class="hint" id="usernameHint"></p>
    </div>
 
    <div class="field">
      <label for="email">Email</label>
      <div class="field-wrap">
        <input type="email" id="email" placeholder="wizard@hogwarts.edu" autocomplete="off" />
        <span class="field-status" id="emailStatus"></span>
      </div>
      <p class="hint" id="emailHint"></p>
    </div>
 
    <div class="field">
      <label for="password">Password</label>
      <div class="field-wrap">
        <div class="input-wrap">
          <input type="password" id="password" placeholder="Choose a strong spell..." />
          <button type="button" class="toggle-pw" data-target="password">👁</button>
        </div>
        <span class="field-status" id="passwordStatus"></span>
      </div>
      <div class="strength-bar">
        <div class="strength-seg" id="s1"></div>
        <div class="strength-seg" id="s2"></div>
        <div class="strength-seg" id="s3"></div>
        <div class="strength-seg" id="s4"></div>
      </div>
      <span id="strengthLabel"></span>
      <p class="hint" id="passwordHint"></p>
    </div>
 
    <div class="field">
      <label for="confirm">Confirm Password</label>
      <div class="field-wrap">
        <div class="input-wrap">
          <input type="password" id="confirm" placeholder="Repeat your spell..." />
          <button type="button" class="toggle-pw" data-target="confirm">👁</button>
        </div>
        <span class="field-status" id="confirmStatus"></span>
      </div>
      <p class="hint" id="confirmHint"></p>
    </div>
 
    <div class="field">
      <label for="role">Role</label>
      <div class="field-wrap">
        <select id="role">
          <option value="" disabled selected>Select your house / role</option>
          <option value="gryffindor">Student — Gryffindor</option>
          <option value="slytherin">Student — Slytherin</option>
          <option value="hufflepuff">Student — Hufflepuff</option>
          <option value="ravenclaw">Student — Ravenclaw</option>
          <option value="teacher">Professor</option>
          <option value="admin">Administrator</option>
        </select>
        <span class="field-status" id="roleStatus"></span>
      </div>
      <p class="hint" id="roleHint"></p>
    </div>
 
    <button class="btn-submit" id="registerBtn" onclick="handleRegister()">
      REGISTER NOW!
    </button>
 
    <p class="card-footer">Have an account? <a href="login.php">Log in</a></p>
 
  </div>
</main>
 
<script src="../assets/js/main.js"></script>
<script src="../assets/js/register.js"></script>
</body>
</html>