<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$conn = new mysqli("localhost", "root", "Amrit@2020", "doctor_booking");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$msg = ""; $msg_type = "success";

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
    unset($_SESSION['admin'], $_SESSION['email_step'], $_SESSION['temp_otp']);
    header("Location: admin.php"); exit();
}
// Send OTP
if (isset($_POST['send_otp'])) {
    $email_input = trim($_POST['email']);
    if ($email_input === 'amritanshuchandra@gmail.com') {
        $otp = rand(100000, 999999);
        $_SESSION['temp_otp'] = $otp; $_SESSION['email_step'] = true;
        $log_time = date("d-m-Y H:i:s");
        $result = sendMail($email_input, "Admin OTP — Chandra & Sons",
            "<div style='font-family:sans-serif;max-width:400px;'>
            <h2 style='color:#354F8E;'>Admin Verification</h2>
            <p>Your OTP code is:</p>
            <h1 style='color:#13C5DD;font-size:3rem;letter-spacing:.2em;'>$otp</h1>
            <p style='color:#888;font-size:12px;'>Request time: $log_time</p></div>");
        $_SESSION['msg'] = ($result === true) ? ["OTP sent to your email.", "success"] : ["Mail Error: $result", "danger"];
    } else {
        $_SESSION['msg'] = ["Unauthorized email address.", "danger"];
    }
    header("Location: admin.php"); exit();
}
// Verify OTP
if (isset($_POST['verify_otp'])) {
    if (isset($_SESSION['temp_otp']) && $_POST['otp'] == $_SESSION['temp_otp']) {
        $_SESSION['admin'] = true;
        unset($_SESSION['temp_otp']);
        header("Location: admin.php"); exit();
    } else {
        $_SESSION['msg'] = ["Invalid OTP. Please try again.", "danger"];
        header("Location: admin.php"); exit();
    }
}
// Add user
if (isset($_POST['add_user']) && isset($_SESSION['admin'])) {
    $stmt = $conn->prepare("INSERT INTO users (name, user_id, password, role, dept, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $_POST['name'], $_POST['uid'], $_POST['pass'], $_POST['role'], $_POST['dept'], $_POST['manual_time']);
    $stmt->execute();
    header("Location: admin.php?added=1"); exit();
}
// Delete user
if (isset($_GET['delete']) && isset($_SESSION['admin'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: admin.php?deleted=1"); exit();
}

if (isset($_SESSION['msg'])) { [$msg, $msg_type] = $_SESSION['msg']; unset($_SESSION['msg']); }
if (isset($_GET['added']))  { $msg = "Staff/Doctor registered successfully!"; $msg_type = "success"; }
if (isset($_GET['deleted'])) { $msg = "Record deleted."; $msg_type = "success"; }

// Data
$admin_doctors = $admin_staff = null;
$doc_count = $staff_count = $patient_count = 0;
if (isset($_SESSION['admin'])) {
    $admin_doctors = $conn->query("SELECT * FROM users WHERE role='doctor' ORDER BY id DESC");
    $admin_staff   = $conn->query("SELECT * FROM users WHERE role='staff'  ORDER BY id DESC");
    $dc = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='doctor'");
    if ($dc) $doc_count = $dc->fetch_assoc()['c'];
    $sc = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='staff'");
    if ($sc) $staff_count = $sc->fetch_assoc()['c'];
    $pc = $conn->query("SELECT COUNT(*) as c FROM patients");
    if ($pc) $patient_count = $pc->fetch_assoc()['c'];
    $ac = $conn->query("SELECT COUNT(*) as c FROM appointments");
    if ($ac) $appt_count = $ac->fetch_assoc()['c'];
}

$active_module = 'admin';
require 'header.php';
?>

<!-- Page Banner -->
<div style="background:linear-gradient(135deg,#1a1a2e,var(--dark));padding:48px 0 36px;">
  <div class="container">
    <span class="section-label" style="color:var(--primary);border-color:var(--primary);">🔐 Admin Panel</span>
    <h1 style="color:white;font-size:2.2rem;margin-top:8px;">Administration</h1>
    <p style="color:rgba(255,255,255,.7);margin-top:6px;">Chandra &amp; Sons Hospital — Secure Management Console</p>
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

<?php if (!isset($_SESSION['admin'])): ?>
<!-- ── LOGIN ─────────────────────────────────────────────────── -->
<div style="max-width:440px;margin:auto;">
  <div class="form-card" style="border-top:4px solid var(--primary);">
    <div style="text-align:center;margin-bottom:28px;">
      <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:grid;place-items:center;font-size:2rem;margin:0 auto 16px;">🔐</div>
      <h3>Secure Admin Access</h3>
      <p class="text-muted">OTP authentication required</p>
    </div>
    <form method="POST">
      <?php if (!isset($_SESSION['email_step'])): ?>
      <div class="form-group">
        <label class="form-label">Administrator Email</label>
        <input type="email" name="email" class="form-control" placeholder="Authorized admin email" required>
      </div>
      <button class="btn btn-primary btn-full" name="send_otp">
        <i class="fa fa-paper-plane"></i> Send OTP
      </button>
      <?php else: ?>
      <div style="text-align:center;padding:16px;background:rgba(19,197,221,.08);border-radius:8px;margin-bottom:20px;">
        <div style="font-size:2rem;margin-bottom:8px;">📧</div>
        <p style="font-size:13px;color:var(--muted);">OTP sent to admin email.</p>
      </div>
      <div class="form-group">
        <label class="form-label">Security OTP</label>
        <input type="text" name="otp" class="form-control" placeholder="6-digit code" required autofocus style="text-align:center;font-size:1.4rem;letter-spacing:.3em;">
      </div>
      <button class="btn btn-primary btn-full" name="verify_otp">
        <i class="fa fa-shield-alt"></i> Verify &amp; Enter
      </button>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ── ADMIN DASHBOARD ────────────────────────────────────────── -->

<!-- Stats row -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;">
  <?php
  $stats = [
    ['👨‍⚕️','Doctors',$doc_count,'var(--primary)'],
    ['👨‍💼','Staff',$staff_count,'var(--secondary)'],
    ['👥','Patients',$patient_count,'#27c98f'],
    ['📅','Appointments',$appt_count ?? 0,'#f5c842'],
  ];
  foreach ($stats as $s): ?>
  <div class="dash-card" style="text-align:center;border-top:3px solid <?= $s[3] ?>;">
    <div style="font-size:1.8rem;margin-bottom:8px;"><?= $s[0] ?></div>
    <div style="font-size:2rem;font-family:'Playfair Display',serif;color:<?= $s[3] ?>;font-weight:900;"><?= $s[2] ?></div>
    <div style="font-size:11px;color:var(--muted);font-weight:700;margin-top:4px;text-transform:uppercase;"><?= $s[1] ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Top actions bar -->
<div style="display:flex;gap:12px;margin-bottom:28px;flex-wrap:wrap;">
  <a href="patients_db.php" class="btn btn-success">
    <i class="fa fa-users"></i> Patient Database
  </a>
  <a href="admin.php?logout=1" class="btn btn-danger-soft">
    <i class="fa fa-sign-out-alt"></i> Logout
  </a>
</div>

<!-- Register new staff/doctor -->
<div class="dash-card mb-24">
  <h3 style="margin-bottom:20px;color:var(--dark);">
    <i class="fa fa-user-plus" style="color:var(--primary);margin-right:8px;"></i>
    Register New Doctor / Staff
  </h3>
  <form method="POST">
    <div class="grid-2">
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input name="name" class="form-control" placeholder="Full name" required>
      </div>
      <div class="form-group">
        <label class="form-label">Employee ID</label>
        <input name="uid" class="form-control" placeholder="Unique employee ID" required>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input name="pass" type="password" class="form-control" placeholder="Set login password" required>
      </div>
      <div class="form-group">
        <label class="form-label">Role</label>
        <select name="role" id="roleSelect" class="form-control" onchange="updateRoleUI()">
          <option value="doctor">Doctor</option>
          <option value="staff">Staff</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Specialization / Department</label>
        <input name="dept" id="deptInput" class="form-control" placeholder="e.g. Cardiology">
      </div>
      <div class="form-group">
        <label class="form-label">Time Slot</label>
        <input name="manual_time" class="form-control" placeholder="e.g. 10:00 AM – 1:00 PM" required>
      </div>
    </div>
    <button class="btn btn-primary" name="add_user">
      <i class="fa fa-plus"></i> Register Entry
    </button>
  </form>
</div>

<!-- Doctors & Staff tables -->
<div class="grid-2" style="gap:24px;">
  <!-- Doctors -->
  <div class="dash-card">
    <h3 style="margin-bottom:18px;color:var(--dark);">
      <span style="color:var(--primary);">👨‍⚕️</span> Doctors
      <span class="badge badge-primary" style="font-size:11px;margin-left:8px;"><?= $doc_count ?></span>
    </h3>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Name</th><th>Dept</th><th>Slot</th><th></th></tr></thead>
        <tbody>
          <?php if ($admin_doctors): while ($d = $admin_doctors->fetch_assoc()): ?>
          <tr>
            <td><strong style="color:var(--dark);"><?= htmlspecialchars($d['name']) ?></strong></td>
            <td><span class="badge badge-primary"><?= htmlspecialchars($d['dept']) ?></span></td>
            <td style="font-size:12px;color:var(--muted);"><?= htmlspecialchars($d['created_at']) ?></td>
            <td>
              <a href="admin.php?delete=<?= $d['id'] ?>" class="btn btn-danger-soft btn-sm"
                 onclick="return confirm('Delete Dr. <?= addslashes($d['name']) ?>?')">×</a>
            </td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Staff -->
  <div class="dash-card">
    <h3 style="margin-bottom:18px;color:var(--dark);">
      <span style="color:var(--secondary);">👨‍💼</span> Staff
      <span class="badge badge-secondary" style="font-size:11px;margin-left:8px;"><?= $staff_count ?></span>
    </h3>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Name</th><th>Emp ID</th><th>Shift</th><th></th></tr></thead>
        <tbody>
          <?php if ($admin_staff): while ($s = $admin_staff->fetch_assoc()): ?>
          <tr>
            <td><strong style="color:var(--dark);"><?= htmlspecialchars($s['name']) ?></strong></td>
            <td style="color:var(--muted);font-size:13px;"><?= htmlspecialchars($s['user_id']) ?></td>
            <td style="font-size:12px;color:var(--muted);"><?= htmlspecialchars($s['created_at']) ?></td>
            <td>
              <a href="admin.php?delete=<?= $s['id'] ?>" class="btn btn-danger-soft btn-sm"
                 onclick="return confirm('Delete <?= addslashes($s['name']) ?>?')">×</a>
            </td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div><!-- .grid-2 -->

<?php endif; ?>

</div><!-- .container -->
</div><!-- .page-wrap -->

<script>
function updateRoleUI() {
    var role = document.getElementById('roleSelect').value;
    var dept = document.getElementById('deptInput');
    if (!dept) return;
    if (role === 'staff') {
        dept.placeholder = 'N/A — not required for staff';
        dept.disabled = true; dept.style.opacity = '.4';
    } else {
        dept.placeholder = 'e.g. Cardiology'; dept.disabled = false; dept.style.opacity = '1';
    }
}
</script>

<?php require 'footer.php'; ?>
