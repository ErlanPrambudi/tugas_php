<?php
    $barangPilih = 0;
    if(isset($_COOKIE['keranjang'])){
        $barangPilih = $_COOKIE['keranjang'];
    }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $barangPilih = $barangPilih.",".$id;
        setcookie('keranjang', $barangPilih, time()+3600);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Barang Tersedia</title>
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
        // menyisiplan file barang untuk dapat ditampilkan
        require_once 'barang.php';
        // fungsi select untuk menyeleksi atau menampilkan data barang yang akan ditampilkan
        $sql = "select * from barang where id not in (".$barangPilih.") and stok > 0 order by id desc";
        echo $sql."<br>"; // cek
        // membaca barang dengan menggunakan fungsi bacaBarang()
        $hasils = bacaBarang($sql);
        echo "<h2>DAFTAR BARANG TERSEDIA</h2>";
        if(count($hasils) > 0){
            // membuat tabel 
            echo "<table>";
            echo "<tr>
            <th>Foto</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Operasi</th>
            </tr>";

            // menampilkan record yang ada didalam tabel barang secara berurutan
            foreach($hasils as $hasil){
                echo "<tr>";
                echo "<td><img src='gambar/{$hasil['foto']}' width='100'></td>"; 
                echo "<td>{$hasil['nama']}</td>"; 
                echo "<td>{$hasil['harga']}</td>"; 
                echo "<td>{$hasil['stok']}</td>"; 
                echo "<td><a href='$_SERVER[PHP_SELF]?id={$hasil['id']}'>Beli</a></td>"; 
                echo "</tr>\n"; 
            }
            echo "</table>";
        }
    ?>
    <input type="button" value="Beli" onClick="window.location.assign('beli.php')">
    </div>
</body>
</html>