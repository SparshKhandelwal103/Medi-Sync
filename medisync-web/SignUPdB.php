
<?php 
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dB="MediSync";
    $con= "";
    try{
        $con=mysqli_connect($servername,$username,$password,$dB);
    }
    catch(mysqli_sql_exception){
        echo "Could Not Connect to DataBase";
    }
    $email=$_POST["Email"];
    $FName=$_POST["FName"];
    $LName=$_POST["LName"];
    $Phone=$_POST["Phone"];
    $Address=$_POST["Address"];
    $City=$_POST["City"];
    $Pincode=$_POST["Pincode"];
    $Password=$_POST["Password"];

    $Insert="INSERT INTO userprofile (Email, FName, LName, Phone, City, Address, Pincode, Password) 
            Values('$email','$FName','$LName',$Phone,'$City','$Address',$Pincode,'$Password')";

    try{
        mysqli_query($con,$Insert);
        $message = "Account Created Successfully. You can now Log In";
        echo "<script>alert('$message');</script>";
        echo "<script>window.location.href = 'index.php';</script>"; 
    }
    catch(mysqli_sql_exception){
        $message = "Account already exists";
        echo "<script>alert('$message');</script>";
        echo "<script>window.location.href = 'SignUp.html';</script>";
    }
    mysqli_close($con);
?>