<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT a.*, b.nama as bahan, c.nama as supplier FROM `bahan_masuk` a ";
    $sql .= "LEFT JOIN `bahan` b ON a.id_bahan = b.id ";
    $sql .= "LEFT JOIN `supplier` c ON a.id_supplier = c.id ";
    $sql .= "WHERE `a`.`active` = 1 ";

    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
        $sql .= "AND DATE_FORMAT(`a`.`tanggal_masuk`, '%m/%Y') = '$filter'";
    }

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Bahan Masuk";
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "id" => $row[0],
                    "id_supplier" => $row[1],
                    "id_bahan" => $row[2],
                    "jumlah_retur" => $row[3],
                    "jumlah_masuk" => $row[4],
                    "kebutuhan" => $row[5],
                    "tanggal_masuk" => $row[6],
                    "date_add" => $row[7],
                    "bahan" => $row[9],
                    "supplier" => $row[10],
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
    $bahan = $_POST["s_bahan"];
    $supplier = $_POST["s_supplier"];
    $tanggal_masuk = $_POST["s_tanggal_masuk"];
    $jumlah_masuk = $_POST["s_jumlah_masuk"];
    $jumlah_retur = $_POST["s_jumlah_retur"];
    $kebutuhan = $_POST["s_kebutuhan"];

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "";

    if ($id == "") {
        $sql = "INSERT INTO `bahan_masuk` (`id_supplier`, `id_bahan`, `jumlah_retur`, `jumlah_masuk`, `kebutuhan`, `tanggal_masuk`) ";
        $sql .= "VALUES ('$supplier','$bahan','$jumlah_retur','$jumlah_masuk','$kebutuhan','$tanggal_masuk')";
        $res["msg"] = "Bahan masuk berhasil ditambahkan.";

    } else {
        $sql = "UPDATE `bahan_masuk` SET `id_supplier`='$supplier', `id_bahan`='$bahan',`jumlah_retur`='$jumlah_retur', `jumlah_masuk`='$jumlah_masuk', `kebutuhan`='$kebutuhan', `tanggal_masuk`='$tanggal_masuk' WHERE `id` = '$id'";
        $res["msg"] = "Bahan masuk  berhasil diubah.";
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

    $sql = "UPDATE `bahan_masuk` SET `active` = 0 WHERE `id` = '$id'";
    $res["msg"] = "Bahan masuk berhasil dihapus.";

    if ($result = $mysql->query($sql)) {
    } else {
        $res["error"] = 1;
        $res["msg"] = $mysql->error;
    }
    echo json_encode($res);

}
