<?php
$active_module = 'home';
require 'header.php';
?>

<div class="service-hero">
  <div class="container">
    <div class="service-hero-icon">🦴</div>
    <div class="section-label" style="color:rgba(255,255,255,.7);border-color:rgba(255,255,255,.3);">Our Services</div>
    <h1>Orthopaedics</h1>
    <p>Complete bone and joint care from fracture management to joint replacement surgery — restoring your mobility and quality of life.</p>
  </div>
</div>

<section class="section">
<div class="container">

  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:48px;">
    <div class="stat-highlight"><span class="num">3000+</span><div class="lbl">Surgeries/Year</div></div>
    <div class="stat-highlight"><span class="num">97%</span><div class="lbl">Patient Satisfaction</div></div>
    <div class="stat-highlight"><span class="num">Robotic</span><div class="lbl">Joint Replacement</div></div>
    <div class="stat-highlight"><span class="num">48 hr</span><div class="lbl">Post-Op Mobilisation</div></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:32px;">

    <div class="info-card">
      <h3>🦴 Conditions We Treat</h3>
      <ul class="info-list">
        <li>Fractures — open, closed and complex</li>
        <li>Osteoarthritis of hip, knee and shoulder</li>
        <li>Sports injuries — ligament tears, meniscus, rotator cuff</li>
        <li>Osteoporosis and fragility fractures</li>
        <li>Spinal disorders — disc prolapse, scoliosis, stenosis</li>
        <li>Bone and soft tissue tumours</li>
        <li>Paediatric orthopaedics and congenital deformities</li>
        <li>Rheumatoid arthritis joint involvement</li>
      </ul>
    </div>

    <div class="info-card">
      <h3>🏥 Surgical Procedures</h3>
      <ul class="info-list">
        <li>Total Knee Replacement (TKR) — conventional &amp; robotic</li>
        <li>Total Hip Replacement (THR) — minimally invasive</li>
        <li>Shoulder and ankle arthroplasty</li>
        <li>Arthroscopy — knee, shoulder, hip and ankle</li>
        <li>ACL, PCL and multi-ligament reconstruction</li>
        <li>Spinal fusion, discectomy and laminectomy</li>
        <li>Limb lengthening and deformity correction</li>
        <li>Bone grafting and fixation</li>
      </ul>
    </div>

  </div>

  <div class="info-card" style="margin-bottom:32px;text-align:center;">
    <h3 style="justify-content:center;">🦾 Our Ortho Programme</h3>
    <div>
      <span class="feature-chip"><span>🤖</span> Robotic Joint Replacement</span>
      <span class="feature-chip"><span>🏃</span> Sports Medicine Clinic</span>
      <span class="feature-chip"><span>🦷</span> Arthroscopic Surgery</span>
      <span class="feature-chip"><span>💊</span> Platelet Rich Plasma (PRP)</span>
      <span class="feature-chip"><span>🏋️</span> Physiotherapy &amp; Rehab</span>
      <span class="feature-chip"><span>🩻</span> Intraoperative Imaging</span>
      <span class="feature-chip"><span>👶</span> Paediatric Orthopaedics</span>
      <span class="feature-chip"><span>🧬</span> Stem Cell Therapy</span>
    </div>
  </div>

  <div class="cta-strip">
    <div>
      <h3>Joint Pain Limiting Your Life?</h3>
      <p>Book a consultation with our orthopaedic specialists — most patients walk pain-free within weeks.</p>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="tel:+919830000000" class="btn btn-outline-white">📞 Call Us</a>
      <a href="../patient.php" class="btn" style="background:white;color:var(--primary);">Book Appointment</a>
    </div>
  </div>

</div>
</section>

<?php require 'footer.php'; ?>
