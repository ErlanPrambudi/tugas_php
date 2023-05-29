<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pengambilan</title>
    <style>
        @media print{
            #tombol{
                display: none;
            } 
        }
 
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
 
        small{
            color: red;
        }
    </style>
</head>
<body>
    <div class="tengah">
        <div id="tombol">
            <input type="button" value="Ambil lagi" onClick="window.location.assign('matkultersedia.php')">
            <input type="button" value="Print" onClick="window.print()">
        </div>
        <?php
            // mengambil idhjual dari program beli.php
            $idkrs = $_GET['idkrs'];
            require_once "koneksitoko.php";
            $kon = koneksiToko();
            $sql = "select * from krs where idkrs = $idkrs ";
            $hasil = mysqli_query($kon, $sql);
            $row = mysqli_fetch_array($hasil);
            // tag pre digunakan untuk mengubah font huruf 
            echo "<pre>";
            echo "<h2>BUKTI PENGAMBILAN KRS</h2>";
            // no nota merupakan gabungan dari tanggal pembelian dan idhjual
            echo "NAMA : ".$row['nama']."<br>";
            echo "NIM : ".$row['nim']."<br>";
            echo "PRODI : ".$row['prodi']."<br>";
            echo "Matakuliah diambil : ";
            // mengambil nilai dai tabel barang dan tabel djual untuk dapat ditampilkan didalam tabel
            $sql = "select matakuliah.kodemk, matakuliah.nama, matakuliah.sks from matakuliah inner join dkrs on matakuliah.id = dkrs.idmk where dkrs.idkrs = $idkrs ";
            $hasil = mysqli_query($kon, $sql);
            echo "<table>";
            echo "<tr>";
            echo " <th> Kode </th>";
            echo " <th> Nama Matakuliah </th>";
            echo " <th> SKS </th>";
            echo "</tr>";

            $jumlah = 0;
            while($row = mysqli_fetch_array($hasil)){
                $jumlah += $row['sks'];
                echo "<tr>";
                echo " <td>".$row['kodemk']."</td>"; 
                echo " <td align='right'>".$row['nama']."</td>"; 
                echo " <td align='right'>".$row['sks']."</td>"; 
                echo "</tr>"; 
            }
            echo "<tr>";
            echo "<td>"."</td>";
            echo "<td align='right'><b> Jumlah sks </b></td>";
            echo "<td align='right'><b>".$jumlah."</b></td>";
            echo "</tr>";

            echo "</table>";
            echo "</pre>";
        ?>
    </div>
</body>
</html>