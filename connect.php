<?php
$mysql = new mysqli("localhost","root","sense324","pengadaan");

// Check connection
if ($mysql -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysql -> connect_error;
    exit();
}
