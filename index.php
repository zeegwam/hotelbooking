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

if (isset($_GET['error']) && $_GET['error'] == 'timestamp') {
?>
<div class='panel panel-default'>
        <p>
            You must select at least  1 day 
        </p>
</div>
<?php
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

$checkInStamp = strtotime($_SESSION['indate']);
$checkOutStamp = strtotime($_SESSION['outdate']);

if ($checkInStamp - $checkOutStamp > 86400 || $checkInStamp == $checkOutStamp) {
    header("Location: ?error=timestamp");
    exit;
}

$daysBooked=$interval->format('%d');

switch(isset($_SESSION['hotelname'])) {

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

$firstname = $_POST['firstname'];
$surname = $_POST['surname'];

$result = mysqli_query($conn,"SELECT hotelname, indate, outdate, firstname, surname FROM bookings WHERE firstname='$firstname' && surname='$surname'"); 

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {    
 echo "<div class='feedback'> You already have a booking. <br> Firstname: ". $row['firstname'] . "<br>
Surname: " . $row['surname'].
"<br> Start Date: " . $row['indate'].
"<br> End Date: " . $row['outdate'].
"<br> Hotel Name: " . $row['hotelname'].
"<br>" . $interval->format('%r%a days') . "<br> Total: R " . $value ."</div>";
    } 
}

echo "<div id='element'><br> Firstname:".  $_SESSION['firstname']."<br>".
"Surname:".  $_SESSION['surname']."<br>".
"Check-in:". $_SESSION['indate']."<br>".
"Check-out:". $_SESSION['outdate']."<br>".
"Hotel Name:". $_SESSION['hotelname']."<br>".
"Total R" . $value ;'</div>';

echo "<form role='form' action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='post'>
<button name='confirm' type='submit'> Confirm </button> </form>".'</div>';
}
if(isset($_POST['confirm'])){
    
    $stmt=$conn->prepare("INSERT INTO bookings(firstname,surname,hotelname,indate,outdate) VALUES (?,?,?,?,?)");
    $stmt->bind_param('sssss', $firstname,$surname,$hotelname,$indate,$outdate);
    $firstname=$_SESSION['firstname'];
    $surname=$_SESSION['surname'];
    $hotelname=$_SESSION['hotelname'];
    $indate=$_SESSION['indate'];
    $outdate=$_SESSION['outdate'];
    $stmt->execute();
    echo '<div id="confirmed">'."Booking confirmed".'</div>';
    
    }
  
 ?>
</body>
</html>
<style>
div#confirmed{
    color:red;
    font-size:20px;
}

div#element{
    color:white;
    font-size:20px;
}
    </style>