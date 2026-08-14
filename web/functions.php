<?php

function testMessage($condition, $message)
{
    if ($condition) {
        echo "<div class='alert alert-success col-5 mx-auto'>$message Successfully</div>";
    } else {
        echo "<div class='alert alert-danger col-5 mx-auto'>$message Failed</div>";
    }
}

function path($go)
{
    echo "<script> window.location.replace('$go') </script>";
}
