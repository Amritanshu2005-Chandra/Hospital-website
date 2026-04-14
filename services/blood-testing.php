<?php
$active_module = 'home';
require 'header.php';
?>

<div class="service-hero">
  <div class="container">
    <div class="service-hero-icon">🩸</div>
    <div class="section-label" style="color:rgba(255,255,255,.7);border-color:rgba(255,255,255,.3);">Our Services</div>
    <h1>Blood Testing</h1>
    <p>ISO-certified diagnostic labs delivering accurate results with rapid turnaround times — because the right diagnosis starts with the right test.</p>
  </div>
</div>

<section class="section">
<div class="container">

  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:48px;">
    <div class="stat-highlight"><span class="num">500+</span><div class="lbl">Tests Available</div></div>
    <div class="stat-highlight"><span class="num">ISO</span><div class="lbl">Certified Lab</div></div>
    <div class="stat-highlight"><span class="num">4 hrs</span><div class="lbl">Report Turnaround</div></div>
    <div class="stat-highlight"><span class="num">99.9%</span><div class="lbl">Accuracy Rate</div></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:32px;">

    <div class="info-card">
      <h3>🔬 Tests We Offer</h3>
      <ul class="info-list">
        <li>Complete Blood Count (CBC) and differential</li>
        <li>Lipid profile and cardiac markers (CK-MB, Troponin)</li>
        <li>Liver function tests (LFT) and kidney function (KFT)</li>
        <li>Blood glucose, HbA1c and insulin levels</li>
        <li>Thyroid function (TSH, T3, T4)</li>
        <li>Infectious disease serology (HIV, Hepatitis, VDRL)</li>
        <li>Tumour markers (PSA, CA-125, CEA)</li>
        <li>Coagulation profile (PT, APTT, D-dimer)</li>
      </ul>
    </div>

    <div class="info-card">
      <h3>🏥 Lab Capabilities</h3>
      <ul class="info-list">
        <li>Fully automated haematology and biochemistry analysers</li>
        <li>Microbiology cultures and sensitivity (C&S) testing</li>
        <li>Flow cytometry for immunology panels</li>
        <li>Molecular diagnostics — PCR and real-time PCR</li>
        <li>Histopathology and cytopathology examination</li>
        <li>NABL-accredited quality control protocols</li>
        <li>Online report delivery via portal and email</li>
        <li>Home sample collection available</li>
      </ul>
    </div>

  </div>

  <div class="info-card" style="margin-bottom:32px;text-align:center;">
    <h3 style="justify-content:center;">🔬 Lab Features</h3>
    <div>
      <span class="feature-chip"><span>🧪</span> NABL Accredited</span>
      <span class="feature-chip"><span>🏠</span> Home Sample Collection</span>
      <span class="feature-chip"><span>📧</span> Digital Reports</span>
      <span class="feature-chip"><span>⚡</span> Stat Results Available</span>
      <span class="feature-chip"><span>🔒</span> Confidential Reports</span>
      <span class="feature-chip"><span>📱</span> WhatsApp Delivery</span>
      <span class="feature-chip"><span>💳</span> Insurance Covered</span>
      <span class="feature-chip"><span>🔬</span> PCR &amp; Molecular Tests</span>
    </div>
  </div>

  <div class="cta-strip">
    <div>
      <h3>Book a Lab Test Today</h3>
      <p>Walk in or schedule your blood work through our patient portal — reports in as little as 4 hours.</p>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="tel:+919830000000" class="btn btn-outline-white">📞 Call Lab</a>
      <a href="../patient.php" class="btn" style="background:white;color:var(--primary);">Book Now</a>
    </div>
  </div>

</div>
</section>

<?php require 'footer.php'; ?>
