<?php
require 'config.php';
$errors = [];
$name = '';
$description = '';
$condition = 'Good';
$quantity = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $condition = $_POST['condition'] ?? 'Good';
    $quantity = (int)($_POST['quantity'] ?? 1);

    if ($name === '') $errors[] = 'Name is required.';
    if ($quantity < 0) $errors[] = 'Quantity must be 0 or more.';
    $allowed_conditions = ['New','Good','Fair','Needs Repair'];
    if (!in_array($condition, $allowed_conditions)) $errors[] = 'Invalid condition.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO tools (name, description, `condition`, quantity) VALUES (:name,:desc,:cond,:qty)');
        $stmt->execute([
            ':name' => $name,
            ':desc' => $description,
            ':cond' => $condition,
            ':qty' => $quantity
        ]);
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Add Tool</title><link rel="stylesheet" href="styles.css"></head><body>
<h2>Add Tool</h2>
<?php if ($errors): ?><div class="errors"><ul><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
<form method="post">
  <label>Name <input name="name" value="<?= htmlspecialchars($name) ?>"></label>
  <label>Description <textarea name="description"><?= htmlspecialchars($description) ?></textarea></label>
  <label>Condition
    <select name="condition">
      <?php foreach (['New','Good','Fair','Needs Repair'] as $c): ?>
        <option value="<?= $c ?>" <?= $c===$condition ? 'selected' : '' ?>><?= $c ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Quantity <input type="number" name="quantity" value="<?= (int)$quantity ?>"></label>
  <button type="submit">Save</button>
  <a href="index.php">Cancel</a>
</form>
</body></html>