<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT a.*, (b.harga*a.jumlah) as harga, c.nama as bahan, d.nama as supplier FROM `pengadaan` a 
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
                    "harga" => number_format($row[6]),
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

    $id_supplier_bahan = explode("#", $id_supplier_bahan)[0];

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

} else if (isset($_GET["loadPeramalan"])) {
    $res["error"] = 0;
    $res["msg"] = "Peramalan bahan";

    $res["supplier"] = getSupplier($mysql);
    $res["jumlah"] = getJumlah($mysql);

    echo  json_encode($res);
}

function getSupplier($mysql) {

    $tanggal = explode("-", $_POST['tanggal']);
    $bahan = $_POST['bahan'];

    $b = (int)$tanggal[1];

    $sql = "SELECT s.nama, ps.nilai_akhir, ps.id_supplier_bahan FROM peramalan p ";
    $sql .= "JOIN peramalan_supplier ps ON p.id = ps.id_peramalan ";
    $sql .= "JOIN supplier_bahan sb on ps.id_supplier_bahan = sb.id ";
    $sql .= "JOIN supplier s ON sb.id_supplier = s.id ";
    $sql .= "WHERE p.id_bahan = $bahan AND p.bulan = $b AND p.tahun = $tanggal[0] AND p.active = 1 ";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "id_supplier_bahan" => $row["id_supplier_bahan"],
                    "nama_supplier" => $row["nama"],
                );
            }

            return $data;
        } else {
            return null;
        }
        $result->close();
    }
}

function getJumlah($mysql) {
    $tanggal = explode("-", $_POST['tanggal']);
    $bahan = $_POST['bahan'];

    $b = (int)$tanggal[1];

    $sql = "SELECT a.id, b.nama as bahan, a.jumlah as estimasi, (SELECT aa.sisa FROM bahan_sisa aa WHERE aa.tahun = ".$tanggal[0]." AND aa.bulan = ".$b." AND aa.id_bahan = b.id ORDER BY aa.id DESC LIMIT 1) as stok FROM `peramalan` a";
    $sql .= " LEFT JOIN `bahan` b ON a.id_bahan = b.id ";
    $sql .= " WHERE id_bahan = $bahan AND bulan = $b AND tahun = $tanggal[0] AND a.active = 1 ";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Peramalan";
            while ($row = $result->fetch_array()) {
                $estimasi = $row["estimasi"];

                //Stok
                $stok = 0;
                if ($row["stok"] != null) {
                    $stok = $row["stok"];
                }

                //Jumlah
                $jumlah = (int)$estimasi - (int)$stok;
                if ($jumlah < 0) {
                    $jumlah = 0;
                }

                return (int)$jumlah;
            }

        } else {
            return 0;
        }
        $result->close();
    }
}

