<?php
$active_module = 'home';
require 'header.php';
?>

<div class="service-hero">
  <div class="container">
    <div class="service-hero-icon">💊</div>
    <div class="section-label" style="color:rgba(255,255,255,.7);border-color:rgba(255,255,255,.3);">Our Services</div>
    <h1>Pharmacy &amp; Medicine</h1>
    <p>In-house pharmacy with a comprehensive drug inventory and expert pharmacist consultation — your prescriptions filled under one roof.</p>
  </div>
</div>

<section class="section">
<div class="container">

  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:48px;">
    <div class="stat-highlight"><span class="num">5000+</span><div class="lbl">Drug SKUs</div></div>
    <div class="stat-highlight"><span class="num">24/7</span><div class="lbl">Open Always</div></div>
    <div class="stat-highlight"><span class="num">15 min</span><div class="lbl">Avg Fill Time</div></div>
    <div class="stat-highlight"><span class="num">100%</span><div class="lbl">Genuine Medicines</div></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:32px;">

    <div class="info-card">
      <h3>💊 Our Drug Inventory</h3>
      <ul class="info-list">
        <li>Branded and generic prescription medicines</li>
        <li>Over-the-counter (OTC) medications</li>
        <li>Oncology and specialty drugs</li>
        <li>Paediatric formulations and syrups</li>
        <li>Vaccines and biological products</li>
        <li>Controlled substances (with valid prescription)</li>
        <li>Ayurvedic and herbal supplements</li>
        <li>Medical consumables, bandages and devices</li>
      </ul>
    </div>

    <div class="info-card">
      <h3>👨‍⚕️ Pharmacist Services</h3>
      <ul class="info-list">
        <li>Prescription verification and drug interaction checks</li>
        <li>Medication counselling and dosage guidance</li>
        <li>Compounding of customised formulations</li>
        <li>Chronic disease medication management</li>
        <li>Home delivery of regular prescriptions</li>
        <li>Immunisation and vaccination support</li>
        <li>Medication adherence tracking</li>
        <li>Insurance claim assistance</li>
      </ul>
    </div>

  </div>

  <div class="info-card" style="margin-bottom:32px;text-align:center;">
    <h3 style="justify-content:center;">✨ Why Our Pharmacy?</h3>
    <div>
      <span class="feature-chip"><span>🏥</span> In-Hospital Location</span>
      <span class="feature-chip"><span>🔒</span> Cold Chain Maintained</span>
      <span class="feature-chip"><span>📱</span> Digital Prescriptions</span>
      <span class="feature-chip"><span>🚚</span> Home Delivery</span>
      <span class="feature-chip"><span>💳</span> Insurance Accepted</span>
      <span class="feature-chip"><span>👩‍⚕️</span> Expert Pharmacists</span>
      <span class="feature-chip"><span>📋</span> Medication Records</span>
      <span class="feature-chip"><span>✅</span> Quality Guaranteed</span>
    </div>
  </div>

  <div class="cta-strip">
    <div>
      <h3>Need a Prescription Filled?</h3>
      <p>Visit our pharmacy in-person or book a doctor first — we're always ready.</p>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="tel:+919830000000" class="btn btn-outline-white">📞 Contact Us</a>
      <a href="../patient.php" class="btn" style="background:white;color:var(--primary);">Book Appointment</a>
    </div>
  </div>

</div>
</section>

<?php require 'footer.php'; ?>
