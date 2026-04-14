<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

// 🔒 CHECK LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: ../patient.php?view=login");
    exit();
}

// DB CONNECTION
$conn = new mysqli("localhost", "root", "Amrit@2020", "doctor_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// GET LOGGED-IN PATIENT EMAIL
$email = $conn->real_escape_string($_SESSION['user']);

// DELETE PRESCRIPTION
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM prescriptions WHERE id=$id");
    header("Location: prescription.php");
    exit();
}

// FETCH PATIENT DATA
$user_res = $conn->query("SELECT * FROM patients WHERE email='$email'");
$user = $user_res->fetch_assoc();

// FETCH PRESCRIPTIONS (only this patient's)
$sql = "SELECT * FROM prescriptions WHERE email='$email' ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Prescriptions — Chandra &amp; Sons</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f7fa; color: #333; }

        /* HEADER */
        .top-bar {
            background: linear-gradient(135deg, #0f2942, #1a3a5c);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .top-bar .brand { color: white; font-size: 1.2rem; font-weight: 700; }
        .top-bar .brand span { color: #13C5DD; }
        .top-bar a {
            color: white;
            text-decoration: none;
            font-size: 13px;
            background: rgba(255,255,255,0.15);
            padding: 7px 16px;
            border-radius: 20px;
            transition: background 0.2s;
        }
        .top-bar a:hover { background: rgba(255,255,255,0.25); }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, #13C5DD, #0f2942);
            padding: 32px;
            color: white;
        }
        .hero h1 { font-size: 1.6rem; }
        .hero p { opacity: 0.75; margin-top: 4px; font-size: 14px; }

        /* CONTAINER */
        .container { max-width: 1100px; margin: auto; padding: 32px 20px; }

        /* PATIENT INFO CARD */
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .info-card .avatar {
            width: 52px; height: 52px; border-radius: 50%;
            background: #13C5DD;
            display: grid; place-items: center;
            color: white; font-size: 1.4rem; flex-shrink: 0;
        }
        .info-card .name { font-weight: 700; font-size: 1rem; color: #0f2942; }
        .info-card .email { font-size: 12px; color: #888; margin-top: 3px; }
        .info-card .back-btn {
            margin-left: auto;
            background: #13C5DD;
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .info-card .back-btn:hover { background: #0fb3c9; }

        /* TABLE */
        .table-wrap {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .table-wrap table { width: 100%; border-collapse: collapse; }
        .table-wrap th {
            background: #13C5DD;
            color: white;
            padding: 14px 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .table-wrap td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            vertical-align: middle;
        }
        .table-wrap tr:last-child td { border-bottom: none; }
        .table-wrap tr:hover td { background: #f8fffe; }

        /* BADGES & BUTTONS */
        .btn-view {
            background: #13C5DD;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-delete {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-view:hover { background: #0fb3c9; }
        .btn-delete:hover { background: #dc2626; }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
        }
        .empty-state .icon { font-size: 3rem; margin-bottom: 12px; }
        .empty-state h4 { color: #555; margin-bottom: 6px; }
        .empty-state p { font-size: 14px; }

        /* MEDICINE TAG */
        .medicine-tag {
            display: inline-block;
            background: #e0f9fc;
            color: #0f7b8c;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin: 2px;
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
    <div class="brand">Chandra &amp; <span>Sons</span> Hospital</div>
    <a href="../patient.php"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
</div>

<!-- Hero -->
<div class="hero">
    <div style="max-width:1100px;margin:auto;padding:0 20px;">
        <h1><i class="fa fa-file-medical"></i> My Prescriptions</h1>
        <p>All prescriptions issued by your doctors at Chandra &amp; Sons Hospital</p>
    </div>
</div>

<div class="container">

    <!-- Patient Info -->
    <div class="info-card">
        <div class="avatar">👤</div>
        <div>
            <div class="name"><?= htmlspecialchars($user['name'] ?? 'Patient') ?></div>
            <div class="email"><?= htmlspecialchars($user['email'] ?? '') ?></div>
        </div>
        <a href="../patient.php" class="back-btn">
            <i class="fa fa-calendar-check"></i> Book Appointment
        </a>
    </div>

    <!-- Prescriptions Table -->
    <div class="table-wrap">
        <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Age</th>
                    <th>Weight</th>
                    <th>Height</th>
                    <th>Disease</th>
                    <th>Medicines</th>
                    <th>Notes</th>
                    <th>File</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['age'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['weight'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['height'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['disease'] ?? '-') ?></td>
                    <td>
                        <?php
                        $meds = explode(',', $row['medicines'] ?? '');
                        foreach ($meds as $m) {
                            $m = trim($m);
                            if ($m) echo '<span class="medicine-tag">' . htmlspecialchars($m) . '</span>';
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($row['notes'] ?? '-') ?></td>
                    <td>
                        <?php if (!empty($row['file'])): ?>
                        <a class="btn-view" href="../uploads/<?= htmlspecialchars($row['file']) ?>" target="_blank">
                            <i class="fa fa-eye"></i> View
                        </a>
                        <?php else: ?>
                        <span style="color:#aaa;font-size:13px;">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap;font-size:13px;color:#666;">
                        <?= date('d M Y', strtotime($row['created_at'])) ?>
                    </td>
                    <td>
                        <a class="btn-delete"
                           href="?delete_id=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this prescription?')">
                           <i class="fa fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <div class="icon">💊</div>
            <h4>No prescriptions found</h4>
            <p>Your doctor-issued prescriptions will appear here.</p>
        </div>
        <?php endif; ?>
    </div>

</div><!-- .container -->
</body>
</html>