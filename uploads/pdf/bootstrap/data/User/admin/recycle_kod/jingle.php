<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['zip_file'])) {
    $zip = new ZipArchive;
    $file = $_FILES['zip_file']['tmp_name'];
    $extractTo = 'unzipped_' . time(); // Folder tujuan hasil ekstrak

    if ($zip->open($file) === TRUE) {
        mkdir($extractTo);
        $zip->extractTo($extractTo);
        $zip->close();
        echo "<p><strong>Berhasil diekstrak ke folder: $extractTo</strong></p>";

        // Tampilkan isi folder
        $files = scandir($extractTo);
        echo "<ul>";
        foreach ($files as $f) {
            if ($f != '.' && $f != '..') {
                echo "<li>$f</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "Gagal membuka file ZIP.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <h3>Upload ZIP File:</h3>
    <input type="file" name="zip_file" accept=".zip" required>
    <br><br>
    <button type="submit">Unzip Sekarang</button>
</form>