<?php
require 'config.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$stmt = $pdo->prepare('SELECT * FROM tools WHERE id = :id');
$stmt->execute([':id'=>$id]);
$tool = $stmt->fetch();
if (!$tool) { echo 'Tool not found'; exit; }

$stmt = $pdo->prepare('SELECT l.*, m.fullname FROM loans l JOIN members m ON m.id = l.member_id WHERE l.tool_id = :id ORDER BY l.loaned_at DESC');
$stmt->execute([':id'=>$id]);
$loans = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title><?= htmlspecialchars($tool['name']) ?></title><link rel="stylesheet" href="styles.css"></head><body>
<h2><?= htmlspecialchars($tool['name']) ?></h2>
<p><strong>Condition:</strong> <?= htmlspecialchars($tool['condition']) ?></p>
<p><strong>Quantity:</strong> <?= (int)$tool['quantity'] ?></p>
<p><?= nl2br(htmlspecialchars($tool['description'])) ?></p>

<h3>Loan history</h3>
<?php if (!$loans): ?>
  <p>No loans yet. <a href="loans_create.php?tool_id=<?= $tool['id'] ?>">Lend this tool</a></p>
<?php else: ?>
  <table>
    <thead><tr><th>Member</th><th>Loaned at</th><th>Due</th><th>Returned</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($loans as $l): ?>
        <tr>
          <td><?= htmlspecialchars($l['fullname']) ?></td>
          <td><?= htmlspecialchars($l['loaned_at']) ?></td>
          <td><?= htmlspecialchars($l['due_date']) ?></td>
          <td><?= $l['returned_at'] ? htmlspecialchars($l['returned_at']) : '—' ?></td>
          <td>
            <?php if (!$l['returned_at']): ?>
              <a href="loans_return.php?id=<?= $l['id'] ?>">Mark returned</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<p><a href="index.php">Back to list</a></p>
</body></html>