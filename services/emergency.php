<?php
$active_module = 'home';
require 'header.php';
?>

<div class="service-hero">
  <div class="container">
    <div class="service-hero-icon">🚑</div>
    <div class="section-label" style="color:rgba(255,255,255,.7);border-color:rgba(255,255,255,.3);">Our Services</div>
    <h1>Emergency Care</h1>
    <p>Round-the-clock emergency services with rapid response teams and fully equipped trauma bays — because every second counts.</p>
  </div>
</div>

<section class="section">
<div class="container">

  <!-- Stats row -->
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:48px;">
    <div class="stat-highlight"><span class="num">24/7</span><div class="lbl">Always Available</div></div>
    <div class="stat-highlight"><span class="num">&lt;5 min</span><div class="lbl">Response Time</div></div>
    <div class="stat-highlight"><span class="num">30+</span><div class="lbl">ICU Beds</div></div>
    <div class="stat-highlight"><span class="num">100%</span><div class="lbl">Trauma Ready</div></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:32px;">

    <!-- What We Treat -->
    <div class="info-card">
      <h3>🩺 What We Treat</h3>
      <ul class="info-list">
        <li>Cardiac arrests and chest pain emergencies</li>
        <li>Severe trauma from road and industrial accidents</li>
        <li>Stroke and neurological emergencies</li>
        <li>Respiratory distress and breathing difficulties</li>
        <li>Poisoning, overdose and toxic exposure</li>
        <li>Severe burns and major wounds</li>
        <li>Diabetic emergencies and metabolic crises</li>
        <li>Obstetric and gynecological emergencies</li>
      </ul>
    </div>

    <!-- Facilities -->
    <div class="info-card">
      <h3>🏥 Emergency Facilities</h3>
      <ul class="info-list">
        <li>Fully equipped trauma bays with advanced life support</li>
        <li>On-site cardiac catheterization laboratory</li>
        <li>Dedicated emergency CT & MRI scanners</li>
        <li>24/7 blood bank and emergency pharmacy</li>
        <li>Negative pressure isolation rooms</li>
        <li>Paediatric emergency unit</li>
        <li>In-house neurosurgery and cardiac surgery teams</li>
        <li>Helipad for air ambulance transfers</li>
      </ul>
    </div>

  </div>

  <!-- Features chips -->
  <div class="info-card" style="margin-bottom:32px;text-align:center;">
    <h3 style="justify-content:center;">⚡ Key Features</h3>
    <div>
      <span class="feature-chip"><span>🚑</span> Free Ambulance Service</span>
      <span class="feature-chip"><span>👨‍⚕️</span> Dedicated ER Physicians</span>
      <span class="feature-chip"><span>🔬</span> Point-of-Care Lab Testing</span>
      <span class="feature-chip"><span>💊</span> Emergency Pharmacy</span>
      <span class="feature-chip"><span>🧠</span> Neuro Emergency Unit</span>
      <span class="feature-chip"><span>❤️</span> Cardiac Emergency Team</span>
      <span class="feature-chip"><span>🩸</span> 24/7 Blood Bank</span>
      <span class="feature-chip"><span>📡</span> Telemedicine Triage</span>
    </div>
  </div>

  <!-- CTA -->
  <div class="cta-strip">
    <div>
      <h3>Emergency? Call Us Now</h3>
      <p>Our rapid response team is on standby 24 hours a day, 7 days a week.</p>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="tel:+919830000000" class="btn btn-outline-white">📞 +91 98300 00000</a>
      <a href="../patient.php" class="btn" style="background:white;color:var(--primary);">Book a Visit</a>
    </div>
  </div>

</div>
</section>

<?php require 'footer.php'; ?>
