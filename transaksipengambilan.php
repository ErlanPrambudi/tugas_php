<?php
    $matakuliahPilih = 0;
    if(isset($_COOKIE['KRS'])){
        $matakuliahPilih = $_COOKIE['KRS'];
    }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $matakuliahPilih = str_replace((",".$id),"",$matakuliahPilih);
        setcookie('KRS', $matakuliahPilih, time()+3600);
    }

    $nimErr = "";
    $namaErr = "";
    $prodipErr = "";
    $matakuliahPilihErr = "";
    $nim = "";
    $nama = "";
    $prodi = "";
    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

    // Jika diklik tombol Simpan
    if ($request_method === 'POST'){
        $nim = htmlspecialchars($_POST['nim']);
        if(empty($nim)){
            $nimErr = "NIM belum diisi";
        }
        $nama = htmlspecialchars($_POST['nama']);
        if(empty($nama)){
            $namaErr = "Nama belum diisi";
        }
        
        $prodi = htmlspecialchars($_POST['prodi']);
        if(empty($prodi)){
            $prodiErr = "Prodi belum diisi";
        }
        $tanggal = date("Y-m-d");
 
        if(!isset($_COOKIE['KRS'])){
            $matakuliahPilihErr = "<br><small>Pengambilan KRS kosong</small><br>"; 
        }
 
        // jika tidak ada error data siap disimpan 
        if(empty($nimErr) && empty($namaErr) && empty($prodiErr) && empty($matakuliahPilihErr)){
            $qty = 1;
            $simpan = true;
            require_once "koneksitoko.php";
            $kon = koneksiToko();
 
            // memulai transaksi menyimpan ke database
            $mulaiTransaksi = mysqli_begin_transaction($kon);
            $sql = "insert into krs (nim, nama, prodi) value ('$nim','$nama','$prodi')";
            $hasil = mysqli_query($kon, $sql);
            
            // jika tidak berhasil disimpan
            if(!$hasil){
                echo "Data mahasiswa gagal disimpan <br>";
                $simpan = false;
            }

            // mendapatkan id dr query terakhir
            // mengambil id yang terakhir
            $idkrs = mysqli_insert_id($kon);
            
            // jika idkrs nya adalah 0 atau tidak ada nilai maka akan djalankan
            if($idkrs == 0){
                echo "Data mahasiswa tidak ada <br>";
                $simpan = false;
            }
 
            // mengkonversi string separator ',' $matakuliahPilih menjadi array
            // explode digunaan untuk mengkonversi nilai string menjadi array
            $matakuliah_array = explode(",", $matakuliahPilih);
            // menghitung jumlah array
            $jumlah = count($matakuliah_array);

            // jika tidak ada matakuliah yang dipilih
            if($jumlah == 0){
                echo "Tidak ada matakuliah yang dipilih<br>";
                $simpan = false;
                // jika ada matakuliah yang dipilih
            }else{
                foreach($matakuliah_array as $idmatakuliah){
                    // jika id matakuliahnya adlah 0 maka diteruskan saja atau dilompati ke array selanjutnya
                    if($idmatakuliah == 0){
                        continue;
                    }
                    $sql = "select * from matakuliah where id = $idmatakuliah ";
                    $hasil = mysqli_query($kon, $sql);
                    
                    if(!$hasil){
                        echo "Matakuliah tidak ada<br>";
                        $simpan = false;
                        break;
                    }else{
                        $row = mysqli_fetch_array($hasil);
                        // mengurangi jumlah stok dengan 1 barang yang akan dibeli
                        /*$stok = $row['stok'] - 1;
                        if($stok < 0){
                            echo "Stok barang ".row['nama']." kosong<br>";
                            $simpan = false;
                            break;
                        }*/
                        $sks = $row['sks'];
                    }

                    // simpan ke tabel dkrs
                    $sql = "insert into dkrs (idkrs, idmk) values ('$idkrs','$idmatakuliah')";
                    $hasil = mysqli_query($kon, $sql);
                    if(!$hasil){
                        echo "Detail jual gagal simpan<br>";
                        $simpan = false();
                        break; 
                    }

                    /*// mengupdate stok matakuliah
                    $sql = "update matakuliah set stok = $stok where id = $idmk ";
                    $hasil = mysqli_query($kon, $sql);
                    if(!$hasil){
                        echo "Update stok barang gagal<br>";
                        $simpan = false;
                        break;
                    }*/
                } // end foreach
            } // end ada matakuliah dipilih

            if($simpan){
                // mysqqli_commit artinya simpan data yang sudah benar-benar disimpan di dalam tabel
                $komit = mysqli_commit($kon); 
                echo "Pengambilan berhasil<br>"; //cek
            }else{
                // mysqli_rollback digunakan untuk membatalkan penyimpanan
                $rollback = mysqli_rollback($kon);
                echo "Pengambilan gagal<br>";
            }
            setcookie('KRS',$matakuliahPilih,time()-3600);
            // mengirimkan nilai dari idhjual ke file
            header("Location: buktitransaksi.php?idkrs=$idkrs");
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
        <h2>DATA PENGAMBIL KRS</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <label>
                NIM :<br>
                <input type="text" name="nim" value="<?php echo $nim;?>"><br>
            </label>
        
            <?php if(!empty($nimErr)) echo "<small>$nimErr</small><br>"; ?>
            
            <label>
                <br>Nama :<br>
                <input type="text" name="nama" value="<?php echo $nama;?>"><br>
            </label>
        
            <?php if(!empty($namaErr)) echo "<small>$namaErr</small><br>"; ?>
        
            <label>
                <br>Prodi :<br>
                <input type="text" name="prodi" value="<?php echo $prodi;?>"><br>
            </label>
        
            <?php if(!empty($prodiErr)) echo "<small>$prodiErr</small><br>"; ?>
            
            <br><button type="submit">Simpan</button>
        </form>
        <?php
            if(!empty($matakuliahPilihErr)){
                echo $matakuliahPilihErr;
            }
        ?>

        <?php
            // tampilkan keranjang belanja
            require_once 'matakuliah.php';
            $sql = "select * from matakuliah where id in (".$matakuliahPilih.")order by id asc";
            echo $sql."<br>"; // cek
            echo "<hr>";
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