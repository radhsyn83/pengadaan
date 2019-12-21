<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

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

} else if (isset($_GET["add-update"])) {

    $id = $_POST["id-bahan"];
    $nama = $_POST["nama-bahan"];

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "";

    if ($id == "") {
        $sql = "INSERT INTO `bahan` (`nama`) VALUES ('$nama')";
        $res["msg"] = "Bahan berhasil ditambahkan.";

    } else {
        $sql = "UPDATE `bahan` SET `nama` = '$nama' WHERE `id` = '$id'";
        $res["msg"] = "Bahan berhasil diubah.";
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

    $sql = "UPDATE `bahan` SET `active` = 0 WHERE `id` = '$id'";
    $res["msg"] = "Bahan berhasil dihapus.";

    if ($result = $mysql->query($sql)) {
    } else {
        $res["error"] = 1;
        $res["msg"] = $mysql->error;
    }
    echo json_encode($res);

} else if (isset($_GET["loadBySupplier"])) {

    $id_supplier = $_POST["id_supplier"];

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT a.*, b.nama FROM `supplier_bahan` a ";
    $sql .= "LEFT JOIN `bahan` b ON a.id_bahan = b.id ";
    $sql .= "WHERE `a`.`active` = 1 AND `a`.id_supplier = $id_supplier";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Bahan Baku";
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "id" => $row[0],
                    "nama" => $row[9]
                );
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

}
