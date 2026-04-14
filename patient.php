<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$conn = new mysqli("localhost", "root", "Amrit@2020", "doctor_booking");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

$msg = ""; $msg_type = "danger";

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP(); $mail->Host = 'smtp.gmail.com'; $mail->SMTPAuth = true;
        $mail->Username = 'amritanshuchandra@gmail.com'; $mail->Password = 'sahgdcuwonnmuabg';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; $mail->Port = 587;
        $mail->setFrom('amritanshuchandra@gmail.com', 'Chandra & Sons Hospital');
        $mail->addAddress($to); $mail->isHTML(true); $mail->Subject = $subject; $mail->Body = $body;
        $mail->send(); return true;
    } catch (Exception $e) { return $mail->ErrorInfo; }
}

// Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['user'], $_SESSION['login_step'], $_SESSION['signup_step'], $_SESSION['temp_otp'], $_SESSION['temp_email'], $_SESSION['signup_data']);
    header("Location: patient.php"); exit();
}
// Login: Send OTP
if (isset($_POST['login_send_otp'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $check = $conn->query("SELECT * FROM patients WHERE email='$email'");
    if ($check->num_rows == 0) { $msg = "Email not found. Please register first!"; }
    else {
        $otp = rand(100000, 999999);
        $_SESSION['temp_otp'] = $otp; $_SESSION['temp_email'] = $email;
        $result = sendMail($email, "Login OTP — Chandra & Sons", "<h2>Your Login OTP</h2><p style='font-size:28px;font-weight:bold;color:#13C5DD;'>$otp</p><p>Valid for 10 minutes.</p>");
        if ($result === true) { $_SESSION['login_step'] = true; $msg = "OTP sent to your email!"; $msg_type = "success"; }
        else { $msg = "Mail Error: $result"; }
    }
}
// Login: Verify OTP
if (isset($_POST['login_verify'])) {
    if (isset($_SESSION['temp_otp']) && $_POST['otp'] == $_SESSION['temp_otp']) {
        $_SESSION['user'] = $_SESSION['temp_email'];
        unset($_SESSION['temp_otp'], $_SESSION['login_step'], $_SESSION['temp_email']);
    } else { $msg = "Invalid OTP. Please try again."; }
}
// Signup: Send OTP
if (isset($_POST['signup_send_otp'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $check = $conn->query("SELECT * FROM patients WHERE email='$email'");
    if ($check->num_rows > 0) { $msg = "Email already registered! Please login."; }
    else {
        if (!is_dir("uploads")) { mkdir("uploads", 0777, true); }
        $target = "";
        if (!empty($_FILES['photo']['name'])) {
            $target = "uploads/" . time() . "_" . basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        }
        $otp = rand(100000, 999999);
        $_SESSION['temp_otp'] = $otp;
        $_SESSION['signup_data'] = ['name' => $conn->real_escape_string($_POST['name']), 'phone' => $conn->real_escape_string($_POST['phone']), 'email' => $email, 'photo' => $target];
        $result = sendMail($email, "Signup OTP — Chandra & Sons", "<h2>Complete Your Registration</h2><p style='font-size:28px;font-weight:bold;color:#13C5DD;'>$otp</p>");
        if ($result === true) { $_SESSION['signup_step'] = true; $msg = "OTP sent to $email"; $msg_type = "success"; }
        else { $msg = "Mail Error: $result"; }
    }
}
// Signup: Verify OTP
if (isset($_POST['signup_verify'])) {
    if (isset($_SESSION['temp_otp']) && isset($_SESSION['signup_data']) && $_POST['otp'] == $_SESSION['temp_otp']) {
        $d = $_SESSION['signup_data'];
        $sql = "INSERT INTO patients (name, phone, email, photo) VALUES ('{$d['name']}','{$d['phone']}','{$d['email']}','{$d['photo']}')";
        if ($conn->query($sql)) {
            $_SESSION['user'] = $d['email'];
            unset($_SESSION['signup_data'], $_SESSION['temp_otp'], $_SESSION['signup_step']);
            $msg = "Welcome! Your account has been created."; $msg_type = "success";
        } else { $msg = "DB Error: " . $conn->error; }
    } else { $msg = "Invalid OTP or session expired."; }
}
// Booking
if (isset($_POST['book']) && isset($_SESSION['user'])) {
    $p_name  = $conn->real_escape_string($_POST['name']);
    $p_email = $conn->real_escape_string($_SESSION['user']);
    $p_phone = $conn->real_escape_string($_POST['phone']);
    $d_name  = $conn->real_escape_string($_POST['doctor']);
    $a_date  = $conn->real_escape_string($_POST['date']);
    $a_time  = $conn->real_escape_string($_POST['time']);
    $sql = "INSERT INTO appointments (patient_name, email, phone, doctor_name, appointment_date, appointment_time) VALUES ('$p_name','$p_email','$p_phone','$d_name','$a_date','$a_time')";
    if ($conn->query($sql)) {
        $msg = "Appointment booked successfully! You'll receive a confirmation email."; $msg_type = "success";
        sendMail($p_email, "Appointment Confirmed — Chandra & Sons", "<h2>Appointment Confirmed!</h2><p>Doctor: <b>$d_name</b></p><p>Date: <b>$a_date</b></p><p>Time: <b>$a_time</b></p>");
    } else { $msg = "Error: " . $conn->error; }
}

// Fetch data
$docs = [];
$res = $conn->query("SELECT name, dept, created_at FROM users WHERE role='doctor'");
if ($res) { while ($row = $res->fetch_assoc()) { $docs[] = $row; } }

$user_data = null;
if (isset($_SESSION['user'])) {
    $u_email = $_SESSION['user'];
    $u_res = $conn->query("SELECT * FROM patients WHERE email='$u_email'");
    $user_data = $u_res ? $u_res->fetch_assoc() : null;
}

// Past appointments
$past_appts = [];
if (isset($_SESSION['user'])) {
    $ue = $conn->real_escape_string($_SESSION['user']);
    $pa = $conn->query("SELECT * FROM appointments WHERE email='$ue' ORDER BY appointment_date DESC LIMIT 5");
    if ($pa) { while ($r = $pa->fetch_assoc()) $past_appts[] = $r; }
}

$active_module = 'patient';
require 'header.php';
?>

<!-- Page hero banner -->
<div style="background:linear-gradient(135deg,var(--secondary),var(--dark));padding:48px 0 36px;">
  <div class="container">
    <span class="section-label" style="border-color:var(--primary);color:var(--primary);">Patient Portal</span>
    <h1 style="color:white;font-size:2.2rem;margin-top:8px;">
      <?= isset($_SESSION['user']) ? 'Your Health Dashboard' : 'Welcome, Patient' ?>
    </h1>
    <p style="color:rgba(255,255,255,.7);margin-top:6px;">Chandra &amp; Sons Hospital · Kolkata</p>
  </div>
</div>

<div class="page-wrap page-wrap-alt">
<div class="container" style="padding-top:40px;">

<?php if ($msg): ?>
<div class="alert alert-<?= $msg_type ?>">
  <i class="fa fa-<?= $msg_type==='success'?'check-circle':'exclamation-circle' ?>"></i>
  <?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<!-- ── NOT LOGGED IN ──────────────────────────────────────────── -->
<?php if (!isset($_SESSION['user'])): ?>
<div style="max-width:480px;margin:auto;">
  <?php $view = $_GET['view'] ?? 'login'; ?>

  <div class="tab-switch">
    <button class="tab-btn <?= $view==='login'?'active':'' ?>" onclick="location.href='patient.php?view=login'">
      <i class="fa fa-sign-in-alt"></i> Login
    </button>
    <button class="tab-btn <?= $view==='signup'?'active':'' ?>" onclick="location.href='patient.php?view=signup'">
      <i class="fa fa-user-plus"></i> Register
    </button>
  </div>

  <?php if ($view === 'login'): ?>
  <!-- LOGIN FORM -->
  <div class="form-card">
    <h3 style="margin-bottom:6px;color:var(--dark);">Sign In</h3>
    <p class="text-muted mb-24">Enter your registered email to receive an OTP.</p>
    <form method="POST">
      <?php if (!isset($_SESSION['login_step'])): ?>
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@gmail.com" required>
      </div>
      <button class="btn btn-primary btn-full" name="login_send_otp">
        <i class="fa fa-paper-plane"></i> Send OTP
      </button>
      <?php else: ?>
      <div style="text-align:center;padding:16px;background:rgba(19,197,221,.08);border-radius:8px;margin-bottom:20px;">
        <div style="font-size:2rem;margin-bottom:8px;">📧</div>
        <p style="font-size:13px;color:var(--muted);">OTP sent to your email. Check your inbox.</p>
      </div>
      <div class="form-group">
        <label class="form-label">6-Digit OTP</label>
        <input name="otp" class="form-control" placeholder="Enter OTP code" required autofocus style="text-align:center;font-size:1.4rem;letter-spacing:.3em;">
      </div>
      <button class="btn btn-primary btn-full" name="login_verify">
        <i class="fa fa-check"></i> Verify &amp; Login
      </button>
      <?php endif; ?>
    </form>
    <p class="text-muted text-center mt-16">Don't have an account? <a href="patient.php?view=signup" style="color:var(--primary);font-weight:700;">Register here</a></p>
  </div>

  <?php else: ?>
  <!-- SIGNUP FORM -->
  <div class="form-card">
    <h3 style="margin-bottom:6px;color:var(--dark);">Create Account</h3>
    <p class="text-muted mb-24">Register to book appointments and manage your health.</p>
    <form method="POST" enctype="multipart/form-data">
      <?php if (!isset($_SESSION['signup_step'])): ?>
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input name="name" class="form-control" placeholder="Your full name" required>
      </div>
      <div class="form-group">
        <label class="form-label">Phone Number</label>
        <input name="phone" class="form-control" placeholder="+91 XXXXX XXXXX" required>
      </div>
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@gmail.com" required>
      </div>
      <div class="form-group">
        <label class="form-label">Profile Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*">
      </div>
      <button class="btn btn-primary btn-full" name="signup_send_otp">
        <i class="fa fa-paper-plane"></i> Send Verification OTP
      </button>
      <?php else: ?>
      <div style="text-align:center;padding:16px;background:rgba(19,197,221,.08);border-radius:8px;margin-bottom:20px;">
        <div style="font-size:2rem;margin-bottom:8px;">✅</div>
        <p style="font-size:13px;color:var(--muted);">Verifying: <strong><?= htmlspecialchars($_SESSION['signup_data']['email']) ?></strong></p>
      </div>
      <div class="form-group">
        <label class="form-label">OTP Code</label>
        <input name="otp" class="form-control" placeholder="Enter OTP" required autofocus style="text-align:center;font-size:1.4rem;letter-spacing:.3em;">
      </div>
      <button class="btn btn-success btn-full" name="signup_verify">
        <i class="fa fa-user-check"></i> Complete Registration
      </button>
      <?php endif; ?>
    </form>
    <p class="text-muted text-center mt-16">Already have an account? <a href="patient.php?view=login" style="color:var(--primary);font-weight:700;">Login here</a></p>
  </div>
  <?php endif; ?>
</div>

<!-- ── LOGGED IN ───────────────────────────────────────────────── -->
<?php else: ?>
<div class="grid-2" style="align-items:start;gap:32px;">

  <!-- LEFT: Booking Form -->
  <div>
    <div class="dash-card">
      <!-- User info strip -->
      <div style="display:flex;align-items:center;gap:14px;padding:16px;background:var(--light);border-radius:8px;margin-bottom:28px;">
        <?php if (!empty($user_data['photo'])): ?>
        <img src="<?= htmlspecialchars($user_data['photo']) ?>" class="avatar" style="width:52px;height:52px;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user_data['name']??'P') ?>&background=13C5DD&color=fff'">
        <?php else: ?>
        <div style="width:52px;height:52px;border-radius:50%;background:var(--primary);display:grid;place-items:center;color:white;font-size:1.4rem;flex-shrink:0;">👤</div>
        <?php endif; ?>
        <div>
          <div style="font-weight:700;color:var(--dark);"><?= htmlspecialchars($user_data['name'] ?? 'Patient') ?></div>
          <div style="font-size:12px;color:var(--muted);"><?= htmlspecialchars($_SESSION['user']) ?></div>
        </div>
        <a href="patient.php?logout=1" class="btn btn-danger-soft btn-sm" style="margin-left:auto;">Logout</a>
      </div>

      <h3 style="margin-bottom:6px;color:var(--dark);">Book New Appointment</h3>
      <p class="text-muted mb-24">Choose your doctor and preferred time slot.</p>

      <form method="POST">
        <div class="form-group">
          <label class="form-label">Patient Name</label>
          <input name="name" class="form-control" value="<?= htmlspecialchars($user_data['name']??'') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input class="form-control" value="<?= htmlspecialchars($_SESSION['user']) ?>" readonly>
        </div>
        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input name="phone" class="form-control" value="<?= htmlspecialchars($user_data['phone']??'') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Select Doctor</label>
          <select name="doctor" class="form-control" onchange="setTimeSlot(this)" required>
            <option value="">— Choose a Doctor &amp; Department —</option>
            <?php foreach ($docs as $d): ?>
            <option value="<?= htmlspecialchars($d['name']) ?>" data-time="<?= htmlspecialchars($d['created_at']) ?>">
              Dr. <?= htmlspecialchars($d['name']) ?> — <?= htmlspecialchars($d['dept']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Appointment Date</label>
            <input type="date" name="date" class="form-control" min="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Time Slot</label>
            <input name="time" id="timeSlot" class="form-control" readonly placeholder="Select doctor first">
          </div>
        </div>
        <button class="btn btn-primary btn-full" name="book" style="margin-top:8px;">
          <i class="fa fa-calendar-check"></i> Confirm Appointment
        </button>
      </form>
    </div>
  </div>

  <!-- RIGHT: Past Appointments + Prescriptions -->
  <div style="display:flex;flex-direction:column;gap:24px;">

    <!-- Recent Appointments -->
    <div class="dash-card">
      <h3 style="margin-bottom:20px;color:var(--dark);">Recent Appointments</h3>
      <?php if (!empty($past_appts)): ?>
      <?php foreach ($past_appts as $appt): ?>
      <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:16px;border-radius:8px;background:var(--light);margin-bottom:12px;">
        <div>
          <div style="font-weight:700;color:var(--dark);font-size:14px;">Dr. <?= htmlspecialchars($appt['doctor_name']) ?></div>
          <div style="font-size:12px;color:var(--muted);margin-top:4px;"><?= date('d M Y', strtotime($appt['appointment_date'])) ?></div>
        </div>
        <span class="badge badge-primary"><?= htmlspecialchars($appt['appointment_time']) ?></span>
      </div>
      <?php endforeach; ?>
      <?php else: ?>
      <div class="empty-state" style="padding:40px 20px;">
        <div class="icon">📅</div>
        <h4>No appointments yet</h4>
        <p>Your booking history will appear here.</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- Prescriptions Card -->
    <div class="dash-card" style="text-align:center;padding:32px 24px;">
      <div style="font-size:3rem;margin-bottom:12px;">💊</div>
      <h3 style="color:var(--dark);margin-bottom:8px;">My Prescriptions</h3>
      <p style="color:var(--muted);font-size:14px;margin-bottom:20px;">
        View all prescriptions issued by your doctors, including medicines, notes, and uploaded files.
      </p>
      <a href="services/prescription.php"
         class="btn btn-primary"
         style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;text-decoration:none;">
        <i class="fa fa-file-medical"></i> View Prescriptions
      </a>
    </div>

  </div><!-- end right column -->

</div><!-- .grid-2 -->
<?php endif; ?>

</div><!-- .container -->
</div><!-- .page-wrap -->

<script>
function setTimeSlot(el) {
    var t = el.options[el.selectedIndex].getAttribute('data-time');
    var tf = document.getElementById('timeSlot');
    if (tf) tf.value = t || 'Not Set';
}
</script>

<?php require 'footer.php'; ?>