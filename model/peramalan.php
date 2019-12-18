<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT a.*, b.nama as bahan, c.nama as supplier FROM `peramalan` a ";
    $sql .= "LEFT JOIN `bahan` b ON a.id_bahan = b.id ";
    $sql .= "LEFT JOIN `supplier` c ON a.id_supplier = c.id ";

    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
        $sql .= "AND DATE_FORMAT(`a`.`tanggal_masuk`, '%m/%Y') = '$filter'";
    }

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Peramalan";
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "id" => $row[0],
                    "harga" => $row[3],
                    "jumlah" => $row[4],
                    "date_add" => $row[5],
                    "bahan" => $row[6],
                    "supplier" => $row[7]);
            }

            $res["data"] = $data;
        } else {
            //Username password not match
            $res["error"] = 2;
            $res["msg"] = "Peramalan belum tersedia.";
        }
        $result->close();
    }
    echo json_encode($res);

}
