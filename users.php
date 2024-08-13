<?php
    require"database.php";


  
 

 if($_SERVER['REQUEST_METHOD']==="POST"){
   $rawPostData = file_get_contents('php://input');
   $postData = json_decode($rawPostData,true);

   if($postData)
   {
      $name = $postData['name'];
      $surname = $postData['surname'];
      $email = $postData['email'];
      $password = $postData['password'];
      $TCKN = $postData['tckn'];
      $telno = $postData['telno'];

      $query = "INSERT  INTO user (name,surname,email,password,tckn,telno) VALUES ('$name','$surname','$email','$password','$TCKN','$telno')";
      if(mysqli_query($conn,$query))
      {
         echo "Data İnserted Succesfully.";
      }  
   }


 }