<?php
// modules/houses/edit.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$hnum = $_GET['hnum'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM houses WHERE hnum = ?");
$stmt->execute([$hnum]);
$house = $stmt->fetch();

if (!$house) {
    header('Location: index.php');
    exit;
}

$residents = $pdo->query("SELECT id, fname, lname FROM individuals ORDER BY fname")->fetchAll();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $area = $_POST['area'];
    $door = $_POST['door'];
    $own_id = $_POST['own_id'];

    $stmt_update = $pdo->prepare("UPDATE houses SET area = ?, door = ?, own_id = ? WHERE hnum = ?");
    try {
        $stmt_update->execute([$area, $door, $own_id, $hnum]);
        $success = "House #$hnum updated successfully!";
        // Refresh data
        $stmt->execute([$hnum]);
        $house = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Update failed: " . $e->getMessage();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit House #<?php echo $hnum; ?></h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>

<?php if ($success): ?> <div class="alert alert-success"><?php echo $success; ?></div> <?php endif; ?>
<?php if ($error): ?> <div class="alert alert-danger"><?php echo $error; ?></div> <?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <form method="POST" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Area (Square Meters)</label>
                <input type="number" step="0.01" name="area" class="form-control" value="<?php echo $house['area']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Number of Doors</label>
                <input type="number" name="door" class="form-control" value="<?php echo $house['door']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">House Owner</label>
                <select name="own_id" class="form-select" required>
                    <?php foreach ($residents as $r): ?>
                        <option value="<?php echo $r['id']; ?>" <?php echo ($r['id'] == $house['own_id']) ? 'selected' : ''; ?>>
                            <?php echo "{$r['fname']} {$r['lname']} (ID: #{$r['id']})"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Update House Record</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
