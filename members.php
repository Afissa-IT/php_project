<?php
require 'config.php';
// Simple members list and add form for submission completeness
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_member'])) {
    $name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if ($name === '') $errors[] = 'Full name is required.';
    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO members (fullname, email, phone) VALUES (:f,:e,:p)');
        $stmt->execute([':f'=>$name, ':e'=>$email, ':p'=>$phone]);
        header('Location: members.php'); exit;
    }
}
$members = $pdo->query('SELECT * FROM members ORDER BY fullname')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Members</title><link rel="stylesheet" href="styles.css"></head><body>
<h2>Members</h2>
<?php if ($errors): ?><div class="errors"><ul><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
<table><thead><tr><th>Name</th><th>Email</th><th>Phone</th></tr></thead><tbody>
<?php foreach ($members as $m): ?>
<tr><td><?= htmlspecialchars($m['fullname']) ?></td><td><?= htmlspecialchars($m['email']) ?></td><td><?= htmlspecialchars($m['phone']) ?></td></tr>
<?php endforeach; ?></tbody></table>

<h3>Add member</h3>
<form method="post">
  <input type="hidden" name="add_member" value="1">
  <label>Full name <input name="fullname"></label>
  <label>Email <input name="email" type="email"></label>
  <label>Phone <input name="phone"></label>
  <button type="submit">Add</button>
</form>
<p><a href="index.php">Back to tools</a></p>
</body></html>