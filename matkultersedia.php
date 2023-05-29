<?php
    $matakuliahPilih = 0;
    if(isset($_COOKIE['KRS'])){
        $matakuliahPilih = $_COOKIE['KRS'];
    }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $matakuliahPilih = $matakuliahPilih.",".$id;
        setcookie('KRS', $matakuliahPilih, time()+3600);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Matakuliah Tersedia</title>
    <style>
        table, td, th {
            border: 1px solid gray;
        }
 
        table {
            border-collapse: collapse;
        }
 
        .tengah{
            width: 75%;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="tengah">
    <?php
        // menyisiplan file matakuliah untuk dapat ditampilkan
        require_once 'matakuliah.php';
        // fungsi select untuk menyeleksi atau menampilkan data matakuliah yang akan ditampilkan
        $sql = "select * from matakuliah where id not in (".$matakuliahPilih.") and sks > 0 order by id asc";
        echo $sql."<br>"; // cek
        // membaca matakuliah dengan menggunakan fungsi bacaMatakuliah()
        $hasils = bacaMatakuliah($sql);
        echo "<h2>DAFTAR MATAKULIAH TERSEDIA</h2>";
        if(count($hasils) > 0){
            // membuat tabel 
            echo "<table>";
            echo "<tr>
            <th>Kode</th>
            <th>Nama Matakuliah</th>
            <th>Sks</th>
            <th>Operasi</th>
            </tr>";

            // menampilkan record yang ada didalam tabel matakuliah secara berurutan
            foreach($hasils as $hasil){
                echo "<tr>"; 
                echo "<td>{$hasil['kodemk']}</td>"; 
                echo "<td>{$hasil['nama']}</td>"; 
                echo "<td>{$hasil['sks']}</td>"; 
                echo "<td><a href='$_SERVER[PHP_SELF]?id={$hasil['id']}'>Ambil</a></td>"; 
                echo "</tr>\n"; 
            }
            echo "</table>";
        }
    ?>
    </div>
</body>
</html>