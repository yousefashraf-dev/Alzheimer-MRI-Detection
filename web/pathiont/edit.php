<?php
include '../config.php';
include '../functions.php';
include '../head.php';
include '../script.php';

$row = ['name' => '', 'age' => '', 'image' => ''];

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $select = "SELECT * FROM `patient` WHERE id = ?";
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['send'])) {
    $id = (int) $_GET['edit'];
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);

    $image_name = $row['image'];
    if (!empty($_FILES['image']['name'])) {
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($extension, $allowed)) {
            $image_name = rand(0, 90000000) . '.' . $extension;
            $location = "upload/" . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $location);

            if (!empty($row['image']) && $row['image'] !== $image_name) {
                $old_file = "upload/" . $row['image'];
                if (is_file($old_file)) {
                    unlink($old_file);
                }
            }
        }
    }

    $update = "UPDATE `patient` SET `name` = ?, `age` = ?, `image` = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt, "sisi", $name, $age, $image_name, $id);
    mysqli_stmt_execute($stmt);

    path("list.php");
}
?>

<h1 class="text-center text-info  mt-2 pt-2">Edit Patient</h1>
<div class="container col-md-6">
    <div class="card">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" value="<?= htmlspecialchars($row['name']) ?>" class="form-control" name="name">
                </div>
                <div class="form-group">
                    <label for="">Age</label>
                    <input type="text" value="<?= htmlspecialchars($row['age']) ?>" class="form-control" name="age">
                </div>
                <div class="form-group">
                    <label for="">Image :
                        <?php if (!empty($row['image'])) : ?>
                            <span><img width="50" src="./upload/<?= $row['image'] ?>" alt=""></span>
                        <?php endif; ?>
                    </label>
                    <input type="file" class="form-control" name="image">
                </div>
                <button name="send" class="btn btn-danger m-3"> Update Patient </button>
            </form>
        </div>
    </div>
</div>