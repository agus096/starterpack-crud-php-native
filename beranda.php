<?php
include 'layout/top.php';

//menampilkan total nama barang
$d_barang = mysqli_query($kon, "SELECT * FROM barang");
$t_barang = mysqli_num_rows($d_barang);

//menampilkan total barang ready
$d_b_ready = mysqli_query($kon, "SELECT SUM(qty) as total_qty FROM barang");
$t_b_ready = mysqli_fetch_array($d_b_ready);

//menampilkan total barang keluar
$d_b_keluar = mysqli_query($kon, "SELECT SUM(qty) as total_qty FROM barang_keluar");
$t_b_keluar = mysqli_fetch_array($d_b_keluar);
?>

<div class="page-heading">
    <h3>Dashboard</h3>
</div>

<div class="page-content">
    <section class="row">

        <!-- start bagian utama -->
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">
                                        Barang
                                    </h6>
                                    <h6 class="font-extrabold mb-0"><?= $t_barang ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Barang Ready</h6>
                                    <h6 class="font-extrabold mb-0"><?= $t_b_ready['total_qty'] ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Barang keluar</h6>
                                    <h6 class="font-extrabold mb-0"><?= $t_b_keluar['total_qty']; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end bagian utama -->
    </section>
</div>


<?php include 'layout/bottom.php' ?>