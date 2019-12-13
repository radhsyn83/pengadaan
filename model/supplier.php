<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT * FROM `supplier` WHERE `active` = 1";
    $sql .= "";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Supplier";
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "id" => $row[0],
                    "nama" => $row[1],
                    "telp" => $row[2],
                    "alamat" => $row[3],
                    "date_add" => $row[4],
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
    $nama = $_POST["s_nama"];
    $telp = $_POST["s_telp"];
    $alamat = $_POST["s_alamat"];

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "";

    if ($id == "") {
        $sql = "INSERT INTO `supplier` (`nama`, `telp`, `alamat`) ";
        $sql .= "VALUES ('$nama','$telp','$alamat')";
        $res["msg"] = "Supplier berhasil ditambahkan.";

    } else {
        $sql = "UPDATE `supplier` SET `nama`='$nama', `telp`='$telp',`alamat`='$alamat' WHERE `id` = '$id'";
        $res["msg"] = "Supplier berhasil diubah.";
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

    $sql = "UPDATE `supplier` SET `active` = 0 WHERE `id` = '$id'";
    $res["msg"] = "Supplier berhasil dihapus.";

    if ($result = $mysql->query($sql)) {
    } else {
        $res["error"] = 1;
        $res["msg"] = $mysql->error;
    }
    echo json_encode($res);

} else if (isset($_GET["load-bahan"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $id = $_POST["id_supplier"];

    $sql = "SELECT a.*, b.nama as bahan FROM `supplier_bahan` a ";
    $sql .= "LEFT JOIN `bahan` b ON a.id_bahan = b.id ";
    $sql .= "WHERE `a`.`active` = 1 AND `a`.`id_supplier` = '$id'";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Supplier Bahan";
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "id" => $row[0],
                    "id_supplier" => $row[1],
                    "id_bahan" => $row[2],
                    "harga" => $row[3],
                    "bahan" => $row[6],
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

} else if (isset($_GET["bahan-add-update"])) {

    $id = $_POST["id-supplier-bahan"];
    $id_supplier = $_POST["modalSupplierId"];
    $id_bahan = $_POST["bahan-id"];
    $harga = $_POST["bahan-harga"];

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "";

    if ($id == "") {
        $sql = "INSERT INTO `supplier_bahan` (`id_supplier`, `id_bahan`, `harga`, `active`) ";
        $sql .= "VALUES ('$id_supplier','$id_bahan','$harga', '1')";
        $res["msg"] = "Supplier bahan berhasil ditambahkan.";

    } else {
        $sql = "UPDATE `supplier_bahan` SET `id_supplier`='$id_supplier', `id_bahan`='$id_bahan',`harga`='$harga' WHERE `id` = '$id'";
        $res["msg"] = "Supplier bahan berhasil diubah.";
    }

    if ($result = $mysql->query($sql)) {

    } else {
        $res["error"] = 1;
        $res["msg"] = $mysql->error;
    }
    echo json_encode($res);

} else if (isset($_GET["hapus-bahan"])) {

    $id = $_POST["id"];

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "UPDATE `supplier_bahan` SET `active` = 0 WHERE `id` = '$id'";
    $res["msg"] = "Supplier bahan berhasil dihapus.";

    if ($result = $mysql->query($sql)) {
    } else {
        $res["error"] = 1;
        $res["msg"] = $mysql->error;
    }
    echo json_encode($res);

}
