<?php
include 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// HANDLE DELETE via GET
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM journal_notes WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: notes.php"); // Palitan kung iba ang filename ng main file mo
    exit();
}

// HANDLE SAVE & UPDATE via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $note_date = $_POST['note_date'];
    $title = $_POST['title'];
    $note_content = $_POST['note_content'];

    if ($action == 'save') {
        $stmt = $pdo->prepare("INSERT INTO journal_notes (note_date, title, note_content) VALUES (?, ?, ?)");
        $stmt->execute([$note_date, $title, $note_content]);
    } 
    elseif ($action == 'update') {
        $id = $_POST['note_id'];
        $stmt = $pdo->prepare("UPDATE journal_notes SET note_date = ?, title = ?, note_content = ? WHERE id = ?");
        $stmt->execute([$note_date, $title, $note_content, $id]);
    }

    header("Location: notes.php");
    exit();
}
?>