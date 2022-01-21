<?php

function build_calendar($month, $year)
{
    $mysqli = new mysqli('localhost', 'root', '', 'reservations');
    $stmt = $mysqli->prepare("select * from meetings where MONTH(date) = ? AND YEAR(date)=?");
    $stmt->bind_param('ss', $month, $year);
    $meetings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $meetings[] = $row['date'];
            }
            $stmt->close();
        }
    }

    //First, create an array containing the names of all the days in a week
    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

    //Then we get the first day of the month in the function
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

    //number of days in a month
    $numberDays = date('t', $firstDayOfMonth);

    //Getting some information about the first day of this month
    $dateComponents = getdate($firstDayOfMonth);

    //Get the name of this month
    $monthName = $dateComponents['month'];

    //Get the index value of 0-6 of the first day of the month
    $dayOfWeek = $dateComponents['wday'];

    //The current date
    $datetoday = date('Y-m-d');

    //make references to next month, previous month and this month
    $prevMonth = date('m', mktime(0, 0, 0, $month-1, 1, $year));
    $prevYear = date('Y', mktime(0, 0, 0, $month-1, 1, $year));
    $nextMonth = date('m', mktime(0, 0, 0, $month+1, 1, $year));
    $nextYear = date('Y', mktime(0, 0, 0, $month+1, 1, $year));
    $calendar = "<center><h2>$monthName $year</h2>";

    $calendar .= "<a class='btn btn=primary btn-xs' href='?month=".$prevMonth."&year=".$prevYear."'>prev Month</a> ";
    $calendar .= "<a class='btn btn=primary btn-xs' href='?month=".date('m')."&year=".date('Y')."'>Current Month </a> ";
    $calendar .= "<a class='btn btn=primary btn-xs' href='?month=".$nextMonth."&year=".$nextYear."'>next Month </a></center>";
    $calendar .= "<table class='table table-bordered'>";
    $calendar .= "<tr>";
    foreach($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    }

    $calendar .= "</tr><tr>";
    $currentDay = 1;
    if($dayOfWeek > 0) {
        for($k=0;$k<$dayOfWeek;$k++){
            $calendar .= "<td class='empty'></td>";
        }
    }

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    while ($currentDay <= $numberDays) {
        //Seventh column (Saturday) reached. Start a new row.
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayName = strtolower(date('l', strtotime($date)));

        $today = $date==date('Y-m-d')? 'today' : '';

        // make buttons that will not be interactive after a certain date (yesterday) and booking for dates that are higher than yesterday
        // that way, you will only be able to book on dates that seem logical
        if($date<date('Y-m-d')){
            $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>N/A</button>";
        }elseif (in_array($date, $meetings)){
            $calendar.="<td class='$today'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>Already Booked</button>";
        }
        else{
            $calendar.="<td class='$today'><h4>$currentDay</h4> <a href='bookMeeting.php?date=".$date."' class='btn btn-success btn-xs'>Book</a>";
        }
        //Increment counters
        $currentDay++;
        $dayOfWeek++;
    }
//Complete the row of the last week in month, if necessary
    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for($l=0;$l<$remainingDays;$l++){
            $calendar .= "<td></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";


    return $calendar;


}
?>

<html>
<body>
<head>
    <meta name="viewport" content="with=device-width, initial scales=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="calendar.css"/>
</head>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="calendar">
                <?php
                $dateComponents = getdate();

                if(isset($_GET['month']) && isset($_GET['year'])){
                    $month = $_GET['month'];
                    $year = $_GET['year'];
                }else{
                    $month = $dateComponents['mon'];
                    $year = $dateComponents['year'];
                }
                echo build_calendar($month,$year);
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
