<?php
// index.php

// Database connection
include "koneksi.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch concert details based on the category
$diadakanBulanIniSql = "SELECT concert_id, name, start_date, end_date, image_path, price, start_time, end_time 
                FROM concerts 
                WHERE MONTH(start_date) = MONTH(CURRENT_DATE()) 
                AND YEAR(start_date) = YEAR(CURRENT_DATE())";

$diadakanTahunIniSql = "SELECT concert_id, name, start_date, end_date, image_path, price, start_time, end_time 
                FROM concerts 
                WHERE YEAR(start_date) = YEAR(CURRENT_DATE())";

// If there's a search query, modify the SQL to include the search term
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($searchQuery)) {
    $diadakanBulanIniSql .= " AND name LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
    $diadakanTahunIniSql .= " AND name LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
}

$diadakanBulanIniResult = $conn->query($diadakanBulanIniSql);
$diadakanTahunIniResult = $conn->query($diadakanTahunIniSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Pembelian Tiket Konser</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <header>
        <div class="atas">
            <a href="login.html" class="logo"><img src="konser.jpg" alt=""></a>
            <div class="topnav">
                <a href="login_form.php">login</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
                <form class="search" action="" method="GET">
                    <input type="text" placeholder="Search.." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </header>
    <main>
        <table>
            <tr>
                <td><p id="diadakan">Diadakan Bulan Ini</p></td>
            </tr>
            <tr>
                <td>
                    <div class="slider">
                        <div class="slides">
                            <?php
                            if ($diadakanBulanIniResult->num_rows > 0) {
                                while($row = $diadakanBulanIniResult->fetch_assoc()) {
                                    echo "<div class='slide'>";
                                    echo "<a href='detail.php?concert_id=" . $row["concert_id"] . "'><img src='" . $row["image_path"] . "' alt='" . $row["name"] . "'>";
                                    echo "<p id='namakonser'>" . $row["name"] . "</p>";
                                    echo "<p id='tanggal'>";

                                    $startDate = date('j', strtotime($row["start_date"]));
                                    $endDate = date('j', strtotime($row["end_date"]));
                                    $startMonth = date('F', strtotime($row["start_date"]));
                                    $endMonth = date('F', strtotime($row["end_date"]));
                                    $year = date('Y', strtotime($row["start_date"]));

                                    if ($row["start_date"] == $row["end_date"]) {
                                        echo "$startDate $startMonth, $year";
                                    } else {
                                        if ($startMonth == $endMonth) {
                                            echo "$startDate - $endDate $startMonth, $year";
                                        } else {
                                            echo "$startDate $startMonth - $endDate $endMonth, $year";
                                        }
                                    }
                                    echo "<br>";
                                    echo " (" . $row["start_time"] . " - " . $row["end_time"] . ")";
                                    echo "</p>";
                                    echo "<p id='harga'>Harga: Rp." . number_format($row["price"], 0, ',', '.') . " (tersedia)</p>";
                                    echo "</a>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>Tidak ada konser bulan ini</p>";
                            }
                            ?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><p id="diadakan">Diadakan Tahun Ini</p></td>
            </tr>
            <tr>
                <td>
                    <div class="slider">
                        <div class="slides">
                            <?php
                            if ($diadakanTahunIniResult->num_rows > 0) {
                                while($row = $diadakanTahunIniResult->fetch_assoc()) {
                                    echo "<div class='slide'>";
                                    echo "<a href='detail.php?concert_id=" . $row["concert_id"] . "'><img src='" . $row["image_path"] . "' alt='" . $row["name"] . "'>";
                                    echo "<p id='namakonser'>" . $row["name"] . "</p>";
                                    echo "<p id='tanggal'>";

                                    $startDate = date('j', strtotime($row["start_date"]));
                                    $endDate = date('j', strtotime($row["end_date"]));
                                    $startMonth = date('F', strtotime($row["start_date"]));
                                    $endMonth = date('F', strtotime($row["end_date"]));
                                    $year = date('Y', strtotime($row["start_date"]));

                                    if ($row["start_date"] == $row["end_date"]) {
                                        echo "$startDate $startMonth, $year";
                                    } else {
                                        if ($startMonth == $endMonth) {
                                            echo "$startDate - $endDate $startMonth, $year";
                                        } else {
                                            echo "$startDate $startMonth - $endDate $endMonth, $year";
                                        }
                                    }
                                    echo "<br>";
                                    echo " (" . $row["start_time"] . " - " . $row["end_time"] . ")";
                                    echo "</p>";
                                    echo "<p id='harga'>Harga: Rp." . number_format($row["price"], 0, ',', '.') . " (tersedia)</p>";
                                    echo "</a>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>Tidak ada konser tahun ini</p>";
                            }
                            ?>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </main>
    <footer>
      <img src="konser.jpg" alt="seru">
      <p>&copy; 2024 Konser Live. All rights reserved.</p>
      <div class="social-icons">
          <a href="https://api.whatsapp.com/send?phone=6281234567890" class="icon whatsapp"></a>
          <a href="https://www.instagram.com/carolimanuella/" class="icon instagram"></a>
          <a href="#" class="icon facebook"></a>
          <a href="#" class="icon tiktok"></a>
          <a href="#" class="icon telegram"></a>
      </div>
    </footer>
</body>
</html>
