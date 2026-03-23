<?php
include "config.php";

$id= isset($_GET['id']) ? $_GET['id'] : null;

if (!isset($id) || !is_numeric($id))
{
die("invalid student id");
}

$stmt = $connection->prepare("SELECT ID FROM students WHERE id = '$id'");
$stmt->execute();

$student = $stmt->fetch(PDO::FETCH_ASSOC);
if (empty($student))
{
    die("enter Valid student id");
}

$stmt = $connection->prepare("DELETE FROM students WHERE id = '$id'");
$stmt->execute();

header("location:index.php");
exit;
?>

