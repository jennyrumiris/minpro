<?php
// detail.php

include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-detail.css">
    <title>Detail Konser</title>
</head>
<body>
    <header>
        <div class="atas">
            <a href="index.php" class="logo"><img src="konser.jpg" alt=""></a>
            <div class="topnav">
                <a class="active" href="#home">Home</a> 
                <a href="">login</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
                <select name="Kategori" id="kategori">
                    <option value='populer'>Populer</option>
                    <option value='on going'>On Going</option>
                    <option value='terdekat'>Terdekat</option>
                </select>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <form class="search" action="action_page.php">
                    <input type="text" placeholder="Search.." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </header>

    <h5 class="back">
        <a href="index.php" class="back-link"> << Home </a>
    </h5>

    <main>
        <section>
            <table>
                <tr>
                    <td>
                        <?php
                            // Ambil concert_id dari parameter URL, jika tidak ada, gunakan nilai default 1
                            $concert_id = isset($_GET['concert_id']) ? intval($_GET['concert_id']) : 1;

                            $sql = "SELECT name, date, venue_id, image_path FROM concerts WHERE concert_id= $concert_id";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo "<div>";
                                echo "<img src='" . $row["image_path"] . "' alt='" . $row["name"] . "' class='pict1'/>";
                                echo "</div>";
                            } else {
                                echo "Tidak ada data konser.";
                            }
                        ?>
                    </td>
                    <td>                        
                        <div id="keterangan">
                            <?php
                                $sql = "SELECT concerts.name AS concert_name, concerts.date, CONCAT(venues.name, ', ', venues.location) AS venue_info
                                FROM concerts
                                INNER JOIN venues ON concerts.venue_id = venues.venue_id
                                WHERE concerts.concert_id = $concert_id";

                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();

                                    // Memisahkan tanggal dan waktu
                                    $datetime = $row["date"];
                                    $datetime_parts = explode(" ", $datetime);
                                    $tanggal = $datetime_parts[0];
                                    $waktu = $datetime_parts[1];

                                    // Mengubah format tanggal
                                    $tanggal = date("j F Y", strtotime($row["date"]));

                                    // Ikon 
                                    $ikon_wkt = "&#128339; ";
                                    $ikon_tgl = "&#128197; ";
                                    $ikon_lok = "&#128204; ";

                                    // Masukin ikon ke keterangan konser
                                    $venue_info = $ikon_lok.$row["venue_info"];
                                    $wkt_info = $ikon_wkt.$waktu;
                                    $tgl_info = $ikon_tgl.$tanggal; 

                                    echo "<div>";
                                    echo "<h3>".$row["concert_name"]."</h3>";
                                    echo "<h1>".$tgl_info."</h1>";
                                    echo "<h1>".$wkt_info."</h1>";
                                    echo "<h1>".$venue_info."</h1>";
                                    echo "</div>";
                                } else {
                                    echo "Tidak ada data konser.";
                                }
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        </section> 

        <div class="tabs">
            <button class="tablink" onclick="openTab(event,'Deskripsi')" id="defaultOpen">DESKRIPSI</button>
            <button class="tablink" onclick="openTab(event, 'Tikets')">TIKET</button>
        </div>
        <div id="Deskripsi" class="tabcontent">
            <!-- Mengatur seating plan secara statis -->
            <?php
                $sql = "SELECT seating_plan FROM concerts WHERE concert_id = $concert_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $seatingPlanPath = $row["seating_plan"];
                    
                    // Check if the seating plan path is not empty
                    if (!empty($seatingPlanPath)) {
                        // Ensure the path is safe and does not contain malicious content
                        $safeSeatingPlanPath = htmlspecialchars($seatingPlanPath, ENT_QUOTES, 'UTF-8');
                        echo "<img id='gambar' src='" . $safeSeatingPlanPath . "' alt='Seating Plan Konser'>";
                    } else {
                        echo "Tidak Ada Seating Plan";
                    }
                } else {
                    echo "Tidak Ada Seating Plan";
                }
            ?>

            <!-- Mengatur Deskripsi secara statis -->
            <?php
                $sql = "SELECT informasi_konser FROM concerts WHERE concert_id = $concert_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $deskripsi = htmlspecialchars($row["informasi_konser"],ENT_QUOTES,'UTF-8');

                    // mmeisahkan deskripsi berdasarkan baris baru
                    // explode di sini bertujuan untuk memisahkan teks deskripsi berdasarkan 
                    // baris baru sehingga kita dapat mengatur format setiap baris secara individual. 
                    $deskripsi_baris = explode("\n",$deskripsi);
                    
                    echo "<div class='deskripsi'>";

                    //H3 di baris pertama
                    if(isset($deskripsi_baris[0])){
                        echo "<h3>".nl2br($deskripsi_baris[0])."</h3>";
                    }
                    // baris2 setelahnnya jadi P
                    for($i = 1;$i < count($deskripsi_baris); $i++){
                        if(!empty(trim($deskripsi_baris[$i]))) {
                            echo "<p>" . nl2br($deskripsi_baris[$i]) . "</p>";
                        }
                    }
                    echo "</div>";
                }else{
                    echo "";
                }
            ?>
        </div>

        <div id="Tikets" class="tabcontent">
            <h3>Paket Tiket</h3>
            <ul class="tiket-list">
                <?php
                    $sql = "SELECT s.seat_id, s.concert_id, st.type_name, st.price, s.available_seats, s.end_date
                            FROM seats s INNER JOIN seattypes st ON s.seat_type_id = st.seat_type_id
                            WHERE s.concert_id = $concert_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $end_date = date("d F Y . H:i", strtotime($row["end_date"]));
                            echo "<li>";
                            // htmlspecialchars digunakan untuk memastikan data yang ditampilkan dari database 
                            // ke halaman web tidak berisi kode HTML atau JavaScript yang dapat dieksekusi.
                            // Dalam konteks kode Anda, penggunaan htmlspecialchars dengan ENT_QUOTES, 'UTF-8' memastikan bahwa data 
                            // yang diambil dari database dan ditampilkan di halaman web aman dan ditampilkan dengan benar
                            echo "<h3 class='ticket-title'>".htmlspecialchars($row["type_name"], ENT_QUOTES, 'UTF-8')."</h3>";
                            echo "<p class='tiket-price'>Rp ".number_format($row["price"], 0, ",", ".")."</p>";
                            if ($row["available_seats"] > 0) { // Cek apakah stok tiket tersedia
                                echo "<p class='tiket-stok'>Stok Tersedia: " . $row["available_seats"] . "</p>"; // Menampilkan stok tiket
                                echo "<p class='tiket-end-date'><span class='icon-clock'>&#128337;</span> Berakhir $end_date WIB</p>";
                                echo "<input type='number' name='jumlah_tiket[" . $row['seat_id'] . "]' min='0' max='5' value='0'>"; // Input field untuk memilih jumlah tiket
                            } else {
                                echo "<p class='tiket-stok'>Tiket Habis Terjual</p>"; // Menampilkan pesan jika tiket habis
                            }
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Tidak ada tiket yang tersedia.</li>";
                    }
                ?>
            </ul>
            <button type="submit" name="pesan" href="pemesanan.php">Pesan</button>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById("defaultOpen").click();
        });

        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

    <footer>
        <img src="konser.jpg" alt="seru">
        <p>&copy; 2024 Konser Live. All rights reserved.</p>
        <div class="social-icons">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
        </div>
    </footer>
</body>
</html>
