<html>
<head>
    <title>Php Database İşlemleri</title>
</head>
<body>
<?php
//Hata raporlamayı kapatır(uyarıları)----->  error_reporting(0);
error_reporting(0);
$servername = "localhost";
$username = "root";
$password = "";
$database = "student";

//veritabanı bağlantısı
try {
    $db = new PDO("mysql:host=$servername;dbname=student", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Bağlantı Başarılı";
} catch(PDOException $e) {
    echo "Bağlantı Hatası <br>:" . $e->getMessage();
}

?>


<br><b><i>******YENİ KAYIT EKLEME MENÜSÜ*******</i></b><br>
<br>
<form action="?islem=ekle" method="post">
    Öğrenci Numarası:<input type="text" name="Numara" required><br>
    <br>
    Ad Soyad: <input type="text" name="AdSoyad" required><br>
    <br>
    Bölümü: <select name="Bolum">
        <option>Bilgisayar Mühendisliği</option>
        <option>Biyomedikal Mühendisliği</option>
        <option>Endüstri Mühendisliği</option>
        <option>İnşaat Mühendisliği</option>
    </select><br>
    <br><input type="submit" value="Kaydet">
</form>



<?php

//EKLEME Komutları

if($_REQUEST['islem']=="ekle" && $_SERVER["REQUEST_METHOD"]=="POST")
{
    /*formdan çağırırken REQUEST*/
    $Numara=$_REQUEST['Numara'];
    $AdSoyad=$_REQUEST['AdSoyad'];
    $Bolum=$_REQUEST['Bolum'];
    $sql="INSERT INTO ogrenci (Numara,AdSoyad,Bolum) VALUES ('$Numara','$AdSoyad','$Bolum')";
    $db->exec($sql);
    echo "Ekleme Yapıldı <br>";
    /*header("Location : ?islem=eklendi");*/
}

//SİLME Komutları

if($_REQUEST['islem']=="sil")
{
    $id=$_REQUEST['id'];
    $sql="DELETE FROM ogrenci WHERE Id=$id";
    $db->exec($sql);
    /*header("Location : ?islem=silindi");*/
}

//GÜNCELLEME Komutları

if($_REQUEST['islem']=="guncelle" && $_SERVER["REQUEST_METHOD"]=="POST")
{
    $id=$_REQUEST['id'];
    $Numara=$_REQUEST['Numara'];
    $AdSoyad=$_REQUEST['AdSoyad'];
    $Bolum=$_REQUEST['Bolum'];
    $sql="UPDATE ogrenci SET Numara='$Numara', AdSoyad='$AdSoyad', Bolum='$Bolum' WHERE Id=$id";
    $db->exec($sql);
    echo "Güncelleme Yapıldı <br>";
    /*header("Location : ?islem=guncellendi");*/
}

?>

<br><b><i>******ÖĞRENCİ LİSTESİ*****</i></b><br>
<br>
<table border="1", width="600">
    <tr bgcolor="#1e90ff">
        <th>Id</th>
        <th>Öğrenci Numarası</th>
        <th>Adı Soyadı</th>
        <th>Bölüm</th>
        <th>Düzenle</th>
        <th>Sil</th>
        <th>Ders Ekle</th>
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
        <td><a href="?islem=duzenle&id=<?=$veri['Id']?>">DÜZENLE</a></td>
        <td><a href="?islem=sil&id=<?=$veri['Id']?>" onclick="return confirm('Silmek İstediğinizden Emin misiniz?');">SİL</a></td>
        <form action="DersEkle.php" method="post">
            <td><br><input type="submit" value="Ders Ekle"></td>
        </form>

    </tr>

    <?php } ?>
</table>
<?php

if($_REQUEST['islem']=="duzenle") {
    $id = $_REQUEST['id'];
    $sql = "SELECT * FROM ogrenci WHERE Id=$id";
    $sonuc = $db->prepare($sql);
    $sonuc->execute();
    $veri = $sonuc->fetch(PDO::FETCH_ASSOC);

    if ($sonuc->rowCount() > 0) {


        ?>
        <br><b><i>******KAYIT DÜZENLEME MENÜSÜ*******</i></b><br>
        <br>
        <form action="?islem=guncelle&id=<?=$veri['Id'] ?>" method="POST">
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
            </select><br>
            <br><input type="submit" value="Güncelle">
        </form>

        <?php
    }//Kayıt boş değilse
    else
    {
        echo "<br> Kayıt Bulunamadı";
    }
}
?>

</body>
</html>