<?php /* pages/index.php — Hogwarts Academy Portal */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hogwarts Academy Portal</title>
  <link rel="stylesheet" href="../assets/css/landing.css"/>
</head>
<body>
 
<div class="cursor-dot"  id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>
<div id="particles"></div>
 
<!-- ═══════════════════════════════════════
     DARK PAGE WRAPPER — latar gelap di luar container
═══════════════════════════════════════ -->
<div class="page-hero">
 
  <!-- ═══ HERO CONTAINER — card besar rounded sesuai Figma ═══ -->
  <section id="hero">
 
    <!-- Background castle -->
    <div class="hero-bg"></div>
    <div class="fog"></div>
    <div class="stars" id="stars"></div>
 
    <!-- Blue corner frame decorations -->
    <div class="hero-frame"></div>
    <div class="corner-tr"></div>
    <div class="corner-bl"></div>
 
    <!-- NAVBAR di dalam container -->
    <nav id="navbar">
      <a href="index.php" class="nav-brand-tab">
        <div class="nav-crest">
          <img src="../assets/img/logo.png" alt="Hogwarts"/>
        </div>
        <span class="nav-wordmark">HOGWARTS</span>
      </a>
      <ul class="nav-links">
        <li><a href="index.php">HOME</a></li>
        <li><a href="#about">ABOUT</a></li>
      </ul>
    </nav>
 
    <!-- HERO TEXT: kiri, agak bawah, bukan pojok -->
    <div class="hero-content">
      <div class="hero-text">
        <p class="welcome-label">WELCOME TO</p>
        <h1 class="hero-title">
          <span>HOGWARTS</span>
          <span>ACADEMY PORTAL</span>
        </h1>
        <div class="hero-buttons">
          <a href="register.php" class="btn btn-register">REGISTER</a>
          <a href="login.php"    class="btn btn-login">LOGIN</a>
        </div>
      </div>
    </div>
 
    <div class="scroll-indicator">
      <span>SCROLL</span>
      <div class="scroll-arrow"></div>
    </div>
 
  </section><!-- end #hero -->
 
</div><!-- end .page-hero -->
 
 
<!-- ═══════════════════════════════════════
     ABOUT SECTION — full width
═══════════════════════════════════════ -->
<section id="about">
 
  <h2 class="section-title">About Hogwarts Academy Portal</h2>
 
  <div class="about-card">
    <div class="about-crest">
      <img src="../assets/img/logo.png" alt="Hogwarts Crest"/>
    </div>
    <p class="about-text">
      Hogwarts Academy Portal adalah portal sekolah sihir Hogwarts
      yang memungkinkan siswa menjelajahi course sihir,
      mempelajari spell, mengumpulkan XP, dan
      meningkatkan level wizard mereka.
    </p>
  </div>
 
  <h3 class="houses-title">HOUSES OF HOGWARTS</h3>
 
  <div class="houses-grid">
    <div class="house-card gryffindor">
      <img src="../assets/img/gryffindor.png" alt="Gryffindor" class="house-logo"/>
      <p class="house-name">Gryffindor</p>
      <p class="house-traits">Bravery<br>Courage<br>Determination</p>
    </div>
    <div class="house-card slytherin">
      <img src="../assets/img/slytherin.png" alt="Slytherin" class="house-logo"/>
      <p class="house-name">Slytherin</p>
      <p class="house-traits">Ambition<br>Leadership<br>Cunning</p>
    </div>
    <div class="house-card ravenclaw">
      <img src="../assets/img/ravenclaw.png" alt="Ravenclaw" class="house-logo"/>
      <p class="house-name">Ravenclaw</p>
      <p class="house-traits">Wisdom<br>Creativity<br>Intelligence</p>
    </div>
    <div class="house-card hufflepuff">
      <img src="../assets/img/hufflepuff.png" alt="Hufflepuff" class="house-logo"/>
      <p class="house-name">Hufflepuff</p>
      <p class="house-traits">Loyalty<br>Patience<br>Hard Work</p>
    </div>
  </div>
 
</section>
 
<footer>
  <p>© 2026 &nbsp;Hogwarts Academy Portal. All rights reserved.</p>
</footer>
 
<script src="../assets/js/main.js"></script>
<script src="../assets/js/landing.js"></script>
</body>
</html>