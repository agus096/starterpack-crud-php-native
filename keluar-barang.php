<?php include 'layout/top.php' ?>

<?php
//proses add
if (isset($_POST['add'])) {

    #buat id barang
    // Daftar karakter yang akan digunakan untuk membuat kode acak
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    // Mengacak karakter-karakter dari daftar karakter
    $randomCode = '';
    for ($i = 0; $i < 8; $i++) {
    $randomCode .= $characters[rand(0, strlen($characters) - 1)];
    }

    $tanggal     = htmlspecialchars($_POST['tanggal']);
    $id_rec      = pilter($kon,$randomCode);
    $nama_barang = pilter($kon, $_POST['nama_barang']);
    $qty         = pilter($kon, $_POST['qty']);
    $tujuan      = pilter($kon,$_POST['tujuan']);
    $note        = pilter($kon, $_POST['note']);

    //ambil data sesuai nama barang dari tabel barang
    $d_barang = mysqli_query($kon,"SELECT * FROM barang WHERE nama_barang = '$nama_barang' ");
    $i_barang = mysqli_fetch_array($d_barang);

    // Menyiapkan statement untuk melakukan insert data ke tabel barang_keluar
    $sql = "INSERT INTO barang_keluar (tanggal, id_rec, idb, nama_barang, sku, qty, tujuan, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $kon->prepare($sql);

    // Mengeksekusi statement untuk menambahkan data ke tabel
    $data = array($tanggal, $id_rec, $i_barang['idb'], $nama_barang, $i_barang['sku'], $qty, $tujuan, $note);
    if ($stmt->execute($data)) {
        $_SESSION['add_pengeluaran_success'] = 'yes';
    } else {
        echo "Terjadi kesalahan saat menambahkan data ke tabel.";
    }

    // Menyiapkan prepared statement untuk update stok di tabel barang
    $stmt2 = $kon->prepare("UPDATE barang SET qty = qty - ? WHERE idb = ?");

    // Mengikat variabel parameter ke prepared statement
    $stmt2->bind_param("ss", $qty, $i_barang['idb']);

    // Mengeksekusi prepared statement
    $stmt2->execute();


}

  //proses update
  if(isset($_POST['update'])) {
    $idb         = pilter($kon, $_POST['idb']);
    $tujuan      = pilter($kon, $_POST['tujuan']);
    $note        = pilter($kon, $_POST['note']);

    // Menyiapkan prepared statement
    $stmt = $kon->prepare("UPDATE barang_keluar SET tujuan = ?, note = ? WHERE idb = ?");

    // Mengikat variabel parameter ke prepared statement
    $stmt->bind_param("sss", $tujuan, $note, $idb);

    // Mengeksekusi prepared statement
    $stmt->execute();
  }


//proses hapus barang
if (isset($_POST['hapus'])) {
    $id_rec = pilter($kon,$_POST['id_rec']);
    $idb    = pilter($kon,$_POST['idb']);
    $qty    = pilter($kon,$_POST['qty']);
    $kode   = pilter($kon,$_POST['kodeakseshapus']);
    #cek dulu kodeakseshapus
    $cek_kode = mysqli_query($kon,"SELECT * from kodeakseshapus where kodeakses = '$kode' ");
    if(mysqli_num_rows($cek_kode) > 0) {
        
        // Menyiapkan prepared statement
        $stmt = $kon->prepare("DELETE FROM barang_keluar WHERE id_rec = ?");

        // Mengikat variabel parameter ke prepared statement
        $stmt->bind_param("s", $id_rec);

        // Mengeksekusi prepared statement
        $stmt->execute();

        // Menyiapkan prepared statement
        $stmt2 = $kon->prepare("UPDATE barang SET qty = qty + ? WHERE idb = ?");

        // Mengikat variabel parameter ke prepared statement
        $stmt2->bind_param("ss", $qty, $idb);

        // Mengeksekusi prepared statement
        $stmt2->execute();
    }
}

?>

<div class="page-heading">
    <h3>Keluar barang</h3>
</div>


<div class="page-content">
    <section class="row">

        <div class="col lg-12">
            <div class="row">

                <div class="alert alert-success">
                    <i class="bi-info-circle"></i> Menu ini untuk melihat data keluar barang sekaligus mencatat pengeluaran barang
                </div>
                <div class="alert alert-success">
                    <i class="bi-info-circle"></i> Stock barang auto berkurang ketika anda menginput & stock akan kembali jika anda menghapus data
                </div>

                <div class="card card-body">

                    <button type="button" class="btn btn-outline-primary block mb-3" data-bs-toggle="modal" data-bs-target="#add">
                        Pengeluaran baru
                    </button>

                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama barang</th>
                                <th>QTY</th>
                                <th>Sku</th>
                                <th>tujuan</th>
                                <th>Note</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $barang = mysqli_query($kon, "SELECT * FROM barang_keluar ORDER BY id DESC ");
                            while ($data = mysqli_fetch_array($barang)) {
                            ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $data['nama_barang'] ?></td>
                                    <td><?= $data['qty'] ?></td>
                                    <td><?= $data['sku'] ?></td>
                                    <td><?= $data['tujuan'] ?></td>
                                    <td><?= $data['note'] ?></td>
                                    <td>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#update<?= $data['sku'] ?>">Update</button>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?= $data['sku'] ?>">Hapus</button>
                                    </td>
                                </tr>

                                <!--start HAPUS Modal -->
                                <div class="modal fade text-left" id="hapus<?= $data['sku'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel4">
                                                    Hapus data <?= $data['nama_barang'] ?> ?
                                                </h4>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <span>Stock akan di kembalikan ke barang <?= $data['nama_barang'] ?></span>
                                                <br>
                                                <br>
                                                <form action="" method="POST">
                                                    <input type="hidden" class="form-control" value="<?= $data['id_rec'] ?>" name="id_rec" required/>
                                                    <input type="hidden" class="form-control" value="<?= $data['idb'] ?>" name="idb" required/>
                                                    <input type="hidden" class="form-control" value="<?= $data['qty'] ?>" name="qty" required/>
                                                    <input type="text" class="form-control mb-3"  name="kodeakseshapus" placeholder="Kode akses"/>
                                                    <button class="btn btn-danger btn-block" name="hapus">Ya hapus!</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end hapus Modal -->

                                <!--start update Modal -->
                                <div class="modal fade text-left" id="update<?= $data['sku'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel4">
                                                    Update data <?= $data['nama_barang'] ?>
                                                </h4>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                <form action="" class="form form-vertical" method="POST">
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                            <input type="hidden" class="form-control" value="<?= $data['idb'] ?>" name="idb"/>
                                                                <div class="form-group has-icon-left">
                                                                    <label for="first-name-icon">Nama barang</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control" value="<?= $data['nama_barang'] ?>" name="nama_barang" disabled />
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-box"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="mobile-id-icon">Tujuan</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control" placeholder="Masukan Tujuan" value="<?= $data['tujuan'] ?>" name="tujuan"  />
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-card-checklist"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="mobile-id-icon">Note</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control" placeholder="Masukan note" value="<?= $data['note'] ?>" name="note" />
                                                                        <div class="form-control-icon">
                                                                            <i class="bi bi-card-checklist"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="submit" name="update" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Update</span>
                                                </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end update Modal -->
                            <?php $no++; } ?>
                            
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <!--start ADD  Modal -->
        <div class="modal fade text-left" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel4">
                            Tambah pengeluaran
                        </h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                        <form action="" class="form form-vertical" method="POST">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group has-icon-left">
                                            <label for="first-name-icon">Tanggal</label>
                                            <div class="position-relative">
                                               <input type="text" class="form-control" value="<?= date("d-M-Y") ?>"  name="tanggal" readonly>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-card-checklist"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group has-icon-left">
                                            <label for="first-name-icon">Nama barang</label>
                                            <div class="position-relative">
                                                <select class="select2" name="nama_barang">
                                                    <?php 
                                                     $d_barang = mysqli_query($kon,"SELECT * FROM barang");
                                                     while($l_barang = mysqli_fetch_array($d_barang))  {
                                                    ?>
                                                    <option value="<?= $l_barang['nama_barang'] ?>"><?= $l_barang['nama_barang'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group has-icon-left">
                                            <label for="email-id-icon">Qty</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" placeholder="Masukan Qty" name="qty" />
                                                <div class="form-control-icon">
                                                    <i class="bi bi-card-checklist"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group has-icon-left">
                                            <label for="mobile-id-icon">Tujuan</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" placeholder="Masukan tujuan" name="tujuan" />
                                                <div class="form-control-icon">
                                                    <i class="bi bi-card-checklist"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group has-icon-left">
                                            <label for="mobile-id-icon">Note</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" placeholder="Masukan note" name="note" />
                                                <div class="form-control-icon">
                                                    <i class="bi bi-card-checklist"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" name="add" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Tambah pengeluaran barang</span>
                        </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end ADD Modal -->

        <?php
            if (isset($_SESSION['add_pengeluaran_success'])) {
        ?>
            <script>
                swal("Berhasil!", "Pengeluaran berhasil di catat", "success");
            </script>
        <?php
          } 
        unset($_SESSION['add_pengeluaran_success'])
        ?>

    </section>
</div>

<?php include 'layout/bottom.php' ?>