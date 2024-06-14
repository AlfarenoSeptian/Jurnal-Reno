<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('image/gunung.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .journal-list {
            margin-top: 20px;
        }

        .journal-entry {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .journal-entry p {
            margin: 5px 0;
        }

        .journal-entry .actions {
            display: flex;
            gap: 10px;
        }

        .journal-entry .actions button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            width: 60px;
        }

        .journal-entry .actions .edit {
            background-color: #ffc107;
            color: #fff;
        }

        .journal-entry .actions .delete {
            background-color: #dc3545;
            color: #fff;
        }

        /* Gaya untuk status tercapai */
        .tercapai {
            color: green;
            font-weight: bold;
        }

        /* Gaya untuk status tidak tercapai */
        .tidak-tercapai {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Pengisian Jurnal Harian</h1>
        <form method="post" action="insert.php" onsubmit="return validateForm()">
            <label for="timing">Timing:</label>
            <select name="timing" id="timing">
                <option value=""></option>
                <option value="Sesudah istirahat">Sesudah istirahat</option>
                <option value="Sebelum istirahat">Sebelum istirahat</option>
            </select><br><br>

            <label for="category">Kategori:</label>
            <select name="category" id="category">
                <option value=""></option>
                <option value="Penilaian">Penilaian</option>
                <option value="Zoom">Zoom</option>
            </select><br><br>

            <label for="instructor">Instruksi:</label>
            <select name="instructor" id="instructor">
                <option value=""></option>
                <option value="Mas Aji">Mas Aji</option>
                <option value="Mas Bariq">Mas Bariq</option>
            </select><br><br>

            <label for="description">Deskripsi Singkat:</label><br>
            <textarea name="description" id="description" rows="4" cols="50"></textarea><br><br>

            <label for="target_completion">Target Penyelesaian:</label><br>
            <input type="text" name="target_completion" id="target_completion"><br><br>

            <label for="status">Status Target:</label>
            <select name="status" id="status" onchange="updateStatusColor()">
                <option value=""></option>
                <option value="Tidak tercapai" class="tidak-tercapai">Tidak tercapai</option>
                <option value="Tercapai" class="tercapai">Tercapai</option>
            </select><br><br>

            <input type="submit" value="Submit">
        </form>

        <h2>Daftar Jurnal</h2>
        <div class="journal-list">
            <?php
            include 'db.php';

            function getStatusClass($status) {
                if ($status === "Tercapai") {
                    return "tercapai";
                } elseif ($status === "Tidak tercapai") {
                    return "tidak-tercapai";
                } else {
                    return "";
                }
            }

            $sql = "SELECT * FROM jurnal";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='journal-entry'>";
                    echo "<div>";
                    echo "<p><strong>Tanggal:</strong> " . $row["timestamp"] . "</p>";
                    echo "<p><strong>Timing:</strong> " . $row["timing"] . "</p>";
                    echo "<p><strong>Kategori:</strong> " . $row["category"] . "</p>";
                    echo "<p><strong>Instruksi:</strong> " . $row["instructor"] . "</p>";
                    echo "<p><strong>Deskripsi Singkat:</strong> " . $row["description"] . "</p>";
                    echo "<p><strong>Target Penyelesaian:</strong> " . $row["target_completion"] . "</p>";
                    echo "<p><strong>Status Target:</strong> <span class='" . getStatusClass($row["status"]) . "'>" . $row["status"] . "</span></p>";
                    echo "</div>";
                    echo "<div class='actions'>";
                    echo "<form action='edit.php' method='GET'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<button type='submit' class='edit'>Edit</button>";
                    echo "</form>";
                    echo "<form action='delete.php' method='POST'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<button type='submit' class='delete'>Delete</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "Belum ada kegiatan.";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function updateStatusColor() {
            var statusSelect = document.getElementById('status');
            var selectedOption = statusSelect.options[statusSelect.selectedIndex];
            
            // Menghapus kelas sebelumnya
            statusSelect.classList.remove('tercapai', 'tidak-tercapai');
            
            // Menambahkan kelas berdasarkan opsi yang dipilih
            if (selectedOption.value === "Tercapai") {
                statusSelect.classList.add('tercapai');
            } else if (selectedOption.value === "Tidak tercapai") {
                statusSelect.classList.add('tidak-tercapai');
            }
        }

        function validateForm() {
            var timing = document.getElementById('timing').value;
            var category = document.getElementById('category').value;
            var instructor = document.getElementById('instructor').value;
            var description = document.getElementById('description').value;
            var target_completion = document.getElementById('target_completion').value;
            var status = document.getElementById('status').value;

            if (timing === "" || category === "" || instructor === "" || description === "" || target_completion === "" || status === "") {
                alert("Harap lengkapi semua kolom!");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
