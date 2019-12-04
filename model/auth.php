<?php
include_once "../connect.php";
session_start();

if (isset($_GET["login"])) {
    $u = $_POST["username"];
    $p = md5($_POST["password"]);

    $res["error"] = 0;
    $res["msg"] = "";

    $sql = "SELECT user.*, jabatan.nama as jabatan FROM `user` LEFT JOIN `jabatan` ON `jabatan`.`id` = `user`.`id_jabatan` WHERE `username` = '$u' AND `passwd` = '$p'";

    if ($result = $mysql->query($sql)) {
        //Username password match
        if ($result->num_rows > 0) {
            $res["msg"] = "Login berhasil!!";
            $row = $result->fetch_assoc();
            //Insert session
            $_SESSION["id"] = $row["id"];
            $_SESSION["nama"] = $row["nama"];
            $_SESSION["id_jabatan"] = $row["id_jabatan"];
            $_SESSION["jabatan"] = $row["jabatan"];
        } else {
            //Username password not match
            $res["error"] = 1;
            $res["msg"] = "Login gagal.";
        }
        $result->close();
    }
    echo json_encode($res);
} else {
    session_destroy();
    echo json_encode("");
}
