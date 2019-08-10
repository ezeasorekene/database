<?php if (!isset($_SESSION)){ session_start(); }

if (isset($_GET['clear']) && ($_GET['clear']==="1")) {
  //Close any open MySQL connection
  mysqli_close($con);

  //Clear the session data
  session_unset($_SESSION['host']);
  session_unset($_SESSION['user']);
  session_unset($_SESSION['pass']);
  session_unset($_SESSION['con']);
  session_destroy();
  header("location: getdbinfo.php");
}

//Check if the host, username and password was specified
if (isset($_POST['host']) && isset($_POST['user']) && isset($_POST['pass'])) {
  $_SESSION['host'] = $_POST['host'];
  $_SESSION['user'] = $_POST['user'];
  $_SESSION['pass'] = $_POST['pass'];
  //Create a MySQL connection and keep it open
  $con = mysqli_connect($_SESSION['host'],$_SESSION['user'],$_SESSION['pass']);
  if ($con) {
    //if the connection was successful
    $_SESSION['con'] = 1;
  } else {
    //if the connection cannot be created
    echo "Either the username or password is incorrect or the host is unreachable.";
  }
}

//If the connection was successful, then show the databases the user has access to
if (isset($_SESSION['con']) && ($_SESSION['con']==1)) {
  $con = mysqli_connect($_SESSION['host'],$_SESSION['user'],$_SESSION['pass']);
  $sql = mysqli_query($con,"SHOW DATABASES");
  $result = mysqli_fetch_array($sql) or die(mysqli_error($con));
  $x = 1;
  //list all the databases
  do {
    echo $x." - ";
    echo $result[0];
    echo "<br>";
    $x++;
  } while ($result = mysqli_fetch_array($sql));
}

?>

<html>
<br><br>
<a href="?clear=1">Clear My Session Data</a>
<br><br>
<b>Show Databases:</b><br>
<form action="" method="post">
  Host: <input type="text" name="host" required>
  Username: <input type="text" name="user" required>
  Password: <input type="text" name="pass" required>
  <input type="submit" value="Get Databases">
</form>
<br>
<b>Show Tables:</b><br>
<form action="" method="post">
  Database: <input type="text" name="database" required>
  <input type="submit" value="Get Tables">
</form>
<br>
<b>Show Columns:</b><br>
<form action="" method="post">
  Database: <input type="text" name="database" required>
  Table: <input type="text" name="table" required>
  <input type="submit" value="Get Columns">
</form>
</html>


<?php
//check if any database was submitted
if (isset($_POST['database'])) {
  $database = $_POST['database'];
  echo "<br><br>-------Tables from <b>{$database}</b>-------<br><br>";
  $con = mysqli_connect($_SESSION['host'],$_SESSION['user'],$_SESSION['pass']);
  $sql = mysqli_query($con,"SHOW TABLES FROM $database");
  $result = mysqli_fetch_array($sql) or die(mysqli_error($con));
  $x = 1;
  //list all the database tables
  do {
    echo $x." - ";
    echo $result[0];
    echo "<br>";
    $x++;
  } while ($result = mysqli_fetch_array($sql));
}

//Check if the database and the table is submitted
if (isset($_POST['database']) && isset($_POST['table'])) {
  $database = $_POST['database'];
  $table = $_POST['table'];
  echo "<br><br>-------Columns from <b>{$database}</b> and <b>{$table}</b>-------<br><br>";
  $con = mysqli_connect($_SESSION['host'],$_SESSION['user'],$_SESSION['pass']);
  $sql = mysqli_query($con,"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table';");
  $result = mysqli_fetch_array($sql) or die(mysqli_error($con));
  $x = 1;
  //list all the columns
  do {
    echo $x." - ";
    echo $result[0];
    echo "<br>";
    $x++;
  } while ($result = mysqli_fetch_array($sql));
}

?>
