<?php
session_start();
include 'koneksi.php';


//jika button simpan di klik
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //$_POST: form input name=''
    //$_GET: url ?param='nilai'
    //$_FILES: ngambil nilai dari input type file
    if (!empty($_FILES['foto']['name'])) {
        $nama_foto = $_FILES['foto']['name'];
        $ukuran_foto = $_FILES['foto']['size'];

        //kita bikin tipe foto: png, jpg, jpeg
        $ext = array('png', 'jpg', 'jpeg');
        $extFoto = pathinfo($nama_foto, PATHINFO_EXTENSION);

        //JIKA EXTENSI FOTO TIDAK EXT YANG TERDAFTAR DI ARRAY EXT
        if (!in_array($extFoto, $ext)) {
            echo "Maaf, foto tidak dapat diupload karena format tidak sesuai";
            die;
        } else {
            //pindahkan gambar dari tmp folder ke folder yg sudah kita buat
            move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $nama_foto);

            $insert = mysqli_query($koneksi, "INSERT INTO user (nama, email, password, foto) VALUES ('$nama','$email','$password','$nama_foto')");
        }
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO user (nama, email, password) VALUES ('$nama','$email','$password')");
    }


    header("location:user.php?tambah=berhasil");
}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM user WHERE id='$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

//jika button edit di klik
if (isset($_POST['edit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    //jika password di isi sama user
    if ($_POST['password']) {
        $password = $_POST['password'];
    } else {
        $password = $rowEdit['password'];
    }

    $update = mysqli_query($koneksi, "UPDATE user SET nama='$nama',email='$email', password='$password' WHERE id='$id'");
    header("location:user.php?ubah=berhasil");
}
?>
<!DOCTYPE html>


<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <?php include 'inc/head.php' ?>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <?php include 'inc/sidebar.php' ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php include 'inc/nav.php' ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header"><?php echo isset($_GET['edit']) ? 'Edit' : 'Tambah' ?> User</div>
                                    <div class="card-body">
                                        <?php if (isset($_GET['hapus'])): ?>
                                            <div class="alert alert-success" role="alert">
                                                Data berhasil dihapus
                                            </div>
                                        <?php endif ?>

                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-3 row">
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Nama</label>
                                                    <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Anda" required value="<?php echo isset($_GET['edit']) ? $rowEdit['nama'] : '' ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email" placeholder="Masukkan Email Anda" required value="<?php echo isset($_GET['edit']) ? $rowEdit['email'] : '' ?>">
                                                </div>

                                            </div>
                                            <div class="mb-3 row">
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Password</label>
                                                    <input type="password" class="form-control" name="password" placeholder="Masukkan Password Anda">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <div class="col-sm-12">
                                                    <label for="" class="form-label">Foto</label>
                                                    <input type="file" name="foto">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-primary" name="<?php echo isset($_GET['edit']) ? 'edit' : 'simpan' ?>" type="submit">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include 'inc/footer.php' ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <?php include 'inc/js.php' ?>


</body>

</html>