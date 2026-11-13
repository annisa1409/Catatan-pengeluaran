<?php
$conn = mysqli_connect("localhost", "root", "", "pengeluaran");

if(isset($_GET['hapus'])){
    mysqli_query($conn, "DELETE FROM catatan WHERE id=$_GET[hapus]");
    header("Location: index.php"); exit;
}

if(isset($_POST['simpan'])){
    $ket = $_POST['keterangan'];
    $jml = $_POST['jumlah'];
    $tgl = $_POST['tanggal'];

    if($_POST['id'] == ''){
        mysqli_query($conn, "INSERT INTO catatan (keterangan, jumlah, tanggal) VALUES('$ket','$jml','$tgl')");
    } else {
        mysqli_query($conn, "UPDATE catatan SET keterangan='$ket', jumlah='$jml', tanggal='$tgl' WHERE id=$_POST[id]");
    }
    header("Location: index.php"); exit;
}

$edit = ['id'=>'','keterangan'=>'','jumlah'=>'','tanggal'=>''];
if(isset($_GET['edit'])){
    $edit = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM catatan WHERE id=$_GET[edit]"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran Harian</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>💰 Catatan Pengeluaran Harian</h2>

    <div class="card">
        <form method="post">
            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
            
            <label>Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" value="<?= $edit['keterangan'] ?>" placeholder="" required>

            <div class="row">
                <div>
                    <label>Harga (Rp)</label>
                    <input type="number" name="jumlah" value="<?= $edit['jumlah'] ?>" placeholder="" required>
                </div>
                <div>
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="<?= $edit['tanggal'] ?>" required>
                </div>
            </div>

            <br>
            <button name="simpan" class="btn">
                <?= $edit['id'] ? 'Update Data' : 'Simpan Data' ?>
            </button>
            <?php if($edit['id']): ?>
                <a href="index.php" class="btn btn-batal">Batal</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no=1; $total=0;
                $q = mysqli_query($conn, "SELECT * FROM catatan ORDER BY id DESC");
                while($d = mysqli_fetch_array($q)){ 
                    $total += $d['jumlah']; 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= $d['keterangan'] ?></strong></td>
                    <td class="text-harga">Rp <?= number_format($d['jumlah'],0,',','.') ?></td>
                    <td><?= $d['tanggal'] ?></td>
                    <td>
                       <a href="?edit=<?= $d['id'] ?>" class="aksi edit" onclick="return editData()">Edit</a>
                        <a href="?hapus=<?= $d['id'] ?>" class="aksi hapus" onclick="return hapusData()">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Total Pengeluaran:</td>
                    <td colspan="3" class="text-harga">Rp <?= number_format($total,0,',','.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>