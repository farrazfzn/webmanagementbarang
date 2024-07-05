<?php
require 'function.php';

//Cek Login, terdaftar atau tidak
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    //cocokin dgn database ada atau tidak
    $cekdatabase = mysqli_query($conn, "SELECT * FROM login where email ='$email' and password='$password'");
    //hitung jumlah data
    $hitung = mysqli_num_rows($cekdatabase);

    if($hitung>0){
        $_SESSION['log'] = 'True';
        header('location:index.php');
    } else {
        header('location:login.php');
    };
};

if(!isset($_SESSION['log'])){

} else {
    header('location:index.php');
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style >
        .warnaregis {
  color: #fff; /* Warna teks putih */
  background-color: #ff9800; /* Warna latar belakang oranye */
  border-color: #ff9800; /* Warna border oranye */
  padding: 6px 12px; /* Padding tombol */
  font-size: 16px; /* Ukuran font */
  font-weight: bold; /* Tebalkan font */
  border-radius: 5px; /* Membuat sudut tombol melengkung */
  cursor: pointer; /* Tambahkan ikon kursor */
}

.warnaregis:hover {
  background-color: #f57c00; /* Warna latar belakang oranye yang sedikit lebih gelap saat dihover */
  border-color: #f57c00; /* Warna border oranye yang sedikit lebih gelap saat dihover */
}

    </style>

    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4" name="email" id="inputEmailAddress" type="email" placeholder="Enter email address" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Enter password" />
                                            </div>
                                            <div class="form-group d-flex justify-content-between mt-4 mb-0">
                                                <button class="btn btn-primary mr-2" name="login">Login</button>
                                                <button type="button" class="warnaregis" data-toggle="modal" data-target="#myModal">Daftar</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
    <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Tambah Admin</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <form method="post">
        <div class="modal-body">
          <input type="email" name="email" placeholder="Email" class="form-control" required>
          <br>
          <input type="password" name="password" placeholder="Password" class="form-control" required>
          <br>
          <button type="submit" class="btn btn-primary" name="addadmin">Submit</button>
        </div>
        </form>
        
      </div>
    </div>
  </div>
</html>
