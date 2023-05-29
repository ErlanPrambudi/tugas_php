<?php
    // berisi data cookie
    $matakuliahPilih = 0;
    // cookie akan dijalankan jika cookie ada
    if(isset($_COOKIE['KRS'])){
        $matakuliahPilih = $_COOKIE['KRS'];
    }

    // akan dijalankan saat user klik tombol 'batal'
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        // mengganti id yang telah dipilih pada pengambilankrs.php
        // menjadi kosong atau menghapus id yang dipilih dengan klik tombol 'batal'
        $matakuliahPilih = str_replace((",".$id),"",$matakuliahPilih);
        // mensetting cookie akan berakhir sesuai dengan waktu yang ditentukan
        setcookie('KRS', $matakuliahPilih, time()+3600);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pengambilan KRS</title>
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
            require_once 'matakuliah.php';
            $sql = "select * from matakuliah where id in (".$matakuliahPilih.")order by id asc";
            echo $sql."<br>"; // cek
 
            $hasils = bacaMatakuliah($sql);
            echo "<h2>PEMILIHAN KRS</h2>";
        
            if(count($hasils) > 0){
                echo "<table>";
                echo "<tr>
                <th>Kode</th>
                <th>Nama Matakuliah</th>
                <th>SKS</th>
                <th>Operasi</th>
                </tr>";
    
                foreach($hasils as $hasil){
                    echo "<tr>"; 
                    echo "<td>{$hasil['kodemk']}</td>"; 
                    echo "<td>{$hasil['nama']}</td>"; 
                    echo "<td>{$hasil['sks']}</td>"; 
                    echo "<td><a href='$_SERVER[PHP_SELF]?id={$hasil['id']}'>Batal</a></td>"; 
                    echo "</tr>\n"; 
                }
                echo "</table>";
            }
        ?>
    </div>
</body>
</html>