<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css"
    />
    <title>Tugas Maskapai</title>
  </head>
  <hr>
  <body class="text-bg-dark p-3">
  <div class="container" align="" >
  <form method="POST" action=""  id="form"></p>
  <label for="maskapai"></label>
  Maskapai : <input type='text' name='maskapai' id="maskapai" style="margin-left: 55px;"></p>
  <label for="awal"></label>
  Bandara Awal : <select name="awal" class="form-select-sm" id="awal" style="margin-left: 25px;">
  <option align="center">-- pilih --</option>";

    <?php

    $bandara1 = array(
      "Soekarno-Hatta (CGK)" => 50000,
      "Husein Sastranegara (BDO)" => 30000,
      "Abdul Rahman Saleh (MLG)" => 40000,
      "Juanda (SUB)" => 40000
    );

    $bandara_1 = array_keys($bandara1);//menjadikan sort pada key nya
    sort($bandara_1);
    
    for ($i = 0; $i < count($bandara_1); $i++){
         echo"<option> $bandara_1[$i] </option>";
        
    }
    echo "</select></p>";

    ?>
    <label for="tujuan"></label>
    Bandara Tujuan:<select name="tujuan" class="form-select-sm" id="tujuan" style="margin-left: 20px;">
    <option align="center">-- pilih --</option>";
    <?php

    $bandara2 = array(
      "Ngurah Rai (DPS)" => 80000,
      "Hasanuddin (UPG)" => 70000,
      "Inanwatan (INX)" => 90000,
      "Sultan Iskandarmuda (BTJ)" => 70000
    );

    $bandara_2 = array_keys($bandara2);
    sort($bandara_2);
    
    for ($i = 0; $i < count($bandara_2); $i++){
         echo"<option> $bandara_2[$i] </option>";
        
    }
    echo "</select></p>";

    ?>
 
  <label for="harga"></label>
  Harga Tiket: <input type='number' name='harga' id="harga"style="margin-left: 45px;"></p>

  <button type="submit" name="cek" value="cek" >cek</button>
    
    </form>
  </div>
    <hr>
    <div class="container">
    <table align="center" class="table table-striped table-dark table-hover">
    <h3>Hasil pengecekkan :</h3>

    <?php

      $berkas = "data(1).json"; //Variabel berisi nama berkas di mana data dibaca dan ditulis.
      $dataCustomer = array(); //Variabel array kosong untuk menampung data customer dari berkas.

      $dataJson = file_get_contents($berkas);
      $dataCustomer = json_decode($dataJson, true);//bentuknya menjadi array
      include 'koneksi.php';
		
      if (isset($_POST['cek']) && is_numeric($_POST['harga']) && !empty($_POST['maskapai']) && $_POST['awal'] != '-- pilih --' && $_POST['tujuan'] != '-- pilih --'  )//mengacu form submit
      {
        
        function Json($berkas,$dataCustomer,$dataBaru, $maskapai,$awal ,$tujuan,$harga ,$totalpajak, $totalbayar){
          global $dataCustomer;// agar dapat dipanggil di
          $dataBaru = array(
            $maskapai,
            $awal,
            $tujuan,
            $harga,
            $totalpajak,
            $totalbayar
          );
          array_push($dataCustomer,$dataBaru); //Menambahkan data baru ke dalam data yang sudah ada dalam berkas. 
  
          //Mengkonversi kembali data customer dari array PHP menjadi array Json dan menyimpannya ke dalam berkas.
          $dataJson = json_encode($dataCustomer, JSON_PRETTY_PRINT);
          file_put_contents($berkas, $dataJson);
        }



        function bandaraAwalAkhir($awal, $bandara1, $tujuan, $bandara2)//Menentukan bandara Awal dan Tujuan SERTA Value nya MASING MASING 
        {
        // agar dapat mengembalikan hasil fungsi untuk di jumlahkan di fungsi Jumlah()
        global $pajak1;
        global $pajak2;

        $subject = array_keys($bandara1);//menentukan array keys nya untuk array bandara 1
        for ($i = 0; count($subject); $i++){//Mengacu pada array key nya yaitu $subject adalah key key yang ada di bandara1 
          if ($awal == $subject[$i])//jika data dari form $awal akan dicocokan $subjek[$i] yaitu ARRAY KEY PADA INDEX NYA di array bandara1
          {
            $pajak1 = $bandara1[$subject[$i]];//Sampai disini program menginisiasi kan Pajak bandara awal dengan memanggil $bandara1[$subject[$i] yaitu VALUE NYA dari ARRAY BANDARA 1
            /*===================================================*///perbatasan pembeda
            $subject2 = array_keys($bandara2);//dilanjut perulangan seperti tadi, tetapi didalam perulangan bandara Awal / Bandara1
            for ($i = 0; count($subject2); $i++){//Pada perulangan ini di fokuskan pada baris 124-129 saja yaitu PEMBEDA ARRAY NYA yang sudah berganti di bandara 2 / Bandara Tujuan
              if ($tujuan == $subject2[$i]){
                $pajak2 = $bandara2[$subject2[$i]];
                return $pajak1 + $pajak2;// lalu sampai disini akhirnya di kembalikan 2 VALUE dari 2 ARRAY lalu hasilnya BISA DI JUMLAHKAN
              } 
            }
          }
        }
        
        }

        /*sebelum 2 fungsi menjadi satu 
        function bandaraAkhir($tujuan, $bandara2)
        {
        global $pajak2;// agar dapat mengembalikan hasil fungsi untuk di jumlahkan
        $subject2 = array_keys($bandara2);//menentukan array keys nya
        for ($i = 0; count($subject2); $i++){
          if ($tujuan == $subject2[$i]){//menentukan value dari array assosiative bandara2
            $pajak2 = $bandara2[$subject2[$i]];
            return $pajak2;
          } 
        }
         }
        dari 2 fungsi diatas yang menghasilkan global variabel di hitung dan di total di fungsi yang baru*/

        
        function Jumlah($harga,$pajak1,$pajak2,$koneksi)// Fungsi ini membat total pajak & bayar dan menerima variabel global yang sudah di kerjakan pada fungsi diatasnya
        {
          global $totalpajak;
          global $totalbayar;
          $totalpajak = $pajak1 + $pajak2;
          $totalbayar = $harga + $totalpajak;
          //aksi untuk menambahkan data ke database 
          mysqli_query($koneksi, "insert INTO penerbangan set
          maskapai = '$_POST[maskapai]',
          bandara_awal = '$_POST[awal]',
          bandara_tujuan = '$_POST[tujuan]', 
          pajak_total = '$totalpajak',
          harga = '$harga',
          dibayar = '$totalbayar'");
          echo 'data baru tersimpan di database';
        }

        


        // data dari form kesini dulu
        $maskapai = $_POST['maskapai'];
        $awal = $_POST['awal'];
        $tujuan = $_POST['tujuan'];
        $harga = $_POST['harga'];


      

        echo "<tr>
          <td>".$maskapai."</td>
          <td>".$awal."</td> 
          <td>".$tujuan."</td> 
          <td>".$harga."</td> 
          </tr>";
        bandaraAwalAkhir($awal, $bandara1, $tujuan, $bandara2);
        //bandaraAkhir($tujuan, $bandara2);
        Jumlah($harga,$pajak1,$pajak2,$koneksi);
        $dataBaru = 0;
        Json($berkas,$dataCustomer,$dataBaru, $maskapai,$awal ,$tujuan,$harga ,$totalpajak, $totalbayar);

      //sebelum menginput apa apa atau belum meng-input jumlah
      } else {
        echo "tombol belum di cek dan jumlah belum dipilih<br>";
      }
      
    ?>
    </table>
    </div>
    <hr>
    <div class="container">
    <h3>Daftar Rute Tersedia :<br></h3>
		<!-- Tabel untuk menampilkan data Customer. -->
		<table class="table table-striped table-dark table-hover">
			<tr  class="table-active">
				<!-- Header tabel data Customer. -->
				<th>Maskapai</th>
				<th>Asal Penerbangan</th>
				<th>Tujuan penerbangan</th>
				<th>pajak total</th>
				<th>Harga tiket</th>
				<th>Di bayar</th>
				<!-- <th>Total Harga Item</th> -->
			</tr>

  
<?php
  
    for ($i=0; $i < count($dataCustomer); $i++){
      
      //	$item adalah data berisi item dalam bentuk array berisikan item1, item2, dan item3.
      $maskapai = $dataCustomer[$i][0]; //index dari dataBaru
      $awal = $dataCustomer[$i][1]; // Contoh isi variabel: "089977641321".
      $tujuan = $dataCustomer[$i][2]; // Isi variabel: "L" atau "P".
      $totalpajak = $dataCustomer[$i][4]; // Contoh isi variabel: ["1000", "2000", "500"]
      $hargaTiket = $dataCustomer[$i][3]; // Contoh isi variabel: ["1000", "2000", "500"]
      $dibayar = $dataCustomer[$i][5]; // Contoh isi variabel: ["1000", "2000", "500"]
      
      //	Baris untuk menampilkan data customer.
      echo "<tr>
          <td>".$maskapai."</td> <!-- Data nama. -->
          <td >".$awal."</td> <!-- Data nomor hp. -->
          <td >".$tujuan."</td> <!-- Data jenis kelamin. -->
          <td >".$totalpajak."</td> <!-- Data item1. -->
          <td >".$hargaTiket."</td> <!-- Data item2. -->
          <td >".$dibayar."</td> <!-- Data item3. -->
          
        </tr>";
    }
?>
    </table>
    </div>
    <!-- Bootstrap JavaScript -->
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    </body>

</html