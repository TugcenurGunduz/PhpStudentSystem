<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ders Ekleme Sayfası</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "student";

error_reporting(0);

//veritabanı bağlantısı
try {
    $db = new PDO("mysql:host=$servername;dbname=student", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Bağlantı Başarılı";
} catch(PDOException $e) {
    echo "Bağlantı Hatası <br>:" . $e->getMessage();
}

?>
<table border="1", width="400" >
    <tr bgcolor="#e9967a">
        <th>DersID</th>
        <th>DersAd</th>
        <th>Bölüm</th>
        <th>Bölüm Id</th>

    </tr>
    <?php
    $sql="SELECT * FROM dersler";

    foreach ($db->query($sql) as $veri) //veri tabanından çağırırken
    {
        ?>
        <tr>
            <td><?=$veri['DersID']?></td>
            <td><?=$veri['DersAd']?></td>
            <td><?=$veri['Bolum']?></td>
            <td><?=$veri['BolumId']?></td>

        </tr>

    <?php } ?>
</table>
<br>

<table border="1", width="600">
    <tr bgcolor="#1e90ff">
        <th>Id</th>
        <th>Öğrenci Numarası</th>
        <th>Adı Soyadı</th>
        <th>Bölüm</th>
        <th>Bölüm Id</th>
        <th>Ders Ekle</th>
        <th>Dersler</th>
    </tr>

<?php
$sql="SELECT * FROM ogrenci";

foreach ($db->query($sql) as $veri) //veri tabanından çağırırken
{
    ?>
    <tr>
        <td><?=$veri['Id']?></td>
        <td><?=$veri['Numara']?></td>
        <td><?=$veri['AdSoyad']?></td>
        <td><?=$veri['Bolum']?></td>
        <td><?=$veri['BolumId']?></td>
        <form action="DersEkle.php" method="post">
            <td><a href="?islem=DersEkle&id=<?=$veri['Id']?>">DERS EKLE</a></td>
        </form>
        <td><?=$veri['Dersler']?></td>



    </tr>

<?php } ?>
    <?php
    if($_REQUEST['islem']=="DersEkle") {
        $id = $_REQUEST['id'];
        $sql = "SELECT * FROM ogrenci WHERE Id=$id";
        $sonuc = $db->prepare($sql);
        $sonuc->execute();
        $veri = $sonuc->fetch(PDO::FETCH_ASSOC);

        if ($sonuc->rowCount() > 0) {

        }//Kayıt boş değilse
        else
        {
            echo "<br> Kayıt Bulunamadı";
        }
    }


    ?>
    <?php

    //EKLEME Komutları

    if($_REQUEST['islem']=="ekle" && $_SERVER["REQUEST_METHOD"]=="POST")
    {
        /*formdan çağırırken REQUEST*/
        $Numara=$_REQUEST['Numara'];
        $AdSoyad=$_REQUEST['AdSoyad'];
        $Bolum=$_REQUEST['Bolum'];
        $Dersler=$_REQUEST['Dersler'];
        $sql="INSERT INTO ogrenci (Numara,AdSoyad,Bolum,Dersler) VALUES ('$Numara','$AdSoyad','$Bolum','$Dersler')";
        $db->exec($sql);
        echo "<b>Ekleme Yapıldı! Lütfen Sayfayı Yenileyin <br>";
        /*header("Location : ?islem=eklendi");*/
    }
    ?>
    <b><i>******DERS EKLEME MENÜSÜ*******</i></b><br>
    <br>
    <form action="?islem=ekle&id=<?=$veri['Id'] ?>" method="post">
        Öğrenci Numarası:<input type="text" name="Numara" value="<?=$veri['Numara'] ?>" required><br>
        <br>
        Ad Soyad: <input type="text" name="AdSoyad" value="<?=$veri['AdSoyad'] ?>" required><br>
        <br>
        Bölümü: <select name="Bolum">
            <option><?=$veri['Bolum'] ?></option>
            <option>Bilgisayar Mühendisliği</option>
            <option>Biyomedikal Mühendisliği</option>
            <option>Endüstri Mühendisliği</option>
            <option>İnşaat Mühendisliği</option>
        </select><br><br>
        Dersler: <select name="Dersler"><br>

            <option>FOL101</option>
            <option>CME201</option>
            <option>EMG304</option>
            <option>GME402</option>

        </select><br>
        <br><input type="submit" value="Ekle"><br>
        <br>


    </form>
    <?php


?>

</body>
</html>

