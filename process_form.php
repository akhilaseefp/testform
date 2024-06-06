<?php

$errors= array();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(empty($_POST["full_name"]))
    {
        $errors[]="Full name is required";
    }
    else
    {
        $full_name=test_input($_POST["full_name"]);
        if(!preg_match("/^[a-zA-Z]*$/",$full_name))
        {
            $errors[]="Only letters and white spaces allowed in full name";
        }
    }


    if(empty($_POST["phone_number"]))
    {
        $errors[]="Phone Number is required";
    }
    else
    {
        $phone_number=test_input($_POST["phone_number"]);
        if(!preg_match("/^[0-9]*$/",$phone_number))
        {
            $errors[]="Invalid Phone Number format";
        }
    }


    if(empty($_POST["email"]))
    {
        $errors[]="Email is required";
    }
    else
    {
        $email=test_input($_POST["email"]);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errors[]="Invalid Email format";
        }
    }


    if(empty($_POST["subject"]))
    {
        $errors[]="Subject is required";
    }
    else
    {
        $subject=test_input($_POST["subject"]);
    }


    if(empty($_POST["message"]))
    {
        $errors[]="Message is required";
    }
    else
    {
        $message=test_input($_POST["message"]);
    }


    if(empty($errors))
    {
        $token = md5(uniqid(rand(),true));
        $conn= new mysqli("localhost","root","","formdb");
        if($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO contact_form(full_name,phone_number,email,subject,message,token) VALUES ('$full_name','$phone_number','$email','$subject','$message','$token')";
        if($conn->query($sql)=== TRUE)
        {
            ini_set('SMTP', 'localhost');
            ini_set('smtp_port', 25);

            $to="akhilaseefp@gmail.com";
            $subject="New Form Submission";
            $message="Name: $full_name\nPhone Number: $phone_number\nEmail: $email\nSubject: $subject\nMessage: $message\n";
            $headers= "From: $email";

            mail($to,$subject,$message,$headers);
            echo "Form Submitted successfully";
        }
        else
        {
            echo "Error :". $sql."<br>" .$conn->error;
        }
        $conn->close();
    }
    else
    {
        foreach($errors as $error)
            {
                echo $error. "<br>";
            }
        echo "<a href='index.php'>Back to form</a>";
    }
}
else
{
    header("Location: index.php");
    exit();
}

function test_input($data)
{
    $data= trim($data);
    $data= stripslashes($data);
    $data= htmlspecialchars($data);
    return $data;

}
?>