<?php
// header.php — shared navigation include
// Usage: require 'header.php'; (set $active_module before including)
$active_module = $active_module ?? 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chandra &amp; Sons Hospital — <?= ucfirst($active_module) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<nav class="site-nav">
  <div class="nav-inner">
    <a href="index.php" class="nav-brand">
      <div class="nav-logo">🏥</div>
      <div class="nav-brand-text">
        <strong>Chandra &amp; Sons</strong>
        <small>Est. 2001 · Kolkata</small>
      </div>
    </a>
    <div class="nav-links">
      <a href="index.php"   class="nav-link <?= $active_module==='home'    ?'active':'' ?>">Home</a>
      <a href="patient.php" class="nav-link <?= $active_module==='patient' ?'active':'' ?>">Patient Portal</a>
      <a href="doctor.php"  class="nav-link <?= $active_module==='doctor'  ?'active':'' ?>">Doctors</a>
      <a href="staff.php"   class="nav-link <?= $active_module==='staff'   ?'active':'' ?>">Staff</a>
      <a href="patient.php" class="nav-link nav-cta">Book Appointment</a>
    </div>
  </div>
</nav>
