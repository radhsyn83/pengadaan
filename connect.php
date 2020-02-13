<?php
$mysql = new mysqli("165.22.242.133","admin","sense324","db_pengadaan");

// Check connection
if ($mysql -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysql -> connect_error;
    exit();
}
