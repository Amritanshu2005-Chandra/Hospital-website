<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$conn = new mysqli("localhost", "root", "Amrit@2020", "doctor_booking");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$msg = ""; $msg_type = "success";

// Login
if (isset($_POST['login'])) {
    $uid  = $conn->real_escape_string($_POST['uid']);
    $pass = $_POST['pass'];
    $res  = $conn->query("SELECT * FROM users WHERE user_id='$uid' AND password='$pass' AND role='staff'");
    if ($res->num_rows > 0) {
        $u = $res->fetch_assoc();
        $_SESSION['staff_name'] = $u['name'];
        $_SESSION['staff_id']   = $u['user_id'];
        header("Location: staff.php"); exit();
    } else { $msg = "Invalid Employee ID or Password"; $msg_type = "danger"; }
}
// Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['staff_name'], $_SESSION['staff_id']);
    header("Location: staff.php"); exit();
}
// Delete
if (isset($_GET['delete']) && isset($_SESSION['staff_id'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM appointments WHERE id=$id");
    header("Location: staff.php?deleted=1"); exit();
}
// Update
if (isset($_POST['update_appt']) && isset($_SESSION['staff_id'])) {
    $id       = intval($_POST['appt_id']);
    $new_name = $conn->real_escape_string($_POST['new_name']);
    $new_date = $conn->real_escape_string($_POST['new_date']);
    $conn->query("UPDATE appointments SET patient_name='$new_name', appointment_date='$new_date' WHERE id=$id");
    $msg = "Appointment updated successfully!"; $msg_type = "success";
}
if (isset($_GET['deleted'])) { $msg = "Appointment deleted."; $msg_type = "success"; }

// Fetch all appointments grouped by doctor
$grouped_data = [];
$total_appts = 0;
if (isset($_SESSION['staff_id'])) {
    $appts = $conn->query("SELECT * FROM appointments ORDER BY doctor_name ASC, appointment_date ASC");
    if ($appts) { while ($row = $appts->fetch_assoc()) { $grouped_data[$row['doctor_name']][] = $row; $total_appts++; } }
}

$active_module = 'staff';
require 'header.php';
?>

<!-- Page Banner -->
<div style="background:linear-gradient(135deg,var(--dark),#2d3f7a);padding:48px 0 36px;">
  <div class="container">
    <span class="section-label" style="color:var(--primary);border-color:var(--primary);">Staff Portal</span>
    <h1 style="color:white;font-size:2.2rem;margin-top:8px;">
      <?= isset($_SESSION['staff_id']) ? 'Welcome, '.htmlspecialchars($_SESSION['staff_name']) : 'Staff Login' ?>
    </h1>
    <p style="color:rgba(255,255,255,.7);margin-top:6px;">Employee Appointment Management</p>
  </div>
</div>

<div class="page-wrap page-wrap-alt">
<div class="container" style="padding-top:40px;">

<?php if (!isset($_SESSION['staff_id'])): ?>
<!-- ── LOGIN ─────────────────────────────────────────────────── -->
<div style="max-width:460px;margin:auto;">
  <?php if ($msg): ?>
  <div class="alert alert-<?= $msg_type ?>"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
  <div class="form-card">
    <div style="text-align:center;margin-bottom:28px;">
      <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--secondary),var(--dark));display:grid;place-items:center;font-size:2rem;margin:0 auto 16px;">👨‍💼</div>
      <h3>Staff Login</h3>
      <p class="text-muted">Access the appointment management system</p>
    </div>
    <form method="POST">
      <div class="form-group">
        <label class="form-label">Employee ID</label>
        <input name="uid" class="form-control" placeholder="Your staff employee ID" required>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="pass" class="form-control" placeholder="Your password" required>
      </div>
      <button class="btn btn-secondary btn-full" name="login">
        <i class="fa fa-sign-in-alt"></i> Login to Portal
      </button>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ── DASHBOARD ─────────────────────────────────────────────── -->

<!-- Header -->
<div class="dash-header" style="background:linear-gradient(135deg,var(--secondary),var(--dark));">
  <div>
    <h2>Staff Dashboard</h2>
    <p style="color:rgba(255,255,255,.7);">
      Logged in as <strong style="color:white;"><?= htmlspecialchars($_SESSION['staff_name']) ?></strong>
      &nbsp; | &nbsp; ID: <?= htmlspecialchars($_SESSION['staff_id']) ?>
    </p>
  </div>
  <a href="staff.php?logout=1" class="btn btn-outline-white btn-sm">
    <i class="fa fa-sign-out-alt"></i> Logout
  </a>
</div>

<!-- Stats -->
<div class="grid-2 mb-24" style="gap:16px;">
  <div class="dash-card" style="text-align:center;border-top:3px solid var(--primary);">
    <div style="font-size:2.4rem;font-family:'Playfair Display',serif;color:var(--primary);font-weight:900;"><?= $total_appts ?></div>
    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-top:4px;">TOTAL APPOINTMENTS</div>
  </div>
  <div class="dash-card" style="text-align:center;border-top:3px solid var(--secondary);">
    <div style="font-size:2.4rem;font-family:'Playfair Display',serif;color:var(--secondary);font-weight:900;"><?= count($grouped_data) ?></div>
    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-top:4px;">DOCTORS SCHEDULED</div>
  </div>
</div>

<?php if ($msg): ?>
<div class="alert alert-<?= $msg_type ?> mb-24"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<!-- Search -->
<div class="search-wrap">
  <i class="fa fa-search"></i>
  <input type="text" id="searchInput" class="search-input" onkeyup="filterData()" placeholder="Search by patient name or doctor…">
</div>

<!-- Appointments by doctor -->
<?php if (!empty($grouped_data)): ?>
<div id="dataWrapper">
  <?php foreach ($grouped_data as $doctor => $pat_list): ?>
  <div class="doc-section filter-card dash-card" data-doctor="<?= strtolower(htmlspecialchars($doctor)) ?>" style="margin-bottom:24px;">
    <div class="doc-section-header">
      <div class="doc-avatar-sm">👨‍⚕️</div>
      <div>
        <h4 style="color:var(--dark);margin:0;">Dr. <?= htmlspecialchars($doctor) ?></h4>
        <small style="color:var(--muted);"><?= count($pat_list) ?> appointment<?= count($pat_list)>1?'s':'' ?></small>
      </div>
      <span class="badge badge-primary" style="margin-left:auto;"><?= count($pat_list) ?></span>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Patient</th><th>Email</th><th>Date</th><th>Time Slot</th><th style="text-align:center;">Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($pat_list as $row): ?>
          <tr class="patient-row" data-name="<?= strtolower(htmlspecialchars($row['patient_name'])) ?>">
            <td><strong style="color:var(--dark);"><?= htmlspecialchars($row['patient_name']) ?></strong></td>
            <td><span style="font-size:12px;color:var(--muted);"><?= htmlspecialchars($row['email']) ?></span></td>
            <td><?= date('d M Y', strtotime($row['appointment_date'])) ?></td>
            <td><span class="badge badge-primary"><?= htmlspecialchars($row['appointment_time']) ?></span></td>
            <td style="text-align:center;">
              <div style="display:flex;gap:8px;justify-content:center;">
                <button class="btn btn-ghost btn-sm" onclick="toggleEdit(<?= $row['id'] ?>)">
                  <i class="fa fa-edit"></i> Edit
                </button>
                <a href="staff.php?delete=<?= $row['id'] ?>" class="btn btn-danger-soft btn-sm"
                   onclick="return confirm('Delete this appointment permanently?')">
                  <i class="fa fa-trash"></i> Delete
                </a>
              </div>
              <!-- Edit inline form -->
              <div id="edit-<?= $row['id'] ?>" class="edit-box" style="text-align:left;margin-top:10px;">
                <form method="POST">
                  <input type="hidden" name="appt_id" value="<?= $row['id'] ?>">
                  <div class="form-group">
                    <label class="form-label">Patient Name</label>
                    <input type="text" name="new_name" class="form-control" value="<?= htmlspecialchars($row['patient_name']) ?>" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Appointment Date</label>
                    <input type="date" name="new_date" class="form-control" value="<?= htmlspecialchars($row['appointment_date']) ?>" required>
                  </div>
                  <button type="submit" name="update_appt" class="btn btn-success btn-sm">
                    <i class="fa fa-save"></i> Save Changes
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php else: ?>
<div class="empty-state">
  <div class="icon">📋</div>
  <h4>No Appointments Found</h4>
  <p>There are no scheduled appointments in the system yet.</p>
</div>
<?php endif; ?>

<?php endif; ?>
</div><!-- .container -->
</div><!-- .page-wrap -->

<script>
function filterData() {
    var q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.filter-card').forEach(function(card) {
        var doc = card.getAttribute('data-doctor') || '';
        var visible = false;
        card.querySelectorAll('.patient-row').forEach(function(row) {
            var name = row.getAttribute('data-name') || '';
            var show = name.includes(q) || doc.includes(q);
            row.style.display = show ? '' : 'none';
            if (show) visible = true;
        });
        card.style.display = visible ? '' : 'none';
    });
}
function toggleEdit(id) {
    var box = document.getElementById('edit-' + id);
    if (!box) return;
    box.style.display = (box.style.display === 'block') ? 'none' : 'block';
}
</script>

<?php require 'footer.php'; ?>
