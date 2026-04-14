<?php
$active_module = 'home';
require 'header.php';
?>

<div class="service-hero">
  <div class="container">
    <div class="service-hero-icon">❤️</div>
    <div class="section-label" style="color:rgba(255,255,255,.7);border-color:rgba(255,255,255,.3);">Our Services</div>
    <h1>Cardiology</h1>
    <p>Advanced cardiac care including ECG, echocardiography, angiography and bypass surgery — your heart is in expert hands.</p>
  </div>
</div>

<section class="section">
<div class="container">

  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:48px;">
    <div class="stat-highlight"><span class="num">10k+</span><div class="lbl">Hearts Treated</div></div>
    <div class="stat-highlight"><span class="num">98%</span><div class="lbl">Surgery Success</div></div>
    <div class="stat-highlight"><span class="num">8</span><div class="lbl">Cath Labs</div></div>
    <div class="stat-highlight"><span class="num">24/7</span><div class="lbl">Cardiac ICU</div></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:32px;">

    <div class="info-card">
      <h3>❤️ Diagnostic Services</h3>
      <ul class="info-list">
        <li>12-lead ECG and Holter monitoring (24/48 hr)</li>
        <li>2D Echocardiography and Colour Doppler</li>
        <li>Stress test (TMT) and nuclear stress test</li>
        <li>Coronary CT Angiography (CCTA)</li>
        <li>Cardiac MRI for structural assessment</li>
        <li>Electrophysiology studies (EPS)</li>
        <li>Transesophageal echocardiography (TEE)</li>
        <li>Ambulatory blood pressure monitoring</li>
      </ul>
    </div>

    <div class="info-card">
      <h3>🏥 Interventional &amp; Surgical</h3>
      <ul class="info-list">
        <li>Coronary angiography and angioplasty (PTCA)</li>
        <li>Drug-eluting stent implantation</li>
        <li>Coronary artery bypass graft (CABG)</li>
        <li>Heart valve repair and replacement</li>
        <li>Pacemaker and ICD implantation</li>
        <li>Ablation therapy for arrhythmias</li>
        <li>Aortic aneurysm repair</li>
        <li>Congenital heart disease correction</li>
      </ul>
    </div>

  </div>

  <div class="info-card" style="margin-bottom:32px;text-align:center;">
    <h3 style="justify-content:center;">🫀 Our Cardiac Programme</h3>
    <div>
      <span class="feature-chip"><span>🏥</span> Dedicated Cardiac ICU</span>
      <span class="feature-chip"><span>⚡</span> Primary Angioplasty 24/7</span>
      <span class="feature-chip"><span>🔬</span> Cardiac Cath Lab</span>
      <span class="feature-chip"><span>👨‍⚕️</span> DM Cardiologists</span>
      <span class="feature-chip"><span>💓</span> Cardiac Rehab Programme</span>
      <span class="feature-chip"><span>📱</span> Remote Monitoring</span>
      <span class="feature-chip"><span>🧬</span> Preventive Cardiology</span>
      <span class="feature-chip"><span>👶</span> Paediatric Cardiology</span>
    </div>
  </div>

  <div class="cta-strip">
    <div>
      <h3>Chest Pain? Don't Wait.</h3>
      <p>For cardiac emergencies, call immediately. For routine consultations, book an appointment online.</p>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="tel:+919830000000" class="btn btn-outline-white">🚨 Emergency Line</a>
      <a href="../patient.php" class="btn" style="background:white;color:var(--primary);">Book Consultation</a>
    </div>
  </div>

</div>
</section>

<?php require 'footer.php'; ?>
