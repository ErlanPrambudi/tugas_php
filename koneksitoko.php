
<?php
function koneksiToko(){
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "toko";

  // menciptakan koneksi
  $koneksi = mysqli_connect($servername, $username, $password, $dbname);

  // Cek koneksi
  if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_error());
  }
  return $koneksi;
}
