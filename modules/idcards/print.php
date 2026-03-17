<?php
// modules/idcards/print.php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT ic.*, i.*, ag.age, ad.keb, ad.city
                       FROM id_cards ic
                       JOIN individuals i ON ic.resident_id = i.id
                       LEFT JOIN ages ag ON i.id = ag.id
                       LEFT JOIN addresses ad ON i.id = ad.id
                       WHERE ic.id = ?");
$stmt->execute([$id]);
$card = $stmt->fetch();

if (!$card) {
    exit('ID Card not found');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print ID - <?php echo $card['id_number']; ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #eee; }
        .id-card {
            width: 400px;
            height: 250px;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
            margin: 50px auto;
            border: 1px solid #ddd;
            overflow: hidden;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .header h3 { margin: 0; color: #2c3e50; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10px; color: #7f8c8d; }
        .photo-area {
            float: left;
            width: 100px;
            height: 120px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        .photo-area img { width: 100%; height: 100%; object-fit: cover; }
        .details {
            float: right;
            width: 250px;
            font-size: 12px;
        }
        .details table { width: 100%; border-collapse: collapse; }
        .details td { padding: 3px 0; }
        .label { font-weight: bold; color: #34495e; width: 80px; }
        .footer {
            clear: both;
            padding-top: 15px;
            font-size: 10px;
            text-align: center;
            color: #95a5a6;
        }
        .id-number {
            position: absolute;
            bottom: 10px;
            right: 20px;
            font-weight: bold;
            color: #e74c3c;
            font-size: 14px;
        }
        @media print {
            body { background: none; }
            .id-card { box-shadow: none; margin: 0; border: 1px solid #000; }
            .btn-print { display: none; }
        }
        .btn-print {
            display: block;
            width: 100px;
            margin: 20px auto;
            padding: 10px;
            background: #2c3e50;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <a href="#" onclick="window.print()" class="btn-print">Print ID Card</a>

    <div class="id-card">
        <div class="header">
            <h3>Hirmata Mentina Kebele</h3>
            <p>Resident Identification Card</p>
        </div>
        
        <div class="photo-area">
            <img src="../../assets/images/<?php echo $card['phot']; ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($card['fname']); ?>&size=120'">
        </div>
        
        <div class="details">
            <table>
                <tr>
                    <td class="label">Name:</td>
                    <td><?php echo $card['fname'] . ' ' . $card['lname']; ?></td>
                </tr>
                <tr>
                    <td class="label">Sex:</td>
                    <td><?php echo $card['s']; ?></td>
                </tr>
                <tr>
                    <td class="label">Age:</td>
                    <td><?php echo $card['age']; ?></td>
                </tr>
                <tr>
                    <td class="label">Occupation:</td>
                    <td><?php echo $card['occ']; ?></td>
                </tr>
                <tr>
                    <td class="label">Nationality:</td>
                    <td><?php echo $card['nat']; ?></td>
                </tr>
                <tr>
                    <td class="label">Kebele:</td>
                    <td><?php echo $card['keb']; ?></td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            Issued on: <?php echo date('d M Y', strtotime($card['issue_date'])); ?>
        </div>
        
        <div class="id-number">
            <?php echo $card['id_number']; ?>
        </div>
    </div>

</body>
</html>
