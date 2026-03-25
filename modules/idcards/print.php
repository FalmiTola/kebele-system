<?php
// modules/idcards/print.php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT ic.*, i.*, ag.age, ag.bdate, ad.keb, ad.city, ad.zon, ad.region
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
    <title>ID Card - <?php echo $card['id_number']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <style>
        @page { size: A5 landscape; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #dce3ed;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 30px 20px;
        }

        .no-print {
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary { background: #1e3a8a; color: white; }
        .btn-secondary { background: #fff; color: #333; border: 1px solid #ccc; }

        /* ── ID Card Shell ── */
        .id-card {
            width: 600px;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
            border: 1px solid #e2e8f0;
        }

        /* ── Header Band ── */
        .card-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            padding: 0;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80px;
        }

        /* Flag corners */
        .card-header .flag-left,
        .card-header .flag-right {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 44px;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .card-header .flag-left  { left: 18px; }
        .card-header .flag-right { right: 18px; }

        /* Center branding */
        .card-header .header-center {
            text-align: center;
            padding: 14px 100px;
        }
        .card-header .header-center h5 {
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .card-header .header-center p {
            color: rgba(255,255,255,0.55);
            font-size: 9.5px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin: 0;
        }
        .card-header .id-badge {
            background: rgba(255,255,255,0.12);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 12px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            margin-top: 6px;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        /* ── Card Body ── */
        .card-body {
            display: flex;
            gap: 0;
            padding: 0;
        }

        /* Photo column */
        .photo-col {
            width: 130px;
            flex-shrink: 0;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 12px;
            gap: 10px;
        }
        .photo-col img {
            width: 90px;
            height: 110px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #1e3a8a;
        }
        .id-number-tag {
            background: #1e3a8a;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            letter-spacing: 1px;
            text-align: center;
            word-break: break-all;
        }

        /* Info column */
        .info-col {
            flex: 1;
            padding: 18px 20px;
        }
        .info-row {
            display: flex;
            align-items: baseline;
            padding: 5px 0;
            border-bottom: 1px dotted #e2e8f0;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            width: 110px;
            flex-shrink: 0;
            font-size: 10px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .info-value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        /* ── Card Footer ── */
        .card-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .issued-info {
            font-size: 10px;
            color: #64748b;
        }
        .issued-info span { font-weight: 700; color: #0f172a; }
        .signature-area {
            text-align: center;
            border-top: 1.5px solid #1e3a8a;
            padding-top: 6px;
            font-size: 9px;
            font-weight: 600;
            color: #1e3a8a;
            width: 160px;
        }

        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none !important; }
            .id-card { box-shadow: none; border-radius: 0; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">🖨 Print ID Card</button>
        <a href="index.php" class="btn btn-secondary">← Back to List</a>
    </div>

    <div class="id-card">

        <!-- ── Header with Flags at Corners ── -->
        <div class="card-header">
            <img src="../../assets/img/ethiopia_flag.png" alt="Ethiopia" class="flag-left">
            <div class="header-center">
                <h5>Hirmata Mentina Kebele</h5>
                <p>Oromia Region · Official Administrative Portal</p>
                <span class="id-badge">Resident Identification Card</span>
            </div>
            <img src="../../assets/img/oromia_flag.png" alt="Oromia" class="flag-right">
        </div>

        <!-- ── Body ── -->
        <div class="card-body">

            <!-- Photo + ID Number -->
            <div class="photo-col">
                <img
                    src="../../assets/images/<?php echo $card['phot']; ?>"
                    onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($card['fname'] . '+' . $card['lname']); ?>&size=200&background=1e3a8a&color=fff&bold=true'"
                    alt="Photo"
                >
                <div class="id-number-tag"><?php echo $card['id_number']; ?></div>
            </div>

            <!-- Details -->
            <div class="info-col">
                <div class="info-row">
                    <span class="info-label">Full Name</span>
                    <span class="info-value"><?php echo $card['fname'] . ' ' . $card['mname'] . ' ' . $card['lname']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sex / Age</span>
                    <span class="info-value"><?php echo $card['s']; ?> · <?php echo $card['age']; ?> yrs</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Birth</span>
                    <span class="info-value"><?php echo $card['bdate'] ? date('d M Y', strtotime($card['bdate'])) : '—'; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nationality</span>
                    <span class="info-value"><?php echo $card['nat']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Occupation</span>
                    <span class="info-value"><?php echo $card['occ']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kebele / Woreda</span>
                    <span class="info-value"><?php echo $card['keb']; ?><?php echo $card['city'] ? ', ' . $card['city'] : ''; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Zone / Region</span>
                    <span class="info-value"><?php echo ($card['zon'] ?? '') . ($card['region'] ? ', ' . $card['region'] : ''); ?></span>
                </div>
            </div>
        </div>

        <!-- ── Footer ── -->
        <div class="card-footer">
            <div class="issued-info">
                Issued on: <span><?php echo date('d M Y', strtotime($card['issue_date'])); ?></span><br>
                Valid until: <span><?php echo date('d M Y', strtotime($card['issue_date'] . ' +5 years')); ?></span>
            </div>
            <div class="signature-area">
                Authorized Signature<br>
                <small style="color:#94a3b8;">Kebele Administrator</small>
            </div>
        </div>

    </div>

</body>
</html>
