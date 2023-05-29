<?php
    // berisi data cookie
    $barangPilih = 0;
    // cookie akan dijalankan jika coo
    if(isset($_COOKIE['keranjang'])){
        $barangPilih = $_COOKIE['keranjang'];
    }

    // akan dijalankan saat user klik tombol 'batal'
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        // mengganti id yang telah dipilih pada keranjangbelanja.php
        // menjadi kosong atau menghapus id yang dipilih dengan klik tombol 'batal'
        $barangPilih = str_replace((",".$id),"",$barangPilih);
        // mensetting cookie akan berakhir sesuai dengan waktu yang ditentukan
        setcookie('keranjang', $barangPilih, time()+3600);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
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
            // menyisipkan file barang.php
            require_once 'barang.php';
            $sql = "select * from barang where id in (".$barangPilih.")order by id desc";
            echo $sql."<br>"; // cek
 
            $hasils = bacaBarang($sql);
            echo "<h2>KERANJANG BELANJA</h2>";
        
            if(count($hasils) > 0){
                echo "<table>";
                echo "<tr>
                <th>Foto</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Operasi</th>
                </tr>";
    
                foreach($hasils as $hasil){
                    echo "<tr>";
                    echo "<td><img src='gambar/{$hasil['foto']}' width='100'></td>"; 
                    echo "<td>{$hasil['nama']}</td>"; 
                    echo "<td>{$hasil['harga']}</td>"; 
                    echo "<td>{$hasil['stok']}</td>"; 
                    echo "<td><a href='$_SERVER[PHP_SELF]?id={$hasil['id']}'>Batal</a></td>"; 
                    echo "</tr>\n"; 
                }
                echo "</table>";
            }
        ?>
    </div>
</body>
</html>