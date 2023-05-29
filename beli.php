<?php
    $barangPilih = 0;
    if(isset($_COOKIE['keranjang'])){
        $barangPilih = $_COOKIE['keranjang'];
    }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $barangPilih = str_replace((",".$id),"",$barangPilih);
        setcookie('keranjang', $barangPilih, time()+3600);
    }

    $namacustErr = "";
    $emailErr = "";
    $notelpErr = "";
    $barangPilihErr = "";
    $namacust = "";
    $email = "";
    $notelp = "";
    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

    // Jika diklik tombol Simpan
    if ($request_method === 'POST'){
        $namacust = htmlspecialchars($_POST['namacust']);
        if(empty($namacust)){
            $namacustErr = "Nama belum diisi";
        }
        $email = htmlspecialchars($_POST['email']);
        if(empty($email)){
            $emailErr = "Email belum diisi";
        }
        
        $notelp = htmlspecialchars($_POST['notelp']);
        if(empty($notelp)){
            $notelpErr = "No. Telepon belum diisi";
        }
        $tanggal = date("Y-m-d");
 
        if(!isset($_COOKIE['keranjang'])){
            $barangPilihErr = "<br><small>Keranjang belanja kosong</small><br>"; 
        }
 
        // jika tidak ada error data siap disimpan 
        if(empty($namacustErr) && empty($emailErr) && empty($notelpErr) && empty($barangPilihErr)){
            $qty = 1;
            $simpan = true;
            require_once "koneksitoko.php";
            $kon = koneksiToko();
 
            // memulai transaksi menyimpan ke database
            $mulaiTransaksi = mysqli_begin_transaction($kon);
            $sql = "insert into hjual (tanggal, namacust, email, notelp) value ('$tanggal','$namacust','$email','$notelp')";
            $hasil = mysqli_query($kon, $sql);
            
            // jika tidak berhasil disimpan
            if(!$hasil){
                echo "Data customer gagal disimpan <br>";
                $simpan = false;
            }

            // mendapatkan id dr query terakhir
            // mengambil id yang terakhir
            $idhjual = mysqli_insert_id($kon);
            
            // jika idhjual nya adalah 0 atau tidak ada nilai maka akan djalankan
            if($idhjual == 0){
                echo "Data customer tidak ada <br>";
                $simpan = false;
            }
 
            // mengkonversi string separator ',' $barangPilih menjadi array
            // explode digunaan untuk mengkonversi nilai string menjadi array
            $barang_array = explode(",", $barangPilih);
            // menghitung jumlah array
            $jumlah = count($barang_array);

            // jika tidak ada barang yang dipilih
            if($jumlah == 0){
                echo "Tidak ada barang yang dipilih<br>";
                $simpan = false;
                // jika ada barang yang dipilih
            }else{
                foreach($barang_array as $idbarang){
                    // jika id barangnya adlah 0 maka diteruskan saja atau dilompati ke array selanjutnya
                    if($idbarang == 0){
                        continue;
                    }
                    $sql = "select * from barang where id = $idbarang ";
                    $hasil = mysqli_query($kon, $sql);
                    
                    if(!$hasil){
                        echo "Barang tidak ada<br>";
                        $simpan = false;
                        break;
                    }else{
                        $row = mysqli_fetch_array($hasil);
                        // mengurangi jumlah stok dengan 1 barang yang akan dibeli
                        $stok = $row['stok'] - 1;
                        if($stok < 0){
                            echo "Stok barang ".row['nama']." kosong<br>";
                            $simpan = false;
                            break;
                        }
                        $harga = $row['harga'];
                    }

                    // simpan ke tabel djual
                    $sql = "insert into djual (idhjual,idbarang,qty,harga) values ('$idhjual','$idbarang','$qty','$harga')";
                    $hasil = mysqli_query($kon, $sql);
                    if(!$hasil){
                        echo "Detail jual gagal simpan<br>";
                        $simpan = false();
                        break; 
                    }

                    // mengurangi stok barang
                    $sql = "update barang set stok = $stok where id = $idbarang ";
                    $hasil = mysqli_query($kon, $sql);
                    if(!$hasil){
                        echo "Update stok barang gagal<br>";
                        $simpan = false;
                        break;
                    }
                } // end foreach
            } // end ada barang dipilih

            if($simpan){
                // mysqqli_commit artinya simpan data yang sudah benar-benar disimpan di dalam tabel
                $komit = mysqli_commit($kon); 
                echo "Pembelian berhasil<br>"; //cek
            }else{
                // mysqli_rollback digunakan untuk membatalkan penyimpanan
                $rollback = mysqli_rollback($kon);
                echo "Pembelian gagal<br>";
            }
            setcookie('keranjang',$barangPilih,time()-3600);
            // mengirimkan nilai dari idhjual ke file
            header("Location: buktibeli.php?idhjual=$idhjual");
        } // end tidak ada error siap disimpan
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembelian</title>
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
 
        small{
            color: red;
        }
    </style>
</head>
<body>
    <div class="tengah">
        <h2>DATA PEMBELI BARANG</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <label>
                Nama:<br>
                <input type="text" name="namacust" value="<?php echo $namacust;?>"><br>
            </label>
        
            <?php if(!empty($namacustErr)) echo "<small>$namacustErr</small><br>"; ?>
            
            <label>
                <br>Email:<br>
                <input type="email" name="email" value="<?php echo $email;?>"><br>
            </label>
        
            <?php if(!empty($emailErr)) echo "<small>$emailErr</small><br>"; ?>
        
            <label>
                <br>No. Telepon:<br>
                <input type="text" name="notelp" value="<?php echo $notelp;?>"><br>
            </label>
        
            <?php if(!empty($notelpErr)) echo "<small>$notelpErr</small><br>"; ?>
            
            <br><button type="submit">Simpan</button>
        </form>
        <?php
            if(!empty($barangPilihErr)){
                echo $barangPilihErr;
            }
        ?>

        <?php
            // tampilkan keranjang belanja
            require_once 'barang.php';
            $sql = "select * from barang where id in (".$barangPilih.")order by id desc";
            echo $sql."<br>"; // cek
            echo "<hr>";
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