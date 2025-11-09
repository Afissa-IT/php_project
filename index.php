<?php
require 'config.php';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = 'SELECT * FROM tools';
$params = [];
if ($q !== '') {
    $sql .= ' WHERE name LIKE :q OR description LIKE :q';
    $params[':q'] = "%$q%";
}
$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tools = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Community Tool Library</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>Community Tool Library</h1>
    <nav><a href="create_tool.php">Add Tool</a> | <a href="members.php">Members</a> | <a href="loans.php">Loans</a></nav>
  </header>

  <section>
    <form method="get">
      <input type="search" name="q" placeholder="Search tools" value="<?= htmlspecialchars($q) ?>">
      <button type="submit">Search</button>
    </form>

    <?php if (count($tools) === 0): ?>
      <p>No tools found.</p>
    <?php else: ?>
      <table>
        <thead><tr><th>Name</th><th>Condition</th><th>Qty</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($tools as $t): ?>
          <tr>
            <td><a href="view_tool.php?id=<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></a></td>
            <td><?= htmlspecialchars($t['condition']) ?></td>
            <td><?= (int)$t['quantity'] ?></td>
            <td>
              <a href="edit_tool.php?id=<?= $t['id'] ?>">Edit</a> |
              <a href="delete_tool.php?id=<?= $t['id'] ?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>
</body>
</html>