<?php
/*
echo"<h1> => merhaba  ECHO </h1>";
print"<h1> => merhaba  PRINT </h1>"

?>
<?= "<h1> => merhaba  php short tags </h1>"?>
<?php

$degisken="<h1> => abdullah duman </h1>";
echo $degisken;

?>
<?php
$sayi1=1;
$sayi2=2;
$sayi3=$sayi1+$sayi2;
echo "<h1>$sayi3</h1>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php

$metin="php de NELER yapilabilir";
echo $metin."<br>";

$kucuk=strtolower($metin);
echo $kucuk."<br>";

$buyuk=strtoupper($metin);
echo $buyuk."<br>";

$buyukharf=ucwords($metin);
echo $buyukharf."<br>";

$paragraf=ucfirst($metin);
echo $paragraf."<br><br><br>";

$deneme="merhaba PHP";
$deneme1="merhaba HTML";
$deneme2="merhaba CSS";
echo $deneme." ".$deneme1." ".$deneme2."<br><br>";

$ornek=array("php"=>"merhaba PHP","merhaba HTML","merhaba CSS");
echo $ornek["php"]." ".$ornek[0]." ".$ornek[1]."<br><br>";

$dizi[]="benim";
$dizi[]="adim";
$dizi[]="abdullah";
$dizi[]="duman";
echo $dizi[0]." ".$dizi[1]." ".$dizi[2]." ".$dizi[3]."<br><br>";

$dizi1=array("word1","word2","word3","word4","word5","word6");
for($i=0;$i<5;$i++)
{
   if($i<=5)
   {
    echo $dizi1[$i]."<br>";
   }
   
    
}

echo "<br><br><br><br>";
*/?>

<?php
// Veritabanı bağlantı bilgileri
$host = "localhost";      // Veritabanı sunucu adresi
$dbname = "demo";         // Veritabanı adı
$username = "root";       // MySQL kullanıcı adı (XAMPP için varsayılan)
$password = "";           // MySQL şifresi (XAMPP için varsayılan boş)

try {
    // PDO ile veritabanı bağlantısını oluştur
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Bağlantı hatalarını yakalayabilmek için
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Veri ekleme işlemi
    if (isset($_POST['ekle'])) {
        $ad = $_POST['ad'];
        $soyad = $_POST['soyad'];
        $departman = $_POST['departman'];
        $maas = $_POST['maas'];

        // Ekleme sorgusu
        $insert_sql = "INSERT INTO calisanlar (ad, soyad, departman, maas) VALUES (:ad, :soyad, :departman, :maas)";
        $stmt = $pdo->prepare($insert_sql);
        $stmt->bindParam(':ad', $ad);
        $stmt->bindParam(':soyad', $soyad);
        $stmt->bindParam(':departman', $departman);
        $stmt->bindParam(':maas', $maas);
        $stmt->execute();

        echo "<p class='success'>Yeni çalışan başarıyla eklendi!</p>";
    }

    // Silme işlemi
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        // Silme sorgusu
        $delete_sql = "DELETE FROM calisanlar WHERE id = :id";
        $stmt = $pdo->prepare($delete_sql);
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<p class='success'>Çalışan silindi! (ID: $delete_id)</p>";
    }

    // Güncelleme işlemi
    if (isset($_POST['guncelle'])) {
        $id = $_POST['id'];
        $ad = $_POST['ad'];
        $soyad = $_POST['soyad'];
        $departman = $_POST['departman'];
        $maas = $_POST['maas'];

        // Güncelleme sorgusu
        $update_sql = "UPDATE calisanlar SET ad = :ad, soyad = :soyad, departman = :departman, maas = :maas WHERE id = :id";
        $stmt = $pdo->prepare($update_sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':ad', $ad);
        $stmt->bindParam(':soyad', $soyad);
        $stmt->bindParam(':departman', $departman);
        $stmt->bindParam(':maas', $maas);
        $stmt->execute();

        echo "<p class='success'>Çalışan bilgileri güncellendi! (ID: $id)</p>";
    }

    // Veritabanından verileri çekmek için SQL sorgusu
    $sql = "SELECT id, ad, soyad, departman, maas FROM calisanlar";
    $stmt = $pdo->query($sql);  // SQL sorgusunu çalıştır

    // HTML tablosu başlat
    echo "<table class='styled-table'>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Departman</th>
                <th>Maaş</th>
                <th>Sil</th>
                <th>Güncelle</th>
            </tr>";

    // Verileri tabloya ekle
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['ad'] . "</td>
                <td>" . $row['soyad'] . "</td>
                <td>" . $row['departman'] . "</td>
                <td>" . $row['maas'] . "</td>
                <td><a href='?delete_id=" . $row['id'] . "' onclick='return confirm(\"Emin misiniz? Bu işlem geri alınamaz.\")'>Sil</a></td>
                <td><a href='?edit_id=" . $row['id'] . "'>Güncelle</a></td>
              </tr>";
    }

    // HTML tablosunu kapat
    echo "</table>";

    // Güncelleme formu
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];

        // Veritabanından güncellenecek veriyi al
        $edit_sql = "SELECT * FROM calisanlar WHERE id = :id";
        $stmt = $pdo->prepare($edit_sql);
        $stmt->bindParam(':id', $edit_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Formu göster
        echo "<h3>Çalışan Güncelle</h3>
              <form method='POST'>
                  <input type='hidden' name='id' value='" . $row['id'] . "'>
                  Ad: <input type='text' name='ad' value='" . $row['ad'] . "'><br>
                  Soyad: <input type='text' name='soyad' value='" . $row['soyad'] . "'><br>
                  Departman: <input type='text' name='departman' value='" . $row['departman'] . "'><br>
                  Maaş: <input type='text' name='maas' value='" . $row['maas'] . "'><br>
                  <input type='submit' name='guncelle' value='Güncelle'>
              </form>";
    }

} catch (PDOException $e) {
    // Hata durumunda mesaj ver
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>

<!-- Yeni Çalışan Ekleme Formu -->
<h3>Yeni Çalışan Ekle</h3>
<form method="POST">
    Ad: <input type="text" name="ad" required><br>
    Soyad: <input type="text" name="soyad" required><br>
    Departman: <input type="text" name="departman" required><br>
    Maaş: <input type="number" name="maas" step="0.01" required><br>
    <input type="submit" name="ekle" value="Ekle">
</form>

<!-- CSS STİLLERİ -->
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc;
        color: #333;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h3 {
        color: #2c3e50;
    }

    .styled-table {
        width: 80%;
        margin-top: 20px;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .styled-table th,
    .styled-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .styled-table th {
        background-color: #2980b9;
        color: white;
    }

    .styled-table tr:nth-child(even) {
        background-color: #ecf0f1;
    }

    .styled-table tr:hover {
        background-color: #d5dbdb;
    }

    .success {
        color: #27ae60;
        font-weight: bold;
    }

    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
        margin: 20px;
    }

    form input[type="text"],
    form input[type="number"] {
        width: 100%;
        padding: 10px;
        margin: 5px 0 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    form input[type="submit"] {
        background-color: #2980b9;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }

    form input[type="submit"]:hover {
        background-color: #3498db;
    }

    a {
        color: #298


</body>
</html>