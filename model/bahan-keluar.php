<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

    $res["error"] = 0;
    $res["msg"] = "";
    $filter = $_GET['filter'];

    $sql = "SELECT a.*, b.nama ";
    $sql .= "FROM bahan_keluar a ";
    $sql .= "JOIN bahan b on a.id_bahan = b.id ";
    $sql .= "WHERE a.active = 1 AND DATE_FORMAT(`a`.`tanggal`, '%m/%Y') = '$filter'";

//
//    if (isset($_GET['filter'])) {
//        $filter = $_GET['filter'];
//        $sql .= "WHERE DATE_FORMAT(`a`.`tanggal`, '%m/%Y') = '$filter'";
//    }

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Bahan Keluar";
            while ($row = $result->fetch_array()) {
                $grandPrice = ((int) $row[10]) * ((int) $row[11]);
                $stok = (int)$row[7] - ((int) $row[2] + (int) $row[3]);
                $data[] = array(
                    "id" => $row[0],
                    "id_bahan" => $row[1],
                    "bahan_produksi" => $row[2],
                    "bahan_rusak" => $row[3],
                    "date_add" => $row[4],
                    "tanggal" => $row[5],
                    "bahan" => $row[8],
                    "stok" => $row[6],
                );
            }

            $res["data"] = $data;
        } else {
            //Username password not match
            $res["error"] = 2;
            $res["msg"] = "Data belum tersedia.";
        }
        $result->close();
    }
    echo json_encode($res);

} else if (isset($_GET["add-update"])) {

    $id = $_POST["s_id"];
    $id_bahan = $_POST["s_bahan"];
    $bahan_produksi = $_POST["s_jumlah_produksi"];
    $bahan_rusak = $_POST["s_jumlah_rusak"];
    $tanggal = $_POST["s_tanggal"];
    $stok = 0;

    $tanggalFormat = explode("-", $tanggal);
    $tanggalFormat = $tanggalFormat[1]."/".$tanggalFormat[0];


    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "";

    if ($id == "") {
        //GET LAST STOCK
        $sqlStok = "SELECT stok, (SELECT IFNULL(sum((aa.jumlah_masuk - aa.jumlah_retur)), 0) as jumlah_masuk FROM bahan_masuk aa JOIN supplier_bahan bb on aa.id_supplier_bahan = bb.id WHERE bb.id_bahan = '$id_bahan' AND DATE_FORMAT(`aa`.`tanggal_masuk`, '%m/%Y') = '$tanggalFormat' AND aa.active = 1) as bahan_masuk FROM bahan_keluar WHERE id_bahan = '$id_bahan' AND active = 1 ORDER BY id DESC LIMIT 1;";

        $lastStok = 0;
        if ($mysql->query($sqlStok)->fetch_row()[0] != null) {
            $lastStok = $mysql->query($sqlStok)->fetch_row()[0];
        }
        $bahanMasuk = 0;
        if ($mysql->query($sqlStok)->fetch_row()[1] != null) {
            $bahanMasuk = $mysql->query($sqlStok)->fetch_row()[1];
        }
        $stok = $lastStok + ((int) $bahanMasuk - ((int) $bahan_produksi + (int) $bahan_rusak));

        $sql = "INSERT INTO `bahan_keluar` (`id_bahan`, `bahan_produksi`, `bahan_rusak`, `tanggal`, `stok`) ";
        $sql .= "VALUES ('$id_bahan','$bahan_produksi','$bahan_rusak','$tanggal', '$stok')";

        $res["msg"] = "Bahan keluar berhasil ditambahkan.";

    } else {
        $sql = "UPDATE `bahan_keluar` SET `id_bahan`='$id_bahan', `bahan_produksi`='$bahan_produksi', `bahan_rusak`='$bahan_rusak', `tanggal`='$tanggal' WHERE `id` = '$id'";
        $res["msg"] = "Bahan keluar  berhasil diubah.";
    }

//    print_r($sql);die;
    if ($result = $mysql->query($sql)) {

    } else {
        $res["error"] = 1;
        $res["msg"] = $mysql->error;
    }
    echo json_encode($res);

} else if (isset($_GET["hapus"])) {

    $id = $_POST["id"];

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "UPDATE `bahan_keluar` SET `active` = 0 WHERE `id` = '$id'";
    $res["msg"] = "Bahan masuk berhasil dihapus.";

    if ($result = $mysql->query($sql)) {
    } else {
        $res["error"] = 1;
        $res["msg"] = $mysql->error;
    }
    echo json_encode($res);

}
