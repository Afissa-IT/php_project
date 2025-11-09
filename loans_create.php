<?php
require 'config.php';
$tool_id = (int)($_GET['tool_id'] ?? 0);
$members = $pdo->query('SELECT * FROM members ORDER BY fullname')->fetchAll();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tool_id = (int)($_POST['tool_id']);
    $member_id = (int)($_POST['member_id']);
    $due_date = $_POST['due_date'] ?? null;
    if (!$member_id) $errors[] = 'Select a member.';
    if (!$due_date) $errors[] = 'Provide a due date.';
    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO loans (tool_id, member_id, due_date) VALUES (:t,:m,:d)');
        $stmt->execute([':t'=>$tool_id,':m'=>$member_id,':d'=>$due_date]);
        header('Location: view_tool.php?id='.$tool_id);
        exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Lend Tool</title><link rel="stylesheet" href="styles.css"></head><body>
<h2>Lend Tool</h2>
<?php if ($errors): ?><div class="errors"><ul><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
<form method="post">
  <input type="hidden" name="tool_id" value="<?= (int)$tool_id ?>">
  <label>Member
    <select name="member_id">
      <option value="">-- choose --</option>
      <?php foreach ($members as $m): ?>
        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['fullname']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Due date <input type="date" name="due_date"></label>
  <button type="submit">Lend</button>
  <a href="view_tool.php?id=<?= $tool_id ?>">Cancel</a>
</form>
</body></html>