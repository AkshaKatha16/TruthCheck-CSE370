<?php
require '../includes/auth_check.php';
require '../config/db.php';

$rows = $pdo->query("
    SELECT FACT_CHECK.*, CLAIM.claim_text, USER.user_name AS verifier_name
    FROM FACT_CHECK
    JOIN CLAIM ON FACT_CHECK.claim_id = CLAIM.claim_id
    LEFT JOIN USER ON FACT_CHECK.verifier_id = USER.user_id
    ORDER BY FACT_CHECK.checked_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

$prefix = '../';
$active = 'factcheck';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Fact Checks - TruthCheck</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="app-layout">
        <?php include '../includes/sidebar.php'; ?>
        <div class="main-content">
        <div class="container">
        <div class="page-title"><h2>✅ সব Fact-Check Verdict</h2></div>
        <table class="data-table">
            <tr><th>Claim</th><th>Verdict</th><th>Explanation</th><th>Checked By</th><th>Date</th><th></th></tr>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= htmlspecialchars(mb_strimwidth($r['claim_text'], 0, 40, '...')) ?></td>
                <td><span class="status status-<?= $r['verdict']==='True' ? 'verified' : ($r['verdict']==='False' ? 'rejected' : 'pending') ?>"><?= htmlspecialchars($r['verdict']) ?></span></td>
                <td><?= htmlspecialchars(mb_strimwidth($r['explanation'], 0, 50, '...')) ?></td>
                <td><?= htmlspecialchars($r['verifier_name'] ?? '—') ?></td>
                <td><?= htmlspecialchars($r['checked_at']) ?></td>
                <td><a href="../claims/view.php?id=<?= $r['claim_id'] ?>">Open</a></td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td colspan="6" style="text-align:center;color:#999">এখনো কোনো verdict দেওয়া হয়নি</td></tr><?php endif; ?>
        </table>
        </div>
        </div>
    </div>
</body>
</html>
