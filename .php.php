<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host = "localhost"; // Server database
$user = "root"; // Username MySQL (sesuaikan jika berbeda)
$password = ""; // Password MySQL (kosong jika default)
$database = "toko_syauqi"; // Nama database

// Koneksi ke MySQL
$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Data produk yang akan dimasukkan
$data_produk = [
    ["Produk A", 100000, "Produk fashion berkualitas tinggi", "fashion", "img/baju1.jpg"],
    ["Produk B", 150000, "Fashion terbaru untuk gaya modern", "fashion", "img/baju2.jpg"],
    ["Produk C", 200000, "Peralatan rumah tangga yang praktis", "peralatan", "img/peralatan.jpg"],
    ["Produk D", 250000, "Gadget canggih dengan fitur terbaik", "elektronik", "img/hp1.jpg"],
    ["Produk E", 300000, "Smartphone terbaru dengan fitur canggih", "elektronik", "img/hp2.jpg"]
];

// Persiapkan query SQL untuk memasukkan data
$sql = "INSERT INTO produk (name, price, description, category, image) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Masukkan data ke database
foreach ($data_produk as $produk) {
    $stmt->bind_param("sisss", $produk[0], $produk[1], $produk[2], $produk[3], $produk[4]);
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
}

echo "Data produk berhasil dimasukkan!";
$stmt->close();
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'all';

// Query SQL
if ($kategori == "all") {
    $sql = "SELECT * FROM produk";
} else {
    $sql = "SELECT * FROM produk WHERE category = ?";
}

// Eksekusi query
$stmt = $conn->prepare($sql);
if ($kategori !== "all") {
    $stmt->bind_param("s", $kategori);
}
$stmt->execute();
$result = $stmt->get_result();

// Tampilkan hasil
while ($row = $result->fetch_assoc()) {
    echo "Nama Produk: " . $row["name"] . "<br>";
    echo "Harga: Rp " . number_format($row["price"]) . "<br>";
    echo "Deskripsi: " . $row["description"] . "<br>";
    echo "<hr>";
}

$stmt->close();
$conn->close();
?>
