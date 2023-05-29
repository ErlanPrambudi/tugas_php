<?php  
require_once __DIR__ . '/koneksitoko.php';
  
function tambahBarang($nama,$harga,$stok){
  $koneksi = koneksiToko();
  $sql="insert into barang(nama,harga,stok) values
      ('$nama',$harga,$stok)";
  $hasil=mysqli_query($koneksi, $sql);

  if($hasil){
    return true; 
  }else{
    return false;;
  }
}

// membaca data dalam tabel barang
// hasilnya baca berupa return value
// parameter: SQL select
function bacaBarang($sql){
  require_once "koneksitoko.php";
  $hasils = array();
  $koneksi = koneksiToko();
  if($result = mysqli_query($koneksi, $sql)){
    if(mysqli_num_rows($result) > 0){
      $i = 0;
      while($row = mysqli_fetch_array($result)){
        $hasils[$i]['id']=$row['id'];
        $hasils[$i]['nama']=$row['nama'];
        $hasils[$i]['harga']=$row['harga'];
        $hasils[$i]['stok']=$row['stok'];
        $hasils[$i]['foto']=$row['foto'];
        $i++;
      }
      mysqli_free_result($result);
    }
  } else{
    die ("ERROR: Tidak dapat menjalankan $sql:". mysqli_error($koneksi));
  }
 
  // Close connection
  mysqli_close($koneksi);
  return $hasils;
}

function hapusBarang($id){
  $koneksi = koneksiToko();
  $sql="delete from barang where id = $id";
  $hasil=mysqli_query($koneksi, $sql);
  mysqli_close($koneksi);

  if($hasil){
    return true; 
  }else{
    return false;;
  }
}

function updateBarang($id, $nama, $harga, $stok){
  $koneksi = koneksiToko();
  $sql="update barang set 
        nama = '$nama',
        harga = $harga,
        stok = $stok
        where id = $id";
  $hasil=mysqli_query($koneksi, $sql);
  mysqli_close($koneksi);

  if($hasil){
    return true; 
  }else{
    return false;;
  }
}