<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$conn = new mysqli("localhost", "root", "Amrit@2020", "doctor_booking");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Auth guard
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin.php"); exit();
}

$msg = ""; $msg_type = "success";

// ── DELETE PATIENT (with appointments + prescriptions) ────────────────────────
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Delete profile photo file
    $findPhoto = $conn->query("SELECT photo, email FROM patients WHERE id=$id");
    $patRow = $findPhoto ? $findPhoto->fetch_assoc() : null;
    if ($patRow) {
        if (!empty($patRow['photo']) && file_exists($patRow['photo'])) {
            unlink($patRow['photo']);
        }
        $patEmail = $conn->real_escape_string($patRow['email']);

        // Delete uploaded prescription files
        $presFiles = $conn->query("SELECT file FROM prescriptions WHERE email='$patEmail'");
        if ($presFiles) {
            while ($pf = $presFiles->fetch_assoc()) {
                if (!empty($pf['file']) && file_exists("uploads/" . $pf['file'])) {
                    unlink("uploads/" . $pf['file']);
                }
            }
        }

        // Delete from all related tables
        $conn->query("DELETE FROM appointments  WHERE email='$patEmail'");
        $conn->query("DELETE FROM prescriptions WHERE email='$patEmail'");
        $conn->query("DELETE FROM patients       WHERE id=$id");

        header("Location: patients_db.php?msg=Patient+and+all+records+deleted+successfully");
        exit();
    }
}

// ── DELETE SINGLE PRESCRIPTION ───────────────────────────────────────────────
if (isset($_GET['delete_pres'])) {
    $pres_id = intval($_GET['delete_pres']);
    // Delete attached file if exists
    $pf = $conn->query("SELECT file FROM prescriptions WHERE id=$pres_id");
    if ($pf && $pfRow = $pf->fetch_assoc()) {
        if (!empty($pfRow['file']) && file_exists("uploads/" . $pfRow['file'])) {
            unlink("uploads/" . $pfRow['file']);
        }
    }
    $conn->query("DELETE FROM prescriptions WHERE id=$pres_id");
    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}

// ── FETCH PATIENTS ────────────────────────────────────────────────────────────
$patients_result = $conn->query("SELECT id, name, email, phone, photo FROM patients ORDER BY id DESC");
$total = $patients_result ? $patients_result->num_rows : 0;

// ── FETCH PRESCRIPTIONS (for modal) ──────────────────────────────────────────
// Build a map: email → [prescriptions]
$prescriptions_map = [];
$pres_res = $conn->query("SELECT * FROM prescriptions ORDER BY id DESC");
if ($pres_res) {
    while ($pr = $pres_res->fetch_assoc()) {
        $prescriptions_map[$pr['email']][] = $pr;
    }
}

$active_module = 'admin';
require 'header.php';
?>

<!-- Page Banner -->
<div style="background:linear-gradient(135deg,#1a1a2e,var(--dark));padding:48px 0 36px;">
  <div class="container">
    <span class="section-label" style="color:var(--primary);border-color:var(--primary);">Admin / Patient Database</span>
    <h1 style="color:white;font-size:2.2rem;margin-top:8px;">Patient Database</h1>
    <p style="color:rgba(255,255,255,.7);margin-top:6px;">All registered patients — <?= $total ?> records</p>
  </div>
</div>

<div class="page-wrap page-wrap-alt">
<div class="container" style="padding-top:40px;">

  <?php if (isset($_GET['msg'])): ?>
  <div class="alert alert-success mb-24">
    <i class="fa fa-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?>
  </div>
  <?php endif; ?>

  <!-- Toolbar -->
  <div class="flex-between mb-24" style="flex-wrap:wrap;gap:12px;">
    <div>
      <h3 style="color:var(--dark);">All Registered Patients</h3>
      <p class="text-muted"><?= $total ?> patient<?= $total!=1?'s':'' ?> in database</p>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="admin.php" class="btn btn-ghost btn-sm">
        <i class="fa fa-arrow-left"></i> Back to Admin
      </a>
    </div>
  </div>

  <!-- Search -->
  <div class="search-wrap">
    <i class="fa fa-search"></i>
    <input type="text" id="searchInput" class="search-input" onkeyup="filterTable()" placeholder="Search by name, email or phone…">
  </div>

  <div class="dash-card">
    <div class="table-wrap">
      <table id="patientsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Email Address</th>
            <th>Phone</th>
            <th style="text-align:center;">Prescriptions</th>
            <th style="text-align:center;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($patients_result && $patients_result->num_rows > 0):
            while ($row = $patients_result->fetch_assoc()):
              $email      = $row['email'];
              $pres_list  = $prescriptions_map[$email] ?? [];
              $pres_count = count($pres_list);
          ?>
          <tr class="patient-row">
            <td class="text-muted">#<?= $row['id'] ?></td>
            <td>
              <img src="<?= htmlspecialchars($row['photo']) ?>"
                   class="avatar"
                   onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($row['name']) ?>&background=13C5DD&color=fff&size=44'"
                   alt="<?= htmlspecialchars($row['name']) ?>">
            </td>
            <td>
              <strong style="color:var(--dark);"><?= htmlspecialchars($row['name']) ?></strong>
            </td>
            <td style="font-size:13px;color:var(--muted);"><?= htmlspecialchars($email) ?></td>
            <td style="font-size:13px;"><?= htmlspecialchars($row['phone']) ?></td>

            <!-- ── Prescriptions column ── -->
            <td style="text-align:center;">
              <?php if ($pres_count > 0): ?>
                <button class="btn btn-primary btn-sm"
                        onclick="openPrescription(<?= $row['id'] ?>)">
                  <i class="fa fa-file-medical"></i> View (<?= $pres_count ?>)
                </button>
              <?php else: ?>
                <span style="font-size:12px;color:var(--muted);">No prescriptions</span>
              <?php endif; ?>
            </td>

            <!-- ── Delete column ── -->
            <td style="text-align:center;">
              <a href="patients_db.php?delete_id=<?= $row['id'] ?>"
                 class="btn btn-danger-soft btn-sm"
                 onclick="return confirm('⚠️ Permanently delete <?= addslashes($row['name']) ?> AND all their appointments & prescriptions?')">
                <i class="fa fa-trash"></i> Delete All
              </a>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr>
            <td colspan="7">
              <div class="empty-state">
                <div class="icon">👥</div>
                <h4>No Patients Registered</h4>
                <p>Patients will appear here once they register through the patient portal.</p>
              </div>
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div><!-- .container -->
</div><!-- .page-wrap -->

<!-- ══ PRESCRIPTION MODAL ════════════════════════════════════════════════════ -->
<div id="prescriptionModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;overflow-y:auto;padding:40px 16px;">
  <div style="background:#fff;max-width:720px;margin:auto;border-radius:12px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.3);">

    <!-- Modal Header -->
    <div style="background:linear-gradient(135deg,var(--primary),var(--secondary));padding:20px 28px;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="font-size:1.6rem;">💊</div>
        <div>
          <h3 style="color:white;margin:0;" id="modalPatientName">Prescriptions</h3>
          <small style="color:rgba(255,255,255,.75);" id="modalPatientEmail"></small>
        </div>
      </div>
      <button onclick="closeModal()"
              style="background:rgba(255,255,255,.2);border:none;color:white;width:36px;height:36px;border-radius:50%;font-size:1.2rem;cursor:pointer;display:grid;place-items:center;">✕</button>
    </div>

    <!-- Modal Body -->
    <div id="modalBody" style="padding:28px;max-height:70vh;overflow-y:auto;">
      <!-- Filled by JS -->
    </div>

  </div>
</div>

<!-- ══ PRESCRIPTION DATA (PHP → JS) ══════════════════════════════════════════ -->
<script>
const prescriptionData = <?php
  // Build a JS-safe array indexed by patient id
  $patients_result2 = $conn->query("SELECT id, name, email FROM patients ORDER BY id DESC");
  $jsData = [];
  if ($patients_result2) {
      while ($r = $patients_result2->fetch_assoc()) {
          $plist = $prescriptions_map[$r['email']] ?? [];
          $jsData[$r['id']] = [
              'name'  => $r['name'],
              'email' => $r['email'],
              'list'  => $plist
          ];
      }
  }
  echo json_encode($jsData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
?>;

function openPrescription(patientId) {
    const data  = prescriptionData[patientId];
    if (!data) return;

    document.getElementById('modalPatientName').textContent  = data.name + " — Prescriptions";
    document.getElementById('modalPatientEmail').textContent = data.email;

    let html = '';
    data.list.forEach((p, idx) => {
        html += `
        <div style="border:1px solid #e8eaf0;border-radius:10px;padding:20px;margin-bottom:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                <h4 style="margin:0;color:#1a1a2e;">Prescription #${idx + 1}</h4>
                ${p.disease ? `<span style="background:#eef6ff;color:#354f8e;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;">${escHtml(p.disease)}</span>` : ''}
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:13px;margin-bottom:14px;">
                ${p.age    ? `<div><span style="color:#888;">Age:</span> <strong>${escHtml(p.age)}</strong></div>` : ''}
                ${p.weight ? `<div><span style="color:#888;">Weight:</span> <strong>${escHtml(p.weight)}</strong></div>` : ''}
                ${p.height ? `<div><span style="color:#888;">Height:</span> <strong>${escHtml(p.height)}</strong></div>` : ''}
            </div>
            ${p.medicines ? `
            <div style="background:#f8f9ff;border-radius:8px;padding:12px;margin-bottom:10px;">
                <div style="font-size:11px;font-weight:700;color:#888;margin-bottom:6px;text-transform:uppercase;">Medicines</div>
                <div style="font-size:13px;white-space:pre-wrap;">${escHtml(p.medicines)}</div>
            </div>` : ''}
            ${p.notes ? `
            <div style="background:#fffbf0;border-radius:8px;padding:12px;margin-bottom:10px;">
                <div style="font-size:11px;font-weight:700;color:#888;margin-bottom:6px;text-transform:uppercase;">Doctor's Notes</div>
                <div style="font-size:13px;white-space:pre-wrap;">${escHtml(p.notes)}</div>
            </div>` : ''}
            ${p.file ? `
            <div style="margin-top:10px;">
                <a href="uploads/${escHtml(p.file)}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:6px;background:#13c5dd;color:white;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:13px;font-weight:600;">
                    <i class="fa fa-paperclip"></i> View Attached File
                </a>
            </div>` : ''}
            <div style="margin-top:14px;text-align:right;">
                <button onclick="deletePrescription(${p.id}, ${patientId})"
                        style="background:#fff0f0;color:#e74c3c;border:1px solid #f5c6c6;padding:7px 16px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                    <i class="fa fa-trash"></i> Delete This Prescription
                </button>
            </div>
        </div>`;
    });

    if (!html) html = '<p style="color:#888;text-align:center;padding:40px;">No prescriptions found.</p>';
    document.getElementById('modalBody').innerHTML = html;
    document.getElementById('prescriptionModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function deletePrescription(presId, patientId) {
    if (!confirm('Delete this prescription permanently?')) return;
    fetch('patients_db.php?delete_pres=' + presId)
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                // Remove from local data
                const data = prescriptionData[patientId];
                data.list = data.list.filter(p => p.id != presId);
                // Update button count in table
                const btn = document.querySelector(`button[onclick="openPrescription(${patientId})"]`);
                if (btn) {
                    if (data.list.length > 0) {
                        btn.innerHTML = `<i class="fa fa-file-medical"></i> View (${data.list.length})`;
                    } else {
                        btn.parentElement.innerHTML = '<span style="font-size:12px;color:#888;">No prescriptions</span>';
                    }
                }
                // Re-render modal
                if (data.list.length > 0) {
                    openPrescription(patientId);
                } else {
                    closeModal();
                }
            }
        });
}

function closeModal() {
    document.getElementById('prescriptionModal').style.display = 'none';
    document.body.style.overflow = '';
}

// Close modal on backdrop click
document.getElementById('prescriptionModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

function escHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function filterTable() {
    var q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#patientsTable tbody .patient-row').forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>

<?php require 'footer.php'; ?>