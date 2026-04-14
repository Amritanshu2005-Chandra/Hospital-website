<?php
$active_module = 'home';
require 'header.php';
?>

<div class="service-hero">
  <div class="container">
    <div class="service-hero-icon">🧠</div>
    <div class="section-label" style="color:rgba(255,255,255,.7);border-color:rgba(255,255,255,.3);">Our Services</div>
    <h1>Neurology</h1>
    <p>Expert neurological diagnosis and treatment for brain, spine and nervous system disorders — combining cutting-edge imaging with personalised care.</p>
  </div>
</div>

<section class="section">
<div class="container">

  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:48px;">
    <div class="stat-highlight"><span class="num">5000+</span><div class="lbl">Patients Treated</div></div>
    <div class="stat-highlight"><span class="num">3T</span><div class="lbl">MRI Scanner</div></div>
    <div class="stat-highlight"><span class="num">95%</span><div class="lbl">Diagnostic Accuracy</div></div>
    <div class="stat-highlight"><span class="num">24/7</span><div class="lbl">Stroke Response</div></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:32px;">

    <div class="info-card">
      <h3>🧠 Conditions We Treat</h3>
      <ul class="info-list">
        <li>Stroke (ischaemic and haemorrhagic) — clot-busting therapy</li>
        <li>Epilepsy and seizure disorders</li>
        <li>Parkinson's disease and movement disorders</li>
        <li>Multiple sclerosis and demyelinating diseases</li>
        <li>Alzheimer's and other dementias</li>
        <li>Migraine, cluster headache and facial pain</li>
        <li>Peripheral neuropathy and nerve disorders</li>
        <li>Brain and spinal cord tumours</li>
      </ul>
    </div>

    <div class="info-card">
      <h3>🏥 Neuro Diagnostics</h3>
      <ul class="info-list">
        <li>3 Tesla MRI Brain &amp; Spine with advanced sequences</li>
        <li>CT Brain, perfusion and angiography</li>
        <li>Electroencephalography (EEG &amp; video EEG)</li>
        <li>Nerve Conduction Study (NCS) and EMG</li>
        <li>Lumbar puncture and CSF analysis</li>
        <li>Evoked potentials (VEP, BAER, SSEP)</li>
        <li>Neuropsychological testing and cognitive assessment</li>
        <li>Neuro-interventional procedures (coiling, thrombolysis)</li>
      </ul>
    </div>

  </div>

  <div class="info-card" style="margin-bottom:32px;text-align:center;">
    <h3 style="justify-content:center;">🧠 Neurology Highlights</h3>
    <div>
      <span class="feature-chip"><span>⚡</span> Stroke Unit &amp; Thrombolysis</span>
      <span class="feature-chip"><span>🧬</span> Genetic Neurological Testing</span>
      <span class="feature-chip"><span>🤖</span> Deep Brain Stimulation</span>
      <span class="feature-chip"><span>🏃</span> Neuro-Rehabilitation</span>
      <span class="feature-chip"><span>📊</span> Video EEG Monitoring</span>
      <span class="feature-chip"><span>👶</span> Paediatric Neurology</span>
      <span class="feature-chip"><span>💊</span> Botox Therapy for Migraine</span>
      <span class="feature-chip"><span>🌙</span> Sleep Disorder Clinic</span>
    </div>
  </div>

  <div class="cta-strip">
    <div>
      <h3>Stroke Symptoms? Act FAST.</h3>
      <p>Face drooping, Arm weakness, Speech difficulty — Time to call. Every minute of delay costs brain cells.</p>
    </div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <a href="tel:+919830000000" class="btn btn-outline-white">🚨 Call Emergency</a>
      <a href="../patient.php" class="btn" style="background:white;color:var(--primary);">Book Neurologist</a>
    </div>
  </div>

</div>
</section>

<?php require 'footer.php'; ?>
