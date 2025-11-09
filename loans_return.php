<?php
require 'config.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$stmt = $pdo->prepare('SELECT * FROM loans WHERE id = :id');
$stmt->execute([':id'=>$id]);
$loan = $stmt->fetch();
if (!$loan) { echo 'Loan not found'; exit; }
$pdo->prepare('UPDATE loans SET returned_at = NOW() WHERE id = :id')->execute([':id'=>$id]);
header('Location: view_tool.php?id='.$loan['tool_id']);
exit;
?>