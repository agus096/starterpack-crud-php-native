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

    $idb         = pilter($kon,$randomCode) ;
    $nama_barang = pilter($kon, $_POST['nama_barang']);
    $qty         = pilter($kon, $_POST['qty']);
    $sku         = pilter($kon, $_POST['sku']);
    $note        = pilter($kon, $_POST['note']);

    // Menyiapkan statement untuk melakukan insert data
    $sql = "INSERT INTO barang (idb, nama_barang, qty, sku, note) VALUES (?, ?, ?, ?, ?)";
    $stmt = $kon->prepare($sql);

    // Mengeksekusi statement untuk menambahkan data ke tabel
    $data = array($idb, $nama_barang, $qty, $sku, $note);
    if ($stmt->execute($data)) {
        $_SESSION['addsuccess'] = 'yes';
    } else {
        echo "Terjadi kesalahan saat menambahkan data ke tabel.";
    }
}

  //proses update
  if(isset($_POST['update'])) {
    $idb         = pilter($kon, $_POST['idb']);
    $qty         = pilter($kon, $_POST['qty']);
    $note        = pilter($kon, $_POST['note']);

    // Menyiapkan prepared statement
    $stmt = $kon->prepare("UPDATE barang SET qty = qty + ?, note = ? WHERE idb = ?");

    // Mengikat variabel parameter ke prepared statement
    $stmt->bind_param("sss", $qty, $note, $idb);

    // Mengeksekusi prepared statement
    $stmt->execute();
  }


//proses hapus barang
if (isset($_POST['hapus'])) {
    $idb =  $_POST['idb'];
    $kode = pilter($kon,$_POST['kodeakseshapus']);
    #cek dulu kodeakseshapus
    $cek_kode = mysqli_query($kon,"SELECT * from kodeakseshapus where kodeakses = '$kode' ");
    if(mysqli_num_rows($cek_kode) > 0) {
        
        // Menyiapkan prepared statement
        $stmt = $kon->prepare("DELETE FROM barang WHERE idb = ?");

        // Mengikat variabel parameter ke prepared statement
        $stmt->bind_param("s", $idb);

        // Mengeksekusi prepared statement
        $stmt->execute();

    }
}

?>

<div class="page-heading">
    <h3>Data barang</h3>
</div>


<div class="page-content">
    <section class="row">

        <div class="col lg-12">
            <div class="row">

                <div class="alert alert-success">
                    <i class="bi-info-circle"></i> Menu ini untuk melihat data barang sekaligus menambah quantity nya
                </div>
                <div class="alert alert-success">
                    <i class="bi-info-circle"></i> jika sebuah barang berkurang karena proses jual atau semacam nya quantity barang auto berkurang
                </div>

                <div class="card card-body">

                    <button type="button" class="btn btn-outline-primary block mb-3" data-bs-toggle="modal" data-bs-target="#add">
                        Tambah data baru
                    </button>

                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama barang</th>
                                <th>QTY</th>
                                <th>SKU</th>
                                <th>Note</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $barang = mysqli_query($kon, "SELECT * FROM barang ORDER BY id DESC");
                            while ($data = mysqli_fetch_array($barang)) {
                            ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $data['nama_barang'] ?></td>
                                    <td><?= $data['qty'] ?></td>
                                    <td><?= $data['sku'] ?></td>
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
                                                <span>Semua data tentang barang ini akan hilang</span>
                                                <br>
                                                <br>
                                                <form action="" method="POST">
                                                    <input type="hidden" class="form-control" value="<?= $data['idb'] ?>" name="idb" required/>
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
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group has-icon-left">
                                                                            <label for="email-id-icon">Qty sekarang</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" class="form-control" value="<?= $data['qty'] ?>" disabled />
                                                                                <div class="form-control-icon">
                                                                                    <i class="bi bi-card-checklist"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group has-icon-left">
                                                                            <label for="email-id-icon">Qty tambahan</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" class="form-control" placeholder="Masukan Qty" name="qty" />
                                                                                <div class="form-control-icon">
                                                                                    <i class="bi bi-card-checklist"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="form-group has-icon-left">
                                                                    <label for="mobile-id-icon">SKU</label>
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control" placeholder="Masukan SKU /kode" value="<?= $data['sku'] ?>" disabled/>
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
                            Tambah data
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
                                            <label for="first-name-icon">Nama barang</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" placeholder="Masukan nama barang" name="nama_barang" />
                                                <div class="form-control-icon">
                                                    <i class="bi bi-box"></i>
                                                </div>
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
                                            <label for="mobile-id-icon">SKU</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" placeholder="Masukan SKU /kode" name="sku" />
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
                            <span class="d-none d-sm-block">Tambah</span>
                        </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end ADD Modal -->

        <?php
            if (isset($_SESSION['addsuccess'])) {
        ?>
            <script>
                swal("Berhasil!", "Data berhasil di tambah", "success");
            </script>
        <?php
          } 
        unset($_SESSION['addsuccess'])
        ?>

    </section>
</div>


<?php include 'layout/bottom.php' ?>