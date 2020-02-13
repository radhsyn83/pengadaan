<?php
include_once "../connect.php";

if (isset($_GET["load_bahan"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT * FROM `bahan` WHERE `active` = 1";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Bahan Baku";
            while ($row = $result->fetch_array()) {
                $data[] = array("id" => $row[0], "nama" => $row[1], "date_add" => $row[2]);
            }

            $res["data"] = $data;
        } else {
            //Username password not match
            $res["error"] = 2;
            $res["msg"] = "Bahan baku belum tersedia.";
        }
        $result->close();
    }
    echo json_encode($res);

} else if (isset($_GET["load_chart"])) {

    $id_bahan = $_POST["bahan"];
    $tahun = $_POST["tahun"];

    $dayInArray = array();
    $estimasi = array();
    $produksi = array();

    $res["error"] = 0;
    $res["msg"] = "";

    //GET ESTIMASI
    $dataEstimasi = array();
    $sqlEstimasi = "SELECT p.id, p.jumlah, (case when (p.bulan > 5 and p.bulan < 11) THEN (p.bulan+1) ELSE p.bulan+1 END)
 as bulan, p.tahun FROM peramalan p";
    $sqlEstimasi .= " join peramalan_supplier ps on ps.id_peramalan = p.id";
    $sqlEstimasi .= " WHERE p.id_bahan = '$id_bahan' AND tahun = '$tahun'";
    $sqlEstimasi .= " group by p.id";
    if ($result = $mysql->query($sqlEstimasi)) {
        while ($row = $result->fetch_array()) {
            $dataEstimasi[] = array("jumlah" => $row["jumlah"], "bulan" => $row["bulan"]);
        }
    }

    //GET PRODUKSI
    $dataProduksi = array();
    $sqlProduksi = "SELECT bh.bahan_produksi as jumlah, DATE_FORMAT(bh.tanggal, '%c') as bulan FROM bahan_keluar bh";
    $sqlProduksi .= " WHERE bh.id_bahan = '$id_bahan' AND DATE_FORMAT(bh.tanggal, '%Y') = '$tahun'";
    if ($result = $mysql->query($sqlProduksi)) {
        while ($row = $result->fetch_array()) {
            $dataProduksi[] = array("jumlah" => $row["jumlah"], "bulan" => $row["bulan"]);
        }
    }

    $i = 1;
    while ($i <= 12) {
        $pr = null;
        foreach ($dataProduksi as $p) {
            $m = (int)$p["bulan"];
            if ($i == $m) {
                $pr = $p["jumlah"];
            }
        }
        if ($pr != null) {
            $produksi[] = $pr;
        } else {
            $produksi[] = 0;
        }
        $i++;
    }

    $y = 1;
    while ($y <= 12) {
        $es = null;
        foreach ($dataEstimasi as $e) {
            $m = (int)$e["bulan"];
            if ($y == $m) {
                $es = $e["jumlah"];
            }
        }
        if ($es != null) {
            $estimasi[] = $es;
        } else {
            $estimasi[] = 0;
        }
        $y++;
    }

    $data['estimasi'] = $estimasi;
    $data['produksi'] = $produksi;

    $res["error"] = 1;
    $res["msg"] = $mysql->error;
    $res["data"] = $data;
    echo json_encode($res);
}
