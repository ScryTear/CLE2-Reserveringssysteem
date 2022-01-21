<?php

$mysqli = new mysqli('localhost', 'root', '', ' meetings');

function build_calendar($month, $year){

    //First, create an array containing the names of all the days in a week

    $daysWeek = array('Monday', 'Tuesday', 'Wednesday','Thursday','Friday','Saturday','Sunday');

    //Then we get the first day of the month in the function

    $firstDay = mktime(0,0,0,$month);

    //number of days in a month
    $numberDays = date('t',$firstDay);

    //Getting some information about the first day of this month
    $dateComponents = getdate($firstDay);

    //Get the name of this month
    $monthName = $dateComponents['month'];

    //Get the index value of 0-6 of the first day of the month
    $dayWeek = $dateComponents['wday'];

    //The current date
    $dateToday = date('Y-m-d');

    //create the HTML table
    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2>";

    //make references to next month, previous month and this month
    $calendar.= "<a class='btn btn-xs btn-primary' href='?month=".date('m', mktime(0, 0, 0, $month-1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month-1, 1, $year))."'>Previous Month</a>";
    $calendar.= "<a class='btn btn-xs btn-primary' href='?month=".date('m')."&year=".date('Y')."'>Current Month</a> ";
    $calendar.= "<a class='btn btn-xs btn-primary' href='?month" .date('m', mktime(0, 0, 0, $month+1, 1, $year))."' &year='".date('Y', mktime(0, 0, 0, $month+1, 1, $year))."'>Next Month</a></center>";
    $calendar .="<tr>";


    //Create the calendar headers
    foreach($daysWeek as $day){
        $calendar .= "<th class = 'header'>$day</th>";
    }

    //Innitiate the day counter
    $currentDay = 1;

    $calendar .="</tr><tr>";

    //Make sure there are only 7 columns
    if($dayWeek > 0) {
        for($k=0;$k<$dayWeek;$k++){
            $calendar .= "<td class='empty'></td>";
        }
    }




    //Get the month number
    $month = str_pad($month,2,"0",STR_PAD_LEFT);

    while($currentDay <= $numberDays){

        //if seventh column reached, start a new row
        if($dayWeek == 7){
            $dayWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay,2,"0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayName = strtolower(date('1',strtotime($date)));
        $eventNum = 0;
        $today = $date==date('Y-m-d')? "today": "";

        // select current day
        if($dateToday==$date){
            $calendar .="<td class='today'><h4>$currentDay</h4>";;
        }else{
            $calendar .="<td><h4>$currentDay</h4>";
        }

        $calendar .= "</td>";

        //Incrementing the counters
        $currentDay++;
        $dayWeek++;

    }
    //Completing the last week of the month if necessary
    if($dayWeek != 7){

        $remainingDays = 7-$dayWeek;
        for($i=0;$i < $remainingDays;$i++){
           $calendar .="<td></td>";

        }

    }

    $calendar .="</tr>";
    $calendar .="</table>";

    echo $calendar;




}
?>

<html>
<head>
    <meta name="viewport" content="with=device-width, initial scales=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="calendar.css"/>
</head>
<body>
<div class="container"
     <div class="row">
         <div class="col-md-12">
            <?php
            $dateComponents=getdate();
            $month = $dateComponents['mon'];
            $year = $dateComponents['year'];
            echo build_calendar($month,$year);
            ?>
         </div>
     </div>
</div>
</body>
</html>
