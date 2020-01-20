<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

    $res["error"] = 0;
    $res["msg"] = "";
    $filter = $_GET['filter'];

    $sql = "SELECT a.*, b.nama, (SELECT (aa.jumlah_masuk - aa.jumlah_retur) as bahan_masuk FROM bahan_masuk aa JOIN supplier_bahan bb on aa.id_supplier_bahan = bb.id WHERE bb.id_bahan = 3 AND DATE_FORMAT(`aa`.`tanggal_masuk`, '%m/%Y') = '$filter') as bahan_masuk ";
    $sql .= "FROM bahan_keluar a ";
    $sql .= "JOIN bahan b on a.id_bahan = b.id ";
    $sql .= "WHERE DATE_FORMAT(`a`.`tanggal`, '%m/%Y') = '$filter'";

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
                    "bahan" => $row[6],
                    "stok" => $stok,
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

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "";

    if ($id == "") {
        $sql = "INSERT INTO `bahan_keluar` (`id_bahan`, `bahan_produksi`, `bahan_rusak`, `tanggal`) ";
        $sql .= "VALUES ('$id_bahan','$bahan_produksi','$bahan_rusak','$tanggal')";

        $res["msg"] = "Bahan keluar berhasil ditambahkan.";

    } else {
        $sql = "UPDATE `bahan_keluar` SET `id_bahan`='$id_bahan', `bahan_produksi`='$bahan_produksi', `bahan_rusak`='$bahan_rusak', `tanggal`='$tanggal' WHERE `id` = '$id'";
        $res["msg"] = "Bahan keluar  berhasil diubah.";
    }

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
