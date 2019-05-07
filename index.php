<?php
session_start();
ini_set('display_errors', 1);
?>
<?php
require_once "connection.php";

$sql="CREATE TABLE IF NOT EXISTS bookings(
    id INT(6)UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50),
    surname VARCHAR(50),
    hotelname VARCHAR(50),
    indate VARCHAR(10),
    outdate VARCHAR(10),
    booked INT(4)
)";

if (!$conn->query($sql)) {
    echo $conn->error;
    exit;
}

 ?>
<!DOCTYPE html>
<html>
<head>
<style>
body {
  background-image: url("aspen.jpg");
  background-repeat:"repeat-x";
}
</style>
<link rel="stylesheet" href="css/main.css">
<link href="https://fonts.googleapis.com/css?family=Courgette|Kaushan+Script|Roboto|Satisfy" rel="stylesheet">

</head>
<body>
    <main role="main" class="container">
<h1>Aspen Holiday Booking</h1>
  <form action="index.php" method="POST"><br>
  <label for="firstname">First Name:</label><br>
  <input type="text" id="firstname" name="firstname" required><br>
  <label for="surname">Surname:</label><br>
  <input type="text" id="surname" name="surname" required><br>
  <label for="start">Check-in:</label><br>
  <input type="date" id="start" name="indate" min="2019-01-01" max="2020-01-01" required><br>
  <label for="end">Check-out:</label><br>
  <input type="date" id="end" name="outdate" min="2019-01-01" max="2020-01-01" required><br>
   <br><br>
   <select name="hotelname" required>
   <option value="Hotel Aspen">Hotel Aspen</option>
   <option value="Aspen Meadows Resort">Aspen Meadows Resort</option>
   <option value="Aspen Square Condonium">Aspen Square Condonium</option>
   <option value="Aspen Mountain Lodge">Aspen Mountain Lodge</option>
   </select>
  <button type="submit" name="submit">submit</button>
  </form>
 
 <?php
  if(isset($_POST['submit'])){
      $_SESSION['firstname']=$_POST['firstname'];
      $_SESSION['surname']=$_POST['surname'];
      $_SESSION['hotelname']=$_POST['hotelname'];
      $_SESSION['indate']=$_POST['indate'];
      $_SESSION['outdate']=$_POST['outdate'];
  }
//       echo "<br> Firstname:".  $_SESSION['firstname']."<br>".
// "surname:".  $_SESSION['surname']."<br>".
// "Check-in:". $_SESSION['indate']."<br>".
// "Check-out:". $_SESSION['outdate']."<br>".
// "Hotel Name:". $_SESSION['hotelname']."<br>";
// "Total R" . $value ."<form role='form' action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='post'>
// <button name='confirm' type='submit'> Confirm </button></form>"

$datetime1= new DateTime($_SESSION['indate']);
$datetime2= new DateTime($_SESSION['outdate']);
$interval=$datetime1->diff($datetime2);

$interval->format('%R%a days');

$daysBooked=$interval->format('%R%d days');
$value;

switch(isset($_POST['hotelname'])) {

    case 'Hotel Aspen':
        $value= $daysBooked * 2649;
        break;
        case 'Aspen Meadows Resort':
        $value= $daysBooked * 2849;
        break;
        case 'Aspen Square Condonium':
        $value= $daysBooked * 3539;
        break;
        case 'Aspen Mountain Lodge':
        $value= $daysBooked * 2484;
        break;    
    
    default:
        return "error";
        break;
}


echo "<br> Firstname:".  $_SESSION['firstname']."<br>".
"surname:".  $_SESSION['surname']."<br>".
"Check-in:". $_SESSION['indate']."<br>".
"Check-out:". $_SESSION['outdate']."<br>".
"Hotel Name:". $_SESSION['hotelname']."<br>".
"Total R" . $value ."<form role='form' action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='post'>
<button name='confirm' type='submit'> Confirm </button></form>";

if(isset($_POST['confirm'])){

    //Preparing and binding a statement
  
  //prepare is method, this way we only pass the query once and then the values
  $stmt=$conn->prepare("INSERT INTO bookings(firstname,surname,hotelname,indate,outdate) VALUES (?,?,?,?,?)");
  //also part of preparing & binding these values to the questions marks.
  $stmt=bind_param("sssss", $firstname,$surname,$hotelname,$indate,$outdate);
  $firstname=$_SESSION['firstname'];
  $surname=$_SESSION['surname'];
  $hotelname=$_SESSION['hotelname'];
  $indate=$_SESSION['indate'];
  $outdate=$_SESSION['outdate'];
  $stmt->execute();
  echo "Booking confirmed";
  }
  
 ?>
</body>
</html>