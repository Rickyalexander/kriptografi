<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriptografi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            padding: 20px;
            background-color: #f4f4f4;
            margin: 20px auto;
            width: 50%;
            border-radius: 8px;
        }
        textarea, input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
        }
        fieldset {
            border: 1px solid #ddd;
            padding: 10px;
        }
    </style>
</head>
<body>
<h1>Kriptografi</h1>
<label>By Pirmansyah 211011400604</label>
<form action="" method="post">
    <fieldset>
        <legend>Form Kriptografi</legend>
        <label>Pesan:</label>
        <textarea name="pesan" cols="70" rows="4" required></textarea>
        <label>Kunci:</label>
        <input type="text" name="kunci" required>
        <label>Proses:</label>
        <select name="proses">
            <option value="E">Enkripsi</option>
            <option value="D">Dekripsi</option>
        </select>
        <input type="submit" name="submit" value="Proses">
    </fieldset>
</form>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil dan memfilter input
    $pesan = htmlspecialchars($_POST["pesan"]);
    $kunci = htmlspecialchars($_POST["kunci"]);
    $proses = $_POST["proses"];

    // Pastikan kelas hanya di-deklarasikan sekali
    if (!class_exists("KripRC4")) {
        class KripRC4 {
            private $kunci;
            private $S;
            private $K;

            public function setKunci($n) {
                $this->kunci = $n;
            }

            public function iniArrayS() {
                for ($i = 0; $i < 256; $i++) {
                    $S[$i] = $i;
                }
                $this->S = $S;
            }

            public function iniArrayK() {
                $key = $this->kunci;
                for ($i = 0; $i < 256; $i++) {
                    $K[$i] = ord($key[$i % strlen($key)]);
                }
                $this->K = $K;
            }

            public function acakSBox() {
                $S = $this->S;
                $K = $this->K;
                $j = 0;
                for ($i = 0; $i < 256; $i++) {
                    $j = ($j + $S[$i] + $K[$i]) % 256;
                    $temp = $S[$i];
                    $S[$i] = $S[$j];
                    $S[$j] = $temp;
                }
                $this->S = $S;
            }

            public function pseudoRandomByte($pesan) {
                $S = $this->S;
                $i = 0;
                $j = 0;
                $key = [];

                for ($p = 0; $p < strlen($pesan); $p++) {
                    $i = ($i + 1) % 256;
                    $j = ($j + $S[$i]) % 256;
                    $temp = $S[$i];
                    $S[$i] = $S[$j];
                    $S[$j] = $temp;
                    $t = ($S[$i] + $S[$j]) % 256;
                    $key[] = $S[$t];
                }

                return $key;
            }

            public function prosesXOR($pesan, $kunci) {
                $hasil = '';
                for ($i = 0; $i < strlen($pesan); $i++) {
                    $hasil .= chr(ord($pesan[$i]) ^ $kunci[$i]);
                }
                return $hasil;
            }

            public function EDkripsi($pesan, $status) {
                $this->iniArrayS();
                $this->iniArrayK();
                $this->acakSBox();
                $kunci = $this->pseudoRandomByte($pesan);
                $hasil = $this->prosesXOR($pesan, $kunci);
                echo "<fieldset><legend>Hasil:</legend><p>" . ($status == "E" ? "Enkripsi" : "Dekripsi") . ": $hasil</p></fieldset>";
            }
        }
    }

    // Membuat objek baru dan melakukan proses enkripsi atau dekripsi
    $obj = new KripRC4();
    $obj->setKunci($kunci);

    if ($proses == "E") {
        $obj->EDkripsi($pesan, "E"); // Enkripsi
    } else {
        $obj->EDkripsi($pesan, "D"); // Dekripsi
    }
}
?>

</body>
</html>
