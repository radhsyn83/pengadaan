<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT a.*, b.harga, c.nama as bahan, d.nama as supplier FROM `pengadaan` a 
LEFT JOIN supplier_bahan b ON a.id_supplier_bahan = b.id
LEFT JOIN bahan c ON b.id_bahan = c.id
LEFT JOIN supplier d ON b.id_supplier = d.id
WHERE a.active = 1 AND b.active = 1 ";

    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
        $sql .= "AND DATE_FORMAT(`a`.`tanggal_pengadaan`, '%m/%Y') = '$filter'";
    }

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Pengadaan";
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "id" => $row[0],
                    "bahan" => $row[7],
                    "supplier" => $row[8],
                    "harga" => $row[6],
                    "jumlah" => $row[3],
                    "tanggal_pengadaan" => $row[2]
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
    $id_supplier_bahan = $_POST["s_supplier_bahan"];
    $tanggal_pengadaan = $_POST["s_tanggal_pengadaan"];
    $jumlah = $_POST["s_jumlah"];

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "";

    if ($id == "") {
        $sql = "INSERT INTO `pengadaan` (`id_supplier_bahan`, `tanggal_pengadaan`, `jumlah`) ";
        $sql .= "VALUES ('$id_supplier_bahan','$tanggal_pengadaan','$jumlah')";
        $res["msg"] = "Pengadaan berhasil ditambahkan.";

    } else {
        $sql = "UPDATE `pengadaan` SET `id_supplier_bahan`='$id_supplier_bahan', `tanggal_pengadaan`='$tanggal_pengadaan', `jumlah`='$jumlah' WHERE `id` = '$id'";
        $res["msg"] = "Pengadaan berhasil diubah.";
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

    $sql = "UPDATE `pengadaan` SET `active` = 0 WHERE `id` = '$id'";
    $res["msg"] = "Pengadaan berhasil dihapus.";

    if ($result = $mysql->query($sql)) {
    } else {
        $res["error"] = 1;
        $res["msg"] = $mysql->error;
    }
    echo json_encode($res);

}
