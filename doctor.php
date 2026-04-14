<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$conn = new mysqli("localhost", "root", "Amrit@2020", "doctor_booking");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$msg = "";

// Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['doctor_verified'], $_SESSION['doctor_name']);
    header("Location: doctor.php"); exit();
}
// Delete/Complete appointment
if (isset($_GET['delete_id']) && isset($_SESSION['doctor_verified'])) {
    $id = intval($_GET['delete_id']);
    $doc_name = $conn->real_escape_string($_SESSION['doctor_name']);
    $conn->query("DELETE FROM appointments WHERE id=$id AND doctor_name='$doc_name'");
    header("Location: doctor.php"); exit();
}
// Login
// ✅ ADD PRESCRIPTION
if (isset($_POST['add_prescription']) && isset($_SESSION['doctor_verified'])) {

    $email = $conn->real_escape_string($_POST['email']);
    $name = $conn->real_escape_string($_POST['name']);
    $age = !empty($_POST['age']) ? intval($_POST['age']) : NULL;
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $disease = $conn->real_escape_string($_POST['disease']);
    $medicines = $conn->real_escape_string($_POST['medicines']);
    $notes = $conn->real_escape_string($_POST['notes']);

    $file = "";
    if (!empty($_FILES['file']['name'])) {
        $file = time() . "_" . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $file);
    }

    $sql = "INSERT INTO prescriptions 
(email, patient_name, age, weight, height, disease, medicines, notes, file)
VALUES 
('$email','$name',".($age === NULL ? "NULL" : $age).",'$weight','$height','$disease','$medicines','$notes','$file')";
    if ($conn->query($sql)) {
        $msg = "✅ Prescription uploaded successfully!";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}
if (isset($_POST['doctor_login'])) {
    $uid  = $conn->real_escape_string($_POST['uid']);
    $pass = $_POST['pass'];
    $res2 = $conn->query("SELECT * FROM users WHERE user_id='$uid' AND password='$pass' AND role='doctor'");
    if ($res2 && $res2->num_rows > 0) {
        $d = $res2->fetch_assoc();
        $_SESSION['doctor_verified'] = true;
        $_SESSION['doctor_name'] = $d['name'];
        header("Location: doctor.php"); exit();
    } else { $msg = "Invalid credentials. Please check your Employee ID and password."; }
}

// Fetch doctor data & appointments
$doctor_data = null; $doctor_appointments = null;
$today_count = 0; $total_count = 0; $upcoming_count = 0;
if (isset($_SESSION['doctor_verified'])) {
    $dn = $conn->real_escape_string($_SESSION['doctor_name']);
    $d_res = $conn->query("SELECT * FROM users WHERE name='$dn' AND role='doctor'");
    $doctor_data = $d_res ? $d_res->fetch_assoc() : null;
    $doctor_appointments = $conn->query("SELECT * FROM appointments WHERE doctor_name='$dn' ORDER BY appointment_date ASC, appointment_time ASC");
    // Stats
    $today = date('Y-m-d');
    $tc = $conn->query("SELECT COUNT(*) as c FROM appointments WHERE doctor_name='$dn' AND appointment_date='$today'");
    if ($tc) { $today_count = $tc->fetch_assoc()['c']; }
    $tt = $conn->query("SELECT COUNT(*) as c FROM appointments WHERE doctor_name='$dn'");
    if ($tt) { $total_count = $tt->fetch_assoc()['c']; }
    $up = $conn->query("SELECT COUNT(*) as c FROM appointments WHERE doctor_name='$dn' AND appointment_date > '$today'");
    if ($up) { $upcoming_count = $up->fetch_assoc()['c']; }
}

$active_module = 'doctor';
require 'header.php';
?>

<!-- Page Banner -->
<div style="background:linear-gradient(135deg,var(--dark),var(--secondary));padding:48px 0 36px;">
  <div class="container">
    <span class="section-label" style="color:var(--primary);border-color:var(--primary);">Doctor Portal</span>
    <h1 style="color:white;font-size:2.2rem;margin-top:8px;">
      <?= isset($_SESSION['doctor_verified']) ? 'Welcome, Dr. '.htmlspecialchars($_SESSION['doctor_name']) : 'Doctor Login' ?>
    </h1>
    <p style="color:rgba(255,255,255,.7);margin-top:6px;">Appointment Management System</p>
  </div>
</div>

<div class="page-wrap page-wrap-alt">
<div class="container" style="padding-top:40px;">

<?php if (!isset($_SESSION['doctor_verified'])): ?>
<!-- ── LOGIN ─────────────────────────────────────────────────── -->
<div style="max-width:460px;margin:auto;">
  <?php if ($msg): ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
  <div class="form-card">
    <div style="text-align:center;margin-bottom:28px;">
      <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:grid;place-items:center;font-size:2rem;margin:0 auto 16px;">👨‍⚕️</div>
      <h3>Doctor Login</h3>
      <p class="text-muted">Access your appointment dashboard</p>
    </div>
    <form method="POST">
      <div class="form-group">
        <label class="form-label">Employee ID</label>
        <input name="uid" class="form-control" placeholder="Your doctor employee ID" required>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="pass" class="form-control" placeholder="Your password" required>
      </div>
      <button class="btn btn-primary btn-full" name="doctor_login">
        <i class="fa fa-sign-in-alt"></i> Login to Dashboard
      </button>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ── DASHBOARD ─────────────────────────────────────────────── -->

<!-- Header strip -->
<div class="dash-header">
  <div>
    <h2>Dr. <?= htmlspecialchars($_SESSION['doctor_name']) ?></h2>
    <p>
      <?php if ($doctor_data): ?>
      <span class="badge badge-primary" style="background:rgba(255,255,255,.15);color:white;">
        <?= htmlspecialchars($doctor_data['dept']) ?>
      </span>
      &nbsp; Employee ID: <?= htmlspecialchars($doctor_data['user_id'] ?? '—') ?>
      <?php endif; ?>
    </p>
  </div>
  <a href="doctor.php?logout=1" class="btn btn-outline-white btn-sm">
    <i class="fa fa-sign-out-alt"></i> Logout
  </a>
</div>

<!-- Stats cards -->
<div class="grid-3 mb-24" style="gap:16px;">
  <div class="dash-card" style="text-align:center;border-top:3px solid var(--primary);">
    <div style="font-size:2.4rem;font-family:'Playfair Display',serif;color:var(--primary);font-weight:900;"><?= $today_count ?></div>
    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-top:4px;">TODAY'S APPOINTMENTS</div>
  </div>
  <div class="dash-card" style="text-align:center;border-top:3px solid var(--secondary);">
    <div style="font-size:2.4rem;font-family:'Playfair Display',serif;color:var(--secondary);font-weight:900;"><?= $upcoming_count ?></div>
    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-top:4px;">UPCOMING</div>
  </div>
  <div class="dash-card" style="text-align:center;border-top:3px solid #27c98f;">
    <div style="font-size:2.4rem;font-family:'Playfair Display',serif;color:#27c98f;font-weight:900;"><?= $total_count ?></div>
    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-top:4px;">TOTAL PATIENTS</div>
  </div>
</div>

<!-- Appointments table -->
<div class="dash-card">
  <div class="flex-between mb-16">
    <h3>Upcoming Appointments</h3>
    <span class="badge badge-primary"><?= $total_count ?> total</span>
  </div>
  <?php if ($doctor_appointments && $doctor_appointments->num_rows > 0): ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Patient Name</th>
          <th>Contact</th>
          <th>Date</th>
          <th>Time Slot</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; while ($row = $doctor_appointments->fetch_assoc()): ?>
        <tr>
          <td class="text-muted"><?= $i++ ?></td>
          <td>
            <div style="font-weight:700;color:var(--dark);"><?= htmlspecialchars($row['patient_name']) ?></div>
          </td>
          <td>
            <div style="font-size:13px;"><?= htmlspecialchars($row['phone']) ?></div>
            <div style="font-size:12px;color:var(--muted);"><?= htmlspecialchars($row['email']) ?></div>
          </td>
          <td>
            <?php
            $d = strtotime($row['appointment_date']);
            $isToday = date('Y-m-d', $d) === date('Y-m-d');
            ?>
            <span class="badge <?= $isToday ? 'badge-success' : 'badge-secondary' ?>">
              <?= $isToday ? 'Today' : date('d M Y', $d) ?>
            </span>
          </td>
          <td><span class="badge badge-primary"><?= htmlspecialchars($row['appointment_time']) ?></span></td>
          <td>
            <a href="doctor.php?delete_id=<?= $row['id'] ?>"
             class="btn btn-success btn-sm"
             onclick="return confirm('Mark this appointment as completed?')">
              <i class="fa fa-check"></i> Complete
           </a>

  <!-- ✅ ADD THIS -->
  <button class="btn btn-primary btn-sm"
    onclick="openForm('<?= $row['patient_name'] ?>','<?= $row['email'] ?>')">
    📄 Prescription
  </button>
</td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
  <div class="empty-state">
    <div class="icon">📅</div>
    <h4>No Appointments</h4>
    <p>Your schedule is clear. New bookings will appear here.</p>
  </div>
  <?php endif; ?>
</div><!-- .dash-card -->
<?php endif; ?>

</div><!-- .container -->
</div><!-- .page-wrap -->

<!-- ✅ POPUP FORM (Step 3 already here) -->
<div id="prescriptionForm" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);">
  <div style="background:#fff;width:400px;margin:5% auto;padding:20px;border-radius:10px;">
    
    <h3>Add Prescription</h3>

    <form method="POST" enctype="multipart/form-data">
      
      <input type="hidden" name="email" id="p_email">
      
      <input type="text" name="name" id="p_name" readonly class="form-control"><br>

      <input type="number" name="age" placeholder="Age" class="form-control"><br>
      <input type="text" name="weight" placeholder="Weight" class="form-control"><br>
      <input type="text" name="height" placeholder="Height" class="form-control"><br>
      <input type="text" name="disease" placeholder="Disease" class="form-control"><br>

      <textarea name="medicines" placeholder="Medicines" class="form-control"></textarea><br>
      <textarea name="notes" placeholder="Notes" class="form-control"></textarea><br>

      <input type="file" name="file" class="form-control"><br>

      <button name="add_prescription" class="btn btn-primary">Submit</button>
      <button type="button" onclick="closeForm()" class="btn btn-danger">Cancel</button>

    </form>
  </div>
</div>

<!-- ✅ STEP 4 JAVASCRIPT GOES HERE -->
<script>
function openForm(name, email) {
    document.getElementById('prescriptionForm').style.display = 'block';
    document.getElementById('p_name').value = name;
    document.getElementById('p_email').value = email;
}

function closeForm() {
    document.getElementById('prescriptionForm').style.display = 'none';
}
</script>

<?php require 'footer.php'; ?>