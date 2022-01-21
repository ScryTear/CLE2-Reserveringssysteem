<?php

if(isset($_GET['date'])){
    $date = $_GET['date'];
}

//stuur het door naar de database
if(isset($_POST['submit'])){
    $mysqli = new mysqli('localhost', 'root', '', 'reservations');
    $stmt = $mysqli->prepare("INSERT INTO meetings (user_id, location, notes, date) VALUES (?,?,?,?)");
    $stmt->bind_param('ssss', $name, $location, $notes, $date);
    $name=$_POST['name'];
    $location=$_POST['location'];
    $notes=$_POST['notes'];
    $stmt->execute();
    $msg="<div class='alert alert-success'>Booking Successfull</div>";
    $stmt->close();
    $mysqli->close();
}

//create timeslots, because a meeting can't (mostly)

$duration = 15;
$cleanup = 5;
$start = "07:00";
$end = "16:30";

function timeslots($duration, $cleanup, $start, $end){
    $start = new DateTime($start);
    $end = new DateTime($end);
    $interval = new DateInterval("PT".$duration."M");
    $cleanupInterval = new DateInterval("PT".$cleanup."M");
    $slots = array();

    for($intStart = $start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
        $endPeriod = clone $intStart;
        $endPeriod->add($interval);
        if($endPeriod>$end){
            break;
        }

        $slots[] = $intStart->format("H:iA")." - ". $endPeriod->format("H:iA");

    }

    return $slots;



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
        <div class="col-md-12">
            <?php echo(isset($msg))?$msg:""; ?>
        </div>
        <?php $timeslots = timeslots($duration, $cleanup, $start, $end);
        foreach($timeslots as $ts){
            ?>
            <div class="col-md-2">
                <div class="form-group">
                    <button class="btn btn-success book" <?php echo $ts; ?>"><?php echo $ts; ?></button>


                </div>
            </div>
        <?php } ?>
    </div>

    </div>
</div>
</body>
</html>


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

