<?php  
  require_once __DIR__ . '/koneksitoko.php';
  
  function tambahMatakuliah($kodemk,$nama,$sks){
    $koneksi = koneksiToko();
    $sql="insert into matakuliah(kodemk,nama,sks) values ('$kodemk','$nama',$sks)";
    $hasil=mysqli_query($koneksi, $sql);

    if($hasil){
      return true; 
    }else{
      return false;;
    }
  }

  // membaca data dalam tabel matakuliah
  // hasilnya baca berupa return value
  // parameter: SQL select
  function bacaMatakuliah($sql){
    require_once "koneksitoko.php";
    $hasils = array();
    $koneksi = koneksiToko();
    
    if($result = mysqli_query($koneksi, $sql)){
      if(mysqli_num_rows($result) > 0){
        $i = 0;
        while($row = mysqli_fetch_array($result)){
          $hasils[$i]['id']=$row['id'];
          $hasils[$i]['kodemk']=$row['kodemk'];
          $hasils[$i]['nama']=$row['nama'];
          $hasils[$i]['sks']=$row['sks'];
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

  function hapusMatakulah($id){
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

  function updateMatakuliah($id, $kodemk, $nama, $sks){
    $koneksi = koneksiToko();
    $sql="update matakuliah set 
        kodemk = '$kodemk',
        nama = $nama,
        sks = $sks
        where id = $id";
    $hasil=mysqli_query($koneksi, $sql);
    mysqli_close($koneksi);

    if($hasil){
      return true; 
    }else{
      return false;;
    }
  }