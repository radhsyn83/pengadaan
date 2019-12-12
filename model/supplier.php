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
    $harga = $_POST["s_harga"];
    $id_bahan = $_POST["s_bahan"];


    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "";

    if ($id == "") {
        $sql = "INSERT INTO `supplier` (`id_bahan`, `nama`, `telp`, `alamat`, `harga`) ";
        $sql .= "VALUES ('$id_bahan','$nama','$telp','$alamat','$harga')";
        $res["msg"] = "Supplier berhasil ditambahkan.";

    } else {
        $sql = "UPDATE `supplier` SET `id_bahan`='$id_bahan', `nama`='$nama', `telp`='$telp',`alamat`='$alamat', `harga`='$harga' WHERE `id` = '$id'";
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

} else if (isset($_GET["load-select"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT a.*, b.nama as bahan FROM `supplier` a ";
    $sql .= "LEFT JOIN `bahan` b ON a.id_bahan = b.id ";
    $sql .= "WHERE `a`.`active` = 1";

    print_r($sql);die;

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Supplier";
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "id" => $row[0],
                    "id_bahan" => $row[1],
                    "nama" => $row[2],
                    "telp" => $row[3],
                    "alamat" => $row[4],
                    "harga" => $row[5],
                    "date_add" => $row[6],
                    "bahan" => $row[8],
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

}
