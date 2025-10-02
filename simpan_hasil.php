<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST"); 

$host = "brmklltefai8500pw9va-mysql.services.clever-cloud.com";
$user = "u5dhutcyrle9lxrj";        // ganti sesuai setting MySQL kamu
$pass = "3AZLbunTMLrDkMLaBM81";            // ganti password MySQL kamu
$db   = "brmklltefai8500pw9va";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["result" => "error", "message" => "DB Connection failed"]);
    exit;
}

// Ambil JSON dari frontend
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["result" => "error", "message" => "Invalid JSON"]);
    exit;
}

$nama       = $conn->real_escape_string($data["Nama_Lengkap"]);
$kelas      = $conn->real_escape_string($data["Kelas"]);
$skor       = intval($data["Score_PG"]);
$skor_maks  = intval($data["Score_Max"]);
$jawaban    = $conn->real_escape_string(json_encode($data["Jawaban"]));

// Simpan ke DB
$sql = "INSERT INTO hasil_ujian (nama, kelas, skor, skor_maks, jawaban)
        VALUES ('$nama', '$kelas', $skor, $skor_maks, '$jawaban')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["result" => "success", "message" => "Data berhasil disimpan"]);
} else {
    http_response_code(500);
    echo json_encode(["result" => "error", "message" => $conn->error]);
}

$conn->close();
?>
