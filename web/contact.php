<?php

include 'config.php';

if (isset($_POST['result'])) {
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $gender = $_POST['gender'] ?? 'Male';
    $birthday = $_POST['birthday'] ?? '';

    $image_name = null;
    if (!empty($_FILES['image']['name'])) {
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($extension, $allowed)) {
            $image_name = rand(0, 90000000) . '.' . $extension;
            $location = "pathiont/upload/" . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $location);
        }
    }

    $insert = "INSERT INTO `patient`(`name`, `age`, `gender`, `birthday`, `image`)
               VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert);
    mysqli_stmt_bind_param($stmt, "sisss", $name, $age, $gender, $birthday, $image_name);
    mysqli_stmt_execute($stmt);

    path("pathiont/list.php");
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>contact</title>
  <meta name="description" content="">
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/hom.css">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Piazzolla:ital,wght@1,100&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://kit.fontawesome.com/63f550a101.js" crossorigin="anonymous"></script>

</head>

<body style="background-image: url(./css/img/img-bg.png)">


  <header class="main">

    <nav class="navbar navbar-expand-lg navbar-light bg">
      <a class="" href="home.php"><i class="fa-solid fa-brain" style="color: white; padding-left: 18px;"></i></a>


      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr">
          <li class="nav-item active">
            <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="about.php">About <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="./pathiont/list.php">History<span class="sr-only">(current)</span></a>
          </li>

        </ul>

      </div>

      <a href="loginn.php" class="btn btn-light" style=" border-radius: 15px;">Sign out</a>
      


    </nav>
  </header>

 
<section class="contactt" style="padding-top: 5%;">
    
 <form style="padding: 0 20px 0 41px;" method="post" enctype="multipart/form-data">
      <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-4 col-sm-12">
          <div class="over" style=" width: 400px;  height: 300px; padding-top: 20px;">
            <div class="text">
              <h3>Upload MRI Image for detect AD or Not </h3> <br><br>
              <div class="form-group" style="border-radius: 20px;"> 
              <div class="input-group">
                                    <div class="custom-file" style="border-radius: 50px;">
                                      <input type="file" class="form-control" id="inputGroupFile04" name="image" aria-describedby="inputGroupFileAddon04">
                                      <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                    </div><br><br>
                                  </div><br>
               </div>

            </div>
          </div> <!----AD OR NOT --->
         </div>


            <div class="col-xl-6 col-lg-6 col-md-4 col-sm-12" style="text-align: center;">
          <!---  <div class="over" style=" width:500px;  height: 400px; padding-top: 20px;">    ---->
           <h3>Please Fill This Information </h3> <br><br>
            <div class="form-group row">
              <label for="inputName" class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="name" name="name" class="form-control" id="inputName" style="border-radius: 50px; width: 50%;">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputage" class="col-sm-2 col-form-label">Age</label>
              <div class="col-sm-10">
                <input type="age"  name="age" class="form-control" id="inputAge" style="border-radius: 50px; width: 50%;">
              </div>
            </div>
            <div class="form-group" style="padding: 0 20px 0 7px;">
              <div class="row">
                <legend class="col-form-label col-sm-2 pt-0" style="padding-left: 8px;padding-right: 6px;">Gender</legend>
                <div class="col-sm-10" style="padding-left: 0;">
                  <div class="form-check" style=" width: 50%; padding-left: 0;">
                    <input class="form-check-input" type="radio" name="gender" id="gridRadios1" value="Male" checked>
                    <label class="form-check-label" for="gridRadios1">
                      Male
                    </label>
                  </div>
                  <div class="form-check" style=" width: 50%;">
                    <input class="form-check-input" type="radio" name="gender" id="gridRadios2" value="Female">
                    <label class="form-check-label" for="gridRadios2">
                      Female
                    </label>
                  </div>

                </div>
              </div>

            </div>
             <input type="date" name="birthday" placeholder="date" required style="text-align: center;border-radius: 9px; "><br><br>
            <div class="result">
            
            <button name="result"class="btn btn-info m-3" type="submit" id="inputGroupFileAddon04" style="color: black;  border-radius: 15px;"> Result</button>
          </div>



          
          <!---</div>-->



        </div>
      </div>
    </div>


  </section>
</form>

</body>

</html>
<?php
include './script.php';
?>