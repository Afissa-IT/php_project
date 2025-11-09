<?php
require 'config.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $stmt = $pdo->prepare('DELETE FROM tools WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
    header('Location: index.php'); exit;
}
$stmt = $pdo->prepare('SELECT * FROM tools WHERE id = :id');
$stmt->execute([':id'=>$id]);
$tool = $stmt->fetch();
if (!$tool) { echo 'Tool not found'; exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Delete Tool</title><link rel="stylesheet" href="styles.css"></head><body>
<h2>Delete Tool</h2>
<p>Are you sure you want to delete "<?= htmlspecialchars($tool['name']) ?>"?</p>
<form method="post">
  <button name="confirm" value="yes">Yes, delete</button>
  <a href="index.php">Cancel</a>
</form>
</body></html>