<?php
include 'db.php';

$message = ""; // Inisialisasi pesan untuk menampung informasi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $timing = $_POST['timing'];
    $category = $_POST['category'];
    $instructor = $_POST['instructor'];
    $description = $_POST['description'];
    $target_completion = $_POST['target_completion'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE jurnal SET timing = ?, category = ?, instructor = ?, description = ?, target_completion = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $timing, $category, $instructor, $description, $target_completion, $status, $id);

    if ($stmt->execute()) {
        $message = "Data berhasil diubah";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: index.php?message=" . urlencode($message));
    exit();
}

// Ambil data jurnal yang mau di edit dari database
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM jurnal WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Journal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS untuk mengatur warna background */
        body {
            background-image: url('image/gunung.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        input[type=text],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 16px;
            resize: vertical;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .journal-list {
            margin-top: 20px;
        }

        .journal-entry {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .journal-entry p {
            margin: 5px 0;
        }

        /* Gaya tambahan untuk dropdown status */
        select#status option[value="Tercapai"] {
            background-color: green;
            color: green;
        }

        select#status option[value="Tidak tercapai"] {
            background-color: red;
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Journal</h2>
        <form method="post" action="edit.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <label for="timing">Timing:</label>
            <select name="timing" id="timing">
                <option value="Sesudah istirahat" <?php if ($row['timing'] == 'Sesudah istirahat') echo 'selected'; ?>>
                    Sesudah istirahat
                </option>
                <option value="Sebelum istirahat" <?php if ($row['timing'] == 'Sebelum istirahat') echo 'selected'; ?>>
                    Sebelum istirahat
                </option>
            </select><br><br>

            <label for="category">Kategori:</label>
            <select name="category" id="category">
                <option value="Penilaian" <?php if ($row['category'] == 'Penilaian') echo 'selected'; ?>>Penilaian
                </option>
                <option value="Zoom" <?php if ($row['category'] == 'Zoom') echo 'selected'; ?>>Zoom
                </option>
            </select><br><br>

            <label for="instructor">Instruksi:</label>
            <select name="instructor" id="instructor">
                <option value="Mas Aji" <?php if ($row['instructor'] == 'Mas Aji') echo 'selected'; ?>>Mas Aji
                </option>
                <option value="Mas Bariq" <?php if ($row['instructor'] == 'Mas Bariq') echo 'selected'; ?>>Mas Bariq
                </option>
            </select><br><br>

            <label for="description">Deskripsi Singkat:</label><br>
            <textarea name="description" id="description" rows="4" cols="50"><?php echo $row['description']; ?></textarea><br><br>

            <label for="target_completion">Target Penyelesaian:</label><br>
            <input type="text" name="target_completion" id="target_completion" value="<?php echo $row['target_completion']; ?>"><br><br>

            <label for="status">Status Target:</label>
            <select name="status" id="status">
                <option value="Tidak tercapai" <?php if ($row['status'] == 'Tidak tercapai') echo 'selected'; ?>>Tidak tercapai
                </option>
                <option value="Tercapai" <?php if ($row['status'] == 'Tercapai') echo 'selected'; ?>>Tercapai
                </option>
            </select><br><br>

            <input type="submit" value="Update">
        </form>
    </div>
</body>

</html>
 