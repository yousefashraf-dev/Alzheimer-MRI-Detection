<?php
include '../config.php';
include '../functions.php';
include '../head.php';
include '../script.php';

$row = null;

if (isset($_GET['show'])) {
    $id = (int) $_GET['show'];
    $select = "SELECT * FROM `patient` WHERE id = ?";
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
}
?>
<h1 class="text-center text-info  mt-2 pt-2">Patient Profile</h1>

<div class="container col-md-5">
    <div class="card">
        <?php if ($row && !empty($row['image'])) : ?>
            <img src="./upload/<?= $row['image'] ?>" class="img-top" alt="">
        <?php endif; ?>
        <div class="card-body">
            <?php if ($row) : ?>
                Name : <?= htmlspecialchars($row['name']) ?>
                <hr>
                Age : <?= htmlspecialchars($row['age']) ?>
                <hr>
                Gender : <?= htmlspecialchars($row['gender'] ?? '-') ?>
                <hr>
                Birthday : <?= htmlspecialchars($row['birthday'] ?? '-') ?>
                <hr>
                <a class="btn btn-info" href="edit.php?edit=<?= $row['id'] ?>">Edit</a>
                <a onclick="return confirm('Are you sure?')" class="btn btn-danger" href="list.php?delete=<?= $row['id'] ?>">Remove</a>
            <?php else : ?>
                <p class="text-center">No patient found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>