<?php
include_once "../connect.php";

if (isset($_GET["load"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $m = $_GET["m"];
    $y = $_GET["y"];

    if ($m > 9) {
        $m = "0"+$m;
    }

    $sql = "SELECT a.id, b.nama as bahan, a.jumlah as estimasi, (SELECT aa.sisa FROM bahan_sisa aa WHERE aa.tahun = ".$y." AND aa.bulan = ".$m." AND aa.id_bahan = b.id ORDER BY aa.id DESC LIMIT 1) as stok FROM `peramalan` a";
    $sql .= " LEFT JOIN `bahan` b ON a.id_bahan = b.id ";
    $sql .= " WHERE bulan = $m AND tahun = $y AND a.active = 1 ";
    
    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Peramalan";
            while ($row = $result->fetch_array()) {
                //Supplier Ranking
                $spp = getRankingSupplier($mysql, $row["id"]);

                $estimasi = $row["estimasi"];

                //Stok
                $stok = 0;
                if ($row["stok"] != null) {
                    $stok = $row["stok"];
                }

                //Jumlah
                $jumlah = 0;
                $jumlah = (int)$estimasi - (int)$stok;
                if ($jumlah < 0) {
                    $jumlah = 0;
                }

                //Total Harga
                $total_harga = (int)$spp->harga_bahan * (int)$jumlah;

                //Pengadaan
                $pengadaan = "Pengadaan";
                if ($jumlah <= 1000) {
                    $pengadaan = "Tidak";
                }

                $data[] = array(
                    "id" => $row["id"],
                    "bahan" => $row["bahan"],
                    "supplier" => $spp->supplier,
                    "estimasi" => $row["estimasi"],
                    "stok" => $stok,
                    "jumlah" => $jumlah,
                    "total_harga" => number_format($total_harga),
                    "pengadaan" => $pengadaan
                );
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

} else if (isset($_GET["loadSupplier"])) {

    $res["error"] = 0;
    $res["msg"] = "";

    $idPeramalan = $_POST['id_peramalan'];

    $sql = "SELECT c.nama as supplier, b.harga";
    $sql .= " FROM `peramalan_supplier` a ";
    $sql .= " JOIN `supplier_bahan` b ON a.id_supplier_bahan = b.id ";
    $sql .= " JOIN `supplier` c ON b.id_supplier = c.id ";
    $sql .= " WHERE a.id_peramalan = $idPeramalan ";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $res["msg"] = "Peramalan";
            while ($row = $result->fetch_array()) {
                $data[] = array(
                    "supplier" => $row["supplier"],
                    "harga" => $row["harga"]
                );
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

} else if (isset($_GET["loadBahanSisa"])) {

    $res["error"] = 0;
    $res["msg"] = "Peramalan bahan";

    $tanggal = explode("-", $_POST['tanggal']);
    $bahan = explode("#", $_POST['bahan']);


    $id_bahan = $bahan[1];

    $sql = "SELECT jumlah";
    $sql .= " FROM `peramalan`";
    $sql .= " WHERE id_bahan = $id_bahan AND tahun = $tanggal[0] AND bulan = $tanggal[1] ";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {
            $d = $result->fetch_row();

            $res["data"] = $d[0];
        } else {
            //Username password not match
            $res["data"] = 0;

            $res["error"] = 0;
        }
        $result->close();
    }
    echo json_encode($res);

} else if (isset($_GET["generate"])) {

    $addSisa = false;

    $res["error"] = 0;
    $res["msg"] = "";

    //Get Date Range
    $y = $_GET['tahun'];
    $m = $_GET['bulan'];
    $dateEnd = $y . "-" . $m . "-31";
//
//    if (strtotime($dateEnd) > strtotime("2019-07-01")) {
//        $addSisa = true;
//    }

    //Get StartDate
    $dateToDiff = "1" . "-" . $m . "-" . $y;
    $formatDateToDiff = "1" . "-" . $m . "-" . $y;
    $dateToDiff = DateTime::createFromFormat('d-m-Y', $formatDateToDiff)->format('Y-m-d');
    $dateStart = date('Y-m-d', strtotime('-3 months', strtotime($dateToDiff)));

    //Get End Date
    $dateToDiff = "1" . "-" . $m . "-" . $y;
    $formatDateToDiff = "1" . "-" . $m . "-" . $y;
    $dateToDiff = DateTime::createFromFormat('d-m-Y', $formatDateToDiff)->format('Y-m-d');
    $dateEnd = date('Y-m', strtotime('-1 months', strtotime($dateToDiff)));
    $dateEnd = $dateEnd . "-31";
    //Start transaction
    $mysql->begin_transaction();

    # STEP 0 : GET ALL BAHAN
    $sqlBahan = "SELECT id FROM `bahan` WHERE `active` = 1";
    //Looping bahan
    if ($resBahan = $mysql->query($sqlBahan)) {
        if ($resBahan->num_rows > 0) {
            while ($row = $resBahan->fetch_array()) {
                $avg = 0;
                $tahunStok = $y;
                $bulanStok = $m-1;
                if ($bulanStok == 0) {
                    $bulanStok = 12;
                    $tahunStok = $y-1;
                }

                $f = $bulanStok."/".$tahunStok;

                $id_bahan = $row["id"];
                # STEP 1.1 : Generate rata2 kebutuhan bedasarkan bulan
                $sqlGetRata = 'SELECT AVG(a.bahan_produksi) as rata_rata, (SELECT bk.stok FROM bahan_keluar bk WHERE bk.id_bahan = ' . $id_bahan . ' AND DATE_FORMAT(bk.tanggal, "%c/%Y") = "'.$f.'" AND bk.active = 1 ORDER BY id DESC LIMIT 1) as sisa ';
                $sqlGetRata .= ' FROM bahan_keluar a';
                $sqlGetRata .= ' WHERE a.id_bahan = "' . $id_bahan . '" AND (a.tanggal BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '") AND a.active = "1"';

                # STEP 1.2 : INSERT sisa to table bahan_sisa
                if ($resRata = $mysql->query($sqlGetRata)) {
                    if ($resRata->num_rows > 0) {
                        while ($rowRata = $resRata->fetch_array()) {
                            $sisa = 0;
                            $avg = floatval($rowRata["rata_rata"]);
                            $sisa = floatval($rowRata["sisa"]);

//                            if ($addSisa) {
//                                //Set sisa
//
//                                if (floatval($rowRata["sisa"]) > 0) {
//                                }
//
//                                //Set Avg
//                                if ($sisa > 0) {
//                                    if ($sisa > $avg) {
//                                        $avg = $sisa - $avg;
//                                    } else {
//                                        $avg = $avg - $sisa;
//                                    }
//                                }
//                            }
//
//                            print_r($sisa);die;

                            $sqlInsertSisa = "INSERT INTO `bahan_sisa` (`id_bahan`, `sisa`, `tahun`, `bulan`) ";
                            $sqlInsertSisa .= "VALUES ('$id_bahan','$sisa','$y', '$m')";

                            if ($mysql->query($sqlInsertSisa)) {
                                //Sukses
                            } else {
                                showError($mysql, $res, "Step 1.2 Error");
                            }
                        }
                    } else {
                        showError($mysql, $res, "Step 1.1 Error");
                    }
                } else {
                    showError($mysql, $res);
                }

                # Insert to table Peramalan
                $sqlInsertPermalan = "INSERT INTO `peramalan` (`id_bahan`, `jumlah`, `bulan`, `tahun`) ";
                $sqlInsertPermalan .= "VALUES ('$id_bahan','$avg','$m', '$y')";
                if ($mysql->query($sqlInsertPermalan)) {

                    $id_peramalan = $mysql->insert_id;

                    # STEP 2 : get Supplier bobot harga, waktu dan retur
                    $sqlBobot = 'SELECT MIN(bobot_harga) as res_bobot_harga, MAX(bobot_waktu) as res_bobot_waktu, MAX(bobot_retur) as res_bobot_retur';
                    $sqlBobot .= ' FROM supplier_bahan';
                    $sqlBobot .= ' WHERE id_bahan = "' . $id_bahan . '" AND active = "1"';

                    if ($resBobot = $mysql->query($sqlBobot)) {
                        if ($resBobot->num_rows > 0) {
                            while ($rowBobot = $resBobot->fetch_array()) {
                                $bHarga = $rowBobot['res_bobot_harga'];
                                $bWaktu = $rowBobot['res_bobot_waktu'];
                                $bRetur = $rowBobot['res_bobot_retur'];

                                # STEP 3 : get nilai Akhir
                                $sqlRangking = 'SELECT a.id as id_supplier_bahan, ((' . $bHarga . '/a.bobot_harga)*2) + ((a.bobot_waktu/' . $bWaktu . ')*4) + ((a.bobot_retur/' . $bRetur . ')*4) as nilai_akhir';
                                $sqlRangking .= ' FROM supplier_bahan a';
                                $sqlRangking .= ' JOIN bahan_masuk b on a.id = b.id_supplier_bahan';
                                $sqlRangking .= ' WHERE a.id_bahan = "' . $id_bahan . '" AND a.active = "1"';
                                $sqlRangking .= ' GROUP BY a.id, a.id_supplier';
                                $sqlRangking .= ' ORDER BY nilai_akhir DESC';

                                if ($resRanking = $mysql->query($sqlRangking)) {
                                    if ($resRanking->num_rows > 0) {
                                        while ($rowRangking = $resRanking->fetch_array()) {
                                            $id_supp_bahan = $rowRangking["id_supplier_bahan"];
                                            $nilai_akhir = $rowRangking["nilai_akhir"];

                                            $sqlInsertRangking = "INSERT INTO `peramalan_supplier` (`id_peramalan`, `id_supplier_bahan`, `nilai_akhir`) ";
                                            $sqlInsertRangking .= "VALUES ('$id_peramalan','$id_supp_bahan','$nilai_akhir')";

                                            if ($mysql->query($sqlInsertRangking)) {

                                            } else {
                                                showError($mysql, $res, "Step Insert Rangking Error");
                                            }
                                        }
                                    } else {
                                        showError($mysql, $res, "Step 4 Error 1");
                                    }
                                } else {
                                    showError($mysql, $res, "Step 4 Error 2");
                                }
                            }
                        } else {
                            showError($mysql, $res, "Step 2 Error");
                        }
                    } else {
                        showError($mysql, $res, "");
                    }

                } else {
                    showError($mysql, $res, "Step Insert Peramalan Error");
                }
            }
        } else {
            showError($mysql, $res);
        }
    } else {
        showError($mysql, $res);
    }

    $mysql->commit();
    $res["msg"] = "Generate berhasil";
    echo json_encode($res);
}

function showError($mysql, $res, $msg = "")
{
    $mysql->rollback();
    $res["error"] = "1";
    $res["msg"] = $msg;
    echo json_encode($res);
    return;
}

function getRankingSupplier($mysql, $id_peramalan)
{
    $sql = "SELECT cc.nama as supplier, b.harga as harga_bahan FROM `peramalan_supplier` bb  JOIN `supplier_bahan` b ON bb.id_supplier_bahan = b.id  JOIN `supplier` cc ON b.id_supplier = cc.id  WHERE bb.id_peramalan = '$id_peramalan' LIMIT  1;";

    if ($result = $mysql->query($sql)) {
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_array()) {
                $a = $row[0];
                $b = $row[1];
                $d = new StdClass();
                $d->supplier = $a;
                $d->harga_bahan = $b;

                return $d;
            }
        }
    }
}