<?php
// modules/residents/create.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
        }

        // 1. Insert into individuals
        $stmt = $pdo->prepare("INSERT INTO individuals (fname, lname, mname, mar, s, nat, level_edu, relg, occ, phot, mother_full_name, father_full_name, mother_nat, father_nat) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $mname = $_POST['mname'];
        $mar = $_POST['mar'];
        $s = $_POST['sex'];
        $nat = $_POST['nat'];
        $level_edu = $_POST['level_edu'];
        $relg = $_POST['relg'];
        $occ = $_POST['occ'];
        $mother_full_name = $_POST['mother_full_name'] ?? '';
        $father_full_name = $_POST['father_full_name'] ?? '';
        $mother_nat = $_POST['mother_nat'] ?? 'Itoophiyaa';
        $father_nat = $_POST['father_nat'] ?? 'Itoophiyaa';
        
        $phot = 'default.png';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $phot = time() . '_' . uniqid() . '.' . $ext;
            if (!is_dir("../../assets/images/")) {
                mkdir("../../assets/images/", 0777, true);
            }
            move_uploaded_file($_FILES['photo']['tmp_name'], "../../assets/images/" . $phot);
        }

        $stmt->execute([$fname, $lname, $mname, $mar, $s, $nat, $level_edu, $relg, $occ, $phot, $mother_full_name, $father_full_name, $mother_nat, $father_nat]);
        $resident_id = $pdo->lastInsertId();

        // 2. Insert into ages
        $bdate = $_POST['bdate'];
        $age = date_diff(date_create($bdate), date_create('today'))->y;
        $stmt_age = $pdo->prepare("INSERT INTO ages (id, bdate, age) VALUES (?, ?, ?)");
        $stmt_age->execute([$resident_id, $bdate, $age]);

        // 3. Insert into addresses
        $stmt_addr = $pdo->prepare("INSERT INTO addresses (id, region, zon, city, keb, pho_no, email) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_addr->execute([
            $resident_id,
            $_POST['region'],
            $_POST['zone'],
            $_POST['city'],
            $_POST['kebele'],
            $_POST['pho_no'],
            $_POST['email']
        ]);

        $pdo->commit();
        $success = "Resident registered successfully! ID: #$resident_id";
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = "Failed to register resident: " . $e->getMessage();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Register New Resident</h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    <div class="row g-3">
        <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-user me-2"></i>Personal Information</h5>
        <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input type="text" name="fname" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Middle Name (Father)</label>
            <input type="text" name="mname" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Last Name (Grandfather)</label>
            <input type="text" name="lname" class="form-control" required>
        </div>
        
        <div class="col-md-3">
            <label class="form-label">Birth Date</label>
            <input type="date" name="bdate" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Sex</label>
            <select name="sex" class="form-select" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Marital Status</label>
            <select name="mar" class="form-select" required>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Nationality</label>
            <input type="text" name="nat" class="form-control" value="Ethiopian" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Education Level</label>
            <input type="text" name="level_edu" class="form-control" placeholder="e.g. Degree, Grade 12">
        </div>
        <div class="col-md-4">
            <label class="form-label">Religion</label>
            <input type="text" name="relg" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Occupation</label>
            <input type="text" name="occ" class="form-control" required>
        </div>

        <h5 class="border-bottom pb-2 mt-4 text-primary"><i class="fas fa-users me-2"></i>Parental Information (For Birth Certificates)</h5>
        <div class="col-md-6">
            <label class="form-label">Mother's Full Name</label>
            <input type="text" name="mother_full_name" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Father's Full Name (If different from names above)</label>
            <input type="text" name="father_full_name" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Mother's Nationality</label>
            <input type="text" name="mother_nat" class="form-control" value="Itoophiyaa">
        </div>
        <div class="col-md-6">
            <label class="form-label">Father's Nationality</label>
            <input type="text" name="father_nat" class="form-control" value="Itoophiyaa">
        </div>

        <h5 class="border-bottom pb-2 mt-4 text-primary"><i class="fas fa-map-marker-alt me-2"></i>Contact & Address</h5>
        <div class="col-md-3">
            <label class="form-label">Region</label>
            <input type="text" name="region" class="form-control" value="Oromia" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Zone</label>
            <input type="text" name="zone" class="form-control" value="Jimma" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="Jimma" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Kebele</label>
            <input type="text" name="kebele" class="form-control" value="Hirmata Mentina" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Phone Number</label>
            <input type="text" name="pho_no" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Profile Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
        </div>

        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                <i class="fas fa-save me-2"></i>Register Resident
            </button>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
