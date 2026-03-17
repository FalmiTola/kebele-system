<?php
// modules/houses/create.php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$residents = $pdo->query("SELECT id, fname, lname FROM individuals ORDER BY fname")->fetchAll();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hnum = $_POST['hnum'];
    $area = $_POST['area'];
    $door = $_POST['door'];
    $own_id = $_POST['own_id'];

    $stmt = $pdo->prepare("INSERT INTO houses (hnum, area, door, own_id) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$hnum, $area, $door, $own_id]);
        $success = "House #$hnum added successfully!";
    } catch (PDOException $e) {
        $error = "Failed to add house: " . $e->getMessage();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add New House</h2>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Houses</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <form method="POST" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">House Number</label>
                <input type="number" name="hnum" class="form-control" required placeholder="Enter unique house number">
            </div>
            <div class="mb-3">
                <label class="form-label">Area (Square Meters)</label>
                <input type="number" step="0.01" name="area" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Number of Doors</label>
                <input type="number" name="door" class="form-control" required value="1">
            </div>
            <div class="mb-3">
                <label class="form-label">House Owner</label>
                <select name="own_id" class="form-select" required>
                    <option value="">-- Select Owner --</option>
                    <?php foreach ($residents as $r): ?>
                        <option value="<?php echo $r['id']; ?>"><?php echo "{$r['fname']} {$r['lname']} (ID: #{$r['id']})"; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">If owner is not registered, please <a href="../residents/create.php">register them first</a>.</div>
            </div>
            <button type="submit" class="btn btn-success w-100 py-2">
                <i class="fas fa-save me-2"></i>Save House Record
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle me-2"></i>House Registration Info</h5>
            <p>Ensure that the house number is unique as per the Kebele records. Ownership must be assigned to a registered resident.</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
