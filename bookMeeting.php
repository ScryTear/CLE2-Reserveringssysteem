<?php
if(isset($_GET['date'])){
    $date = $_GET['date'];
}

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $mysqli = new mysqli('localhost', 'root', '', 'reservations');
    $stmt = $mysqli->prepare("INSERT INTO meetings (name, date) VALUES (?,?)");
    $stmt->bind_param('sss', $name, $date);
    $stmt->execute();
    $msg="<div class='alert alert-success'>Booking Successfull</div>";
    $stmt->close();
    $mysqli->close();
}
?>

<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
</head>

<body>
<div class="container">
    <h1>Book for Date: <?php echo date('m/d/Y', strtotime($date)); ?></h1>
    <hr>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?php echo isset($msg)?$msg:'';?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group">
                    <label for="">Location</label>
                    <input type="text" class="form-control" name="location">
                </div>
                <div class="form-group">
                    <label for="">Notes</label>
                    <input type="text" class="form-control" name="notes">
                </div>
                <button class="btn btn-primary" type="submit" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

