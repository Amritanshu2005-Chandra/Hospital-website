<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$conn = new mysqli("localhost", "root", "Amrit@2020", "doctor_booking");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Fetch doctors for team carousel
$docs = [];
$res = $conn->query("SELECT name, dept FROM users WHERE role='doctor' LIMIT 6");
if ($res) { while ($r = $res->fetch_assoc()) $docs[] = $r; }

$active_module = 'home';
require 'header.php';
?>

<!-- ══ HERO SLIDER ══════════════════════════════════════════════ -->
<div class="hero-slider" id="heroSlider">
  <div class="hero-slides" id="heroSlides">

    <!-- Slide 1 -->
    <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1400&q=80')">
      <div class="hero-content">
        <h5>Welcome to Chandra &amp; Sons Hospital</h5>
        <h1>Advanced Care,<br>Human Touch</h1>
        <p>State-of-the-art medical facilities combined with compassionate care — serving Kolkata since 2001.</p>
        <div class="hero-btns">
          <a href="patient.php" class="btn btn-primary">Book Appointment</a>
          <a href="#about" class="btn btn-outline-white">Learn More</a>
        </div>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1584982751601-97dcc096659c?w=1400&q=80')">
      <div class="hero-content">
        <h5>Expert Medical Specialists</h5>
        <h1>Qualified Doctors,<br>Trusted Expertise</h1>
        <p>Our team of 50+ specialists across all major departments deliver world-class healthcare to every patient.</p>
        <div class="hero-btns">
          <a href="#team" class="btn btn-primary">Meet Our Doctors</a>
          <a href="patient.php" class="btn btn-outline-white">Book Now</a>
        </div>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=1400&q=80')">
      <div class="hero-content">
        <h5>24/7 Emergency Services</h5>
        <h1>We're Here When<br>You Need Us Most</h1>
        <p>Round-the-clock emergency care, ambulance services, and dedicated ICU units for critical care.</p>
        <div class="hero-btns">
          <a href="tel:+919830000000" class="btn btn-primary">📞 Emergency Line</a>
          <a href="patient.php" class="btn btn-outline-white">Schedule Visit</a>
        </div>
      </div>
    </div>

  </div><!-- .hero-slides -->

  <!-- Controls -->
  <div class="slider-controls">
    <button class="slider-btn" id="heroPrev">&#8592;</button>
    <div class="slider-dots" id="heroDots">
      <button class="slider-dot active"></button>
      <button class="slider-dot"></button>
      <button class="slider-dot"></button>
    </div>
    <button class="slider-btn" id="heroNext">&#8594;</button>
  </div>
</div><!-- .hero-slider -->

<!-- ══ STATS BAR ════════════════════════════════════════════════ -->
<div class="container" style="margin-top:-24px;position:relative;z-index:10;">
  <div class="stats-row" style="box-shadow:0 8px 40px rgba(53,79,142,.15);">
    <div class="stat-item">
      <div class="stat-num">50+</div>
      <div class="stat-label">Specialist Doctors</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">20K+</div>
      <div class="stat-label">Patients Served</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">24/7</div>
      <div class="stat-label">Emergency Care</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">23</div>
      <div class="stat-label">Years of Service</div>
    </div>
  </div>
</div>

<!-- ══ ABOUT ═══════════════════════════════════════════════════ -->
<section class="section" id="about">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">
      <div style="position:relative;min-height:460px;border-radius:var(--radius);overflow:hidden;">
        <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=800&q=80"
          alt="About" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius);">
        <div style="position:absolute;bottom:24px;right:24px;background:var(--primary);color:white;padding:20px 28px;border-radius:var(--radius);font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;line-height:1.3;">
          Est. 2001<br><small style="font-family:'Nunito Sans',sans-serif;font-size:12px;font-weight:400;opacity:.85;">Kolkata, West Bengal</small>
        </div>
      </div>
      <div>
        <span class="section-label">About Us</span>
        <h2 class="section-title">Best Medical Care For You &amp; Your Family</h2>
        <p style="color:var(--muted);line-height:1.8;margin-bottom:24px;">
          Chandra &amp; Sons Hospital has been the cornerstone of healthcare in Kolkata for over two decades. 
          We combine cutting-edge medical technology with deeply compassionate patient care to ensure 
          the best outcomes for every individual who walks through our doors.
        </p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:28px;">
          <div style="background:var(--light);padding:20px;border-radius:var(--radius);text-align:center;">
            <div style="font-size:2rem;margin-bottom:8px;">👨‍⚕️</div>
            <h6 style="color:var(--dark);margin-bottom:4px;">Qualified Doctors</h6>
            <small style="color:var(--primary);">Board Certified</small>
          </div>
          <div style="background:var(--light);padding:20px;border-radius:var(--radius);text-align:center;">
            <div style="font-size:2rem;margin-bottom:8px;">🔬</div>
            <h6 style="color:var(--dark);margin-bottom:4px;">Accurate Testing</h6>
            <small style="color:var(--primary);">ISO Certified Labs</small>
          </div>
          <div style="background:var(--light);padding:20px;border-radius:var(--radius);text-align:center;">
            <div style="font-size:2rem;margin-bottom:8px;">🚑</div>
            <h6 style="color:var(--dark);margin-bottom:4px;">Free Ambulance</h6>
            <small style="color:var(--primary);">24/7 Available</small>
          </div>
          <div style="background:var(--light);padding:20px;border-radius:var(--radius);text-align:center;">
            <div style="font-size:2rem;margin-bottom:8px;">🏥</div>
            <h6 style="color:var(--dark);margin-bottom:4px;">Modern ICU</h6>
            <small style="color:var(--primary);">Critical Care Unit</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ SERVICES ════════════════════════════════════════════════ -->
<section class="section section-alt" id="services">
  <div class="container">
    <div class="section-head">
      <span class="section-label">Our Services</span>
      <h2 class="section-title">Excellent Medical Services</h2>
      <p style="color:var(--muted);font-size:14px;line-height:1.7;">From routine check-ups to complex surgeries, we provide comprehensive healthcare services across all specialties.</p>
    </div>
    <div class="services-grid">
      <?php
      $services = [
        ['🚑','Emergency Care','Round-the-clock emergency services with rapid response teams and fully equipped trauma bays.','services/emergency.php'],
        ['💊','Pharmacy &amp; Medicine','In-house pharmacy with comprehensive drug inventory and expert pharmacist consultation.','services/pharmacy.php'],
        ['🩸','Blood Testing','ISO-certified diagnostic labs delivering accurate results with rapid turnaround times.','services/blood-testing.php'],
        ['❤️','Cardiology','Advanced cardiac care including ECG, echocardiography, angiography and bypass surgery.','services/cardiology.php'],
        ['🧠','Neurology','Expert neurological diagnosis and treatment for brain, spine and nervous system disorders.','services/neurology.php'],
        ['🦴','Orthopaedics','Complete bone and joint care from fracture management to joint replacement surgery.','services/orthopaedics.php'],
        ['📄','Prescription','View and manage your medical prescriptions uploaded by doctors.','services/prescription.php'],
      ];
      foreach ($services as $s): ?>
      <a href="<?= $s[3] ?>" class="service-card">
        <div class="service-icon-wrap"><?= $s[0] ?></div>
        <h4><?= $s[1] ?></h4>
        <p><?= $s[2] ?></p>
        <div class="card-arrow">&#8594;</div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ TEAM CAROUSEL ════════════════════════════════════════════ -->
<section class="section" id="team">
  <div class="container">
    <div class="section-head">
      <span class="section-label">Our Doctors</span>
      <h2 class="section-title">Qualified Healthcare Professionals</h2>
    </div>

    <div class="team-carousel-wrap" id="teamCarousel">
      <div class="team-track" id="teamTrack">

        <?php
        // Use real DB doctors if available, supplement with demo
        $teamDoctors = !empty($docs) ? $docs : [];
        $demoPhotos = [
          'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=400&q=80',
          'https://images.unsplash.com/photo-1594824476967-48c8b964273f?w=400&q=80',
          'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=400&q=80',
          'https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=400&q=80',
        ];
        $demoDoctors = [
          ['name'=>'Dr. Arindam Ghosh','dept'=>'Cardiology'],
          ['name'=>'Dr. Priya Sharma','dept'=>'Neurology'],
          ['name'=>'Dr. Ravi Mehta','dept'=>'Orthopaedics'],
          ['name'=>'Dr. Sunita Roy','dept'=>'Gynaecology'],
        ];
        // Merge real + demo up to 6
        $allDocs = array_merge($teamDoctors, $demoDoctors);
        $allDocs = array_slice($allDocs, 0, 6);
        // Chunk into pairs for 2-per-slide layout
        $pairs = array_chunk($allDocs, 2);
        foreach ($pairs as $pi => $pair):
        ?>
        <div class="team-slide">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <?php foreach ($pair as $di => $doc):
            $photoIdx = ($pi * 2 + $di) % count($demoPhotos);
          ?>
            <div class="team-card">
              <div class="team-card-img">
                <img src="<?= $demoPhotos[$photoIdx] ?>" alt="<?= htmlspecialchars($doc['name']) ?>">
              </div>
              <div class="team-card-body">
                <div>
                  <h3><?= htmlspecialchars($doc['name']) ?></h3>
                  <div class="spec"><?= htmlspecialchars($doc['dept']) ?> Specialist</div>
                  <p>Dedicated professional with years of experience providing expert patient care.</p>
                </div>
                <div class="team-socials">
                  <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                  <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                  <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>

      </div><!-- .team-track -->
    </div><!-- .team-carousel-wrap -->

    <div class="team-nav">
      <div class="text-muted" id="teamCounter">1 / <?= count($pairs) ?></div>
      <div class="team-arrows">
        <button class="btn-icon" id="teamPrev">&#8592;</button>
        <button class="btn-icon" id="teamNext">&#8594;</button>
      </div>
    </div>
  </div>
</section>

<!-- ══ APPOINTMENT BANNER ══════════════════════════════════════ -->
<section class="section section-alt">
  <div class="container">
    <div class="appt-banner">
      <div>
        <h2>Ready to Book an Appointment?</h2>
        <p>Login to our patient portal and book with your preferred doctor in minutes.</p>
      </div>
      <a href="patient.php" class="btn btn-outline-white" style="flex-shrink:0;">Book Appointment →</a>
    </div>
  </div>
</section>

<!-- ══ TESTIMONIALS ════════════════════════════════════════════ -->
<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="section-label">Testimonials</span>
      <h2 class="section-title">What Our Patients Say</h2>
    </div>
    <div style="position:relative;overflow:hidden;max-width:700px;margin:auto;" id="testCarousel">
      <div class="testimonial-track" id="testTrack">
        <?php
        $testimonials = [
          ['https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80','Rajeev Kumar','Patient','The doctors here are incredibly skilled and the staff is warm and caring. I recovered quickly thanks to their expert treatment.'],
          ['https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80','Priya Das','Patient','Booking my appointment online was so easy. The facilities are modern and the entire team made me feel comfortable throughout.'],
          ['https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80','Suresh Patel','Patient','Had an emergency situation and was impressed by how quickly the team responded. Truly a world-class hospital in Kolkata.'],
        ];
        foreach ($testimonials as $t): ?>
        <div class="testimonial-slide">
          <div class="testimonial-card">
            <img class="test-avatar" src="<?= $t[0] ?>" alt="<?= $t[1] ?>">
            <p class="test-quote">"<?= $t[2] ?>"</p>
            <div class="test-name"><?= $t[1] ?></div>
            <div class="test-role"><?= $t[1] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="test-dots" id="testDots">
        <div class="test-dot active"></div>
        <div class="test-dot"></div>
        <div class="test-dot"></div>
      </div>
    </div>
  </div>
</section>

<!-- ══ PORTAL CARDS ════════════════════════════════════════════ -->
<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <span class="section-label">Portals</span>
      <h2 class="section-title">Access Your Portal</h2>
    </div>
    <div class="portal-grid">
      <a href="patient.php" class="portal-card">
        <div class="p-icon">👤</div>
        <h4>Patient Portal</h4>
        <p>Register, login and book appointments with your preferred doctor.</p>
        <div class="p-arrow">→</div>
      </a>
      <a href="doctor.php" class="portal-card">
        <div class="p-icon">👨‍⚕️</div>
        <h4>Doctor Dashboard</h4>
        <p>View and manage your appointment schedule and patient list.</p>
        <div class="p-arrow">→</div>
      </a>
      <a href="staff.php" class="portal-card">
        <div class="p-icon">👨‍💼</div>
        <h4>Staff Portal</h4>
        <p>Manage all appointments, edit and update patient records.</p>
        <div class="p-arrow">→</div>
      </a>
      <a href="admin.php" class="portal-card">
        <div class="p-icon">🔐</div>
        <h4>Admin Panel</h4>
        <p>Full system control — user management, doctor registration &amp; settings.</p>
        <div class="p-arrow">→</div>
      </a>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>
