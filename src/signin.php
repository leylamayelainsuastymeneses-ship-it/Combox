<?php

//get data base connection
require('../config/database.php');

session_start();

if(isset($_SESSION['userId'])){
     header('refresh:0;url=signin.html'); 
}

//get data from html form (client)

$e_mail = $_POST['email'];
$p_assword = $_POST['pswd'];
$pass_enc = md5($p_assword);

//query to search user into database

$sql ="
      SELECT 
      u.* 
      FROM 
      users u
      WHERE 
      email = '$email' and 
      password ='$pass_enc'";

      
$res = pg_query($supa_conn, $sql); 
if(pg_num_rows ($res) > 0){
    $row = pg_fetch_assoc($res);
   
    $_SESSION['userId'] = $row['id'];
    $_SESSION['fname'] = $row['firstname'];
    $_SESSION['lname'] = $row['lastname'];
      

  header('refresh:0;url=home.php'); 
}else{
    echo"<script>alert('Invalid email or password :::')</script>"; 
    header('refresh:0;url=signin.html'); 
}