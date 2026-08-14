<?php
include '../config.php';
include '../functions.php';
include '../head.php';
include '../script.php';

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $selectImage = "SELECT `image` FROM `patient` WHERE id = ?";
    $stmt = mysqli_prepare($conn, $selectImage);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row && !empty($row['image'])) {
        $file = "upload/" . $row['image'];
        if (is_file($file)) {
            unlink($file);
        }
    }

    $delete = "DELETE FROM `patient` WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    path("list.php");
}

$select = "SELECT * FROM `patient`";
$s = mysqli_query($conn, $select);
?>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<h1 class="text-center text-info  mt-2 pt-2">Patients History</h1>

<div class="container col-md-9">
    <form action="./search.php" method="get">
        <div class="row my-3">
            <div class="col-md-10">
                <div class="form-group ">
                    <input type="text" id="myInput" name="nameValue" class="form-control" placeholder="Search">
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-grid">
                    <button name="search" class="btn btn-info"> Search </button>
                </div>
            </div>
        </div>
    </form>
    <div class="card" style="
    background-color: #333 ;
    color: white;
    padding: 30px;
    box-shadow: 10px 10px 10px rgb(36, 8, 8);
">
        <div class="card-body" style="background-color: #666;
    color: white;">
            <table id="myTable" class="table table-dark">
                <tr>
                    <th> ID </th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Image</th>
                    <th colspan="2">Action</th>
                </tr>
                <?php foreach ($s as $data) :  ?>
                    <tr>
                        <td> <?= $data['id'] ?> </td>
                        <td> <?= $data['name'] ?> </td>
                        <td> <?= $data['age'] ?> </td>
                        <td>
                            <?php if (!empty($data['image'])) : ?>
                                <img width="50" src="./upload/<?= $data['image'] ?>" alt="">
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" style="min-width: 20px !important;">
                                    <li><a class="dropdown-item" href="edit.php?edit=<?= $data['id'] ?>"><i class="text-info fa-solid fa-pen-to-square"></i></a></li>
                                    <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure?')" href="list.php?delete=<?= $data['id'] ?>"><i class="text-danger fa-solid fa-trash"></i></a></li>
                                    <li><a class="dropdown-item" href="profile.php?show=<?= $data['id'] ?>"><i class="fa-solid fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;  ?>
            </table>
        </div>
    </div>
</div>