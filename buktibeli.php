<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pembelian</title>
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
            <input type="button" value="Beli lagi" onClick="window.location.assign('barangtersedia.php')">
            <input type="button" value="Print" onClick="window.print()">
        </div>
        <?php
            // mengambil idhjual dari program beli.php
            $idhjual = $_GET['idhjual'];
            require_once "koneksitoko.php";
            $kon = koneksiToko();
            $sql = "select * from hjual where idhjual = $idhjual ";
            $hasil = mysqli_query($kon, $sql);
            $row = mysqli_fetch_array($hasil);
            // tag pre digunakan untuk mengubah font huruf 
            echo "<pre>";
            echo "<h2>BUKTI PEMBELIAN</h2>";
            // no nota merupakan gabungan dari tanggal pembelian dan idhjual
            echo "NO. NOTA : ".date("Ymd").$row['idhjual']."<br>";
            echo "TANGGAL : ".$row['tanggal']."<br>";
            echo "NAMA : ".$row['namacust']."<br>";
            // mengambil nilai dai tabel barang dan tabel djual untuk dapat ditampilkan didalam tabel
            $sql = "select barang.nama, djual.harga, djual.qty, (djual.harga * djual.qty) as jumlah from djual inner join barang on djual.idbarang = barang.id where djual.idhjual = $idhjual ";
            $hasil = mysqli_query($kon, $sql);
            echo "<table>";
            echo "<tr>";
            echo " <th> Nama Barang </th>";
            echo " <th> Quantity </th>";
            echo " <th> Harga </th>";
            echo " <th> Jumlah </th>";
            echo "</tr>";

            $jumlah = 0;
            while($row = mysqli_fetch_array($hasil)){
                $jumlah += $row['jumlah'];
                echo "<tr>";
                echo " <td>".$row['nama']."</td>"; 
                echo " <td align='right'>".$row['qty']."</td>"; 
                echo " <td align='right'>".$row['harga']."</td>"; 
                echo " <td align='right'>".$row['jumlah']."</td>"; 
                echo "</tr>"; 
            }
            echo "<tr>";
            echo "<td>"."</td>";
            echo "<td>"."</td>";
            echo "<td align='right'><b> Total Harga </b></td>";
            echo "<td align='right'><b>".$jumlah."</b></td>";
            echo "</tr>";
 
            echo "</table>";
            echo "</pre>";
        ?>
    </div>
</body>
</html>