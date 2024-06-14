<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $timing = $_POST['timing'];
    $category = $_POST['category'];
    $instructor = $_POST['instructor'];
    $description = $_POST['description'];
    $target_completion = $_POST['target_completion'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO jurnal (timing, category, instructor, description, target_completion, status) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $timing, $category, $instructor, $description, $target_completion, $status);

    if ($stmt->execute()) {
        $message = "Data berhasil ditambahkan";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

header("Location: index.php?message=" . urlencode($message));
exit();
?>
