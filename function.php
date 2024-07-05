<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stockbarang");



// Menambah barang baru
if (isset($_POST['addnewbarang'])) {

    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    
    // Validasi apakah barang sudah ada atau belum
    $cek = mysqli_query($conn, "SELECT * FROM stock WHERE namabarang = '$namabarang'");
    $hitung = mysqli_num_rows($cek);

    if ($hitung < 1) {
        // Jika belum ada

        if (!empty($_FILES['file']['name'])) {
            // Soal Gambar
            $allowed_extenstion = array('png', 'jpg');
            $nama = $_FILES['file']['name']; // Mengambil nama file
            $dot = explode('.', $nama);
            $ekstensi = strtolower(end($dot)); // Mengambil ekstensi
            $ukuran = $_FILES['file']['size']; // Mengambil ukuran file
            $file_tmp = $_FILES['file']['tmp_name']; // Mengambil lokasi file

            // Penamaan file --> enkripsi
            $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; // Menggabungkan nama file yang dienkripsi dengan ekstensinya

            // Proses upload gambar
            if (in_array($ekstensi, $allowed_extenstion) === true) {
                // Validasi ukuran file
                if ($ukuran < 2500000) {
                    move_uploaded_file($file_tmp, 'images/' . $image);

                    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock, image) VALUES ('$namabarang', '$deskripsi', '$stock', '$image')");
                    if ($addtotable) {
                        header('Location: index.php');
                        exit();
                    } else {
                        echo 'Gagal';
                        header('Location: index.php');
                        exit();
                    }
                } else {
                    // Jika file lebih dari 2.5MB
                    echo '
                    <script>
                    alert("Ukuran Terlalu Besar");
                    window.location.href="index.php";
                    </script>
                    ';
                }
            } else {
                // Jika file tidak berformat jpg atau png
                echo '
                <script>
                alert("File harus png/jpg");
                window.location.href="index.php";
                </script>
                ';
            }
        } else {
            // Jika tidak ada gambar yang diunggah
            $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) VALUES ('$namabarang', '$deskripsi', '$stock')");
            if ($addtotable) {
                header('Location: index.php');
                exit();
            } else {
                echo 'Gagal';
                header('Location: index.php');
                exit();
            }
        }
    } else {
        // Jika barang sudah ada
        echo '
        <script>
        alert("Nama barang sudah terdaftar");
        window.location.href="index.php";
        </script>
        ';
    }
}



//Menambah Barang Masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang = '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya' ");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//Menambah Barang Keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang = '$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    if($stocksekarang >= $qty){
        //Kalau Barang Cukup
        $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;

        $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty) values('$barangnya','$penerima','$qty')");
        $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya' ");
        if($addtokeluar&&$updatestockmasuk){
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        //Kalau Barang Tidak Cukup
        echo '
        <script>
            alert("Stock saat ini tidak cukup");
            window.location.href="keluar.php";
        </script>
        ';
    }
}


//Update Info Barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    
        //soal Gambar
        $allowed_extenstion = array('png','jpg');
        $nama = $_FILES['file']['name']; //ngambil nama barang
        $dot = explode('.', $nama);
        $ekstensi = strtolower(end($dot)); //ngambil ekstensi
        $ukuran = $_FILES['file']['size']; // ngambil size filenya
        $file_tmp = $_FILES['file']['tmp_name']; //ngambil lokasi filenya
    
        //penamaan file--> enkripsi
        $image = md5(uniqid($nama, true) . time()).'.'.$ekstensi; //menggabungkan nama file yang dienkripsi dgn ekstensinya
            if($ukuran==0){
                //jika tidak ingin upload
                $update = mysqli_query($conn,"update stock set namabarang ='$namabarang', deskripsi='$deskripsi' where idbarang ='$idb'");
                if($update){
                    header('location:index.php');
                } else {
                    echo 'Gagal';
                    header('location:index.php');
                }
            } else {
                //jika ingin
                move_uploaded_file($file_tmp, 'images/'.$image);
                $update = mysqli_query($conn,"update stock set namabarang ='$namabarang', deskripsi='$deskripsi', image='$image' where idbarang ='$idb'");
                if($update){
                    header('location:index.php');
                } else {
                    echo 'Gagal';
                    header('location:index.php');
                }
            }
}

//Menghapus Barang dari Stok
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb']; //idbarang

    $gambar = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $get = mysqli_fetch_array($gambar);
    $img = 'images/'.$get['image'];
    unlink($img);

    $hapus = mysqli_query($conn,"delete from stock where idbarang ='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}


//Mengubah Data Barang Masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg= $stocknya['stock'];

    $qtyskrg = mysqli_query($conn,"select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn,"update stock set stock = '$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
            if($kurangistocknya&&$updatenya){
                header('location:masuk.php');
                } else {
                    echo 'Gagal';
                    header('location:masuk.php');
                        }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn,"update stock set stock = '$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
            if($kurangistocknya&&$updatenya){
                header('location:masuk.php');
                } else {
                    echo 'Gagal';
                    header('location:masuk.php');
                        }

    }
}

//Menghapus Barang Masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $qty = $_POST['kty'];

    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok-$qty;


    $update= mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where idmasuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
                } else {
                    echo 'Gagal';
                    header('location:masuk.php');
    }
}

//Mengubah Data Barang Keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg= $stocknya['stock'];

    $qtyskrg = mysqli_query($conn,"select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg - $selisih;
        
        $kurangistocknya = mysqli_query($conn,"update stock set stock = '$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
            if($kurangistocknya&&$updatenya){
                header('location:keluar.php');
                } else {
                    echo 'Gagal';
                    header('location:keluar.php');
                        }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn,"update stock set stock = '$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
            if($kurangistocknya&&$updatenya){
                header('location:keluar.php');
                } else {
                    echo 'Gagal';
                    header('location:keluar.php');
                        }

    }
}

//Menghapus Barang Keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $qty = $_POST['kty'];

    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok+$qty;


    $update= mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from keluar where idkeluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
                } else {
                    echo 'Gagal';
                    header('location:keluar.php');
    }
}

//Menambah Admin Baru
if(isset($_POST['addadmin'])){
 $email= $_POST['email'];   
 $password= $_POST['password'];   

 $queryinsert = mysqli_query($conn,"insert into login (email, password) values ('$email','$password')");

 if($queryinsert){
    //if berhasil
    header('location:admin.php');
 } else {   
    //kalau gagal
    header('location:admin.php');
}
}



//edit data admin
if(isset($_POST['updateadmin'])){
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru= $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn,"update login set email='$emailbaru', password='$passwordbaru' where iduser='$idnya'");

 if($queryupdate){
    //if berhasil
    header('location:admin.php');
 } else {   
    //kalau gagal
    header('location:admin.php');
}
}

//Hapus data admin
if(isset($_POST['hapusadmin'])){
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn,"delete from login where iduser='$id'");
    if($querydelete){
        //if berhasil
        header('location:admin.php');
     } else {   
        //kalau gagal
        header('location:admin.php');
    }
    }

    

?>