<?php
include "config.php";

$errors = [];
$name = $email = $phone ="";

$id= isset($_GET['id']) ? $_GET['id'] : null;

if (!isset($id) || !is_numeric($id))
{
die("invalid student id");
}
$stmt = $connection->prepare("SELECT * FROM students  WHERE id = '$id'");
$stmt->execute();

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($student))
    {
        die ("enter Valid student id");
    }

$name = $student['name'];
$email = $student['email'];
$phone = $student['phone'];



if (isset($_POST['submit'])){
   $name = trim($_POST['name']);
   $email = trim($_POST['email']);
   $phone = trim($_POST['phone']);

   if ($name == ""){
    $errors[] = "name filed is required";

   }
   
   if ($email == ""){
    $errors[] ="email filed is required";
   }
   
   elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
         $errors[] ="invalid email format";

   }

   if($email != ""){
    $stmt = $connection->prepare("SELECT   email from students WHERE email = '$email' AND id != '$id' ");
    $stmt->execute();
    if($stmt->fetch()){
        $errors[] = "email aready exist";
    }
   }



      if($phone != ""){
    $stmt = $connection->prepare("SELECT   phone from students WHERE phone = '$phone' AND id != '$id' ");
    $stmt->execute();

    if($stmt->fetch()){
        $errors[] = "phone aready exist ";
    }
   }



   if (empty($errors)){
    $stmt = $connection->prepare("UPDATE students SET name =:name, email = :email, phone = :phone WHERE id = :id");
    $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'id' => $id]);

    header("location: index.php");
    exit;
}
}
?>

<h3> edit student form </h3>

<?php
if(!empty($errors)){
    foreach ($errors as $err){
        echo "<p style='color:red'>$err</p>";
    }
}
?>



?>

<?php
if(!empty($errors) ){
    foreach($errors as $error){
        echo "<div class='alert alert-danger'>$error</div>";
    }
}
?>



<form method="POST">
    <div class="form-group">
        <label>Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" placeholder=" Enter your name"  value="<?= htmlspecialchars($name) ?>">
    </div>

    <div class="form-group">
        <label>Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" name="email" placeholder=" Enter your email" value="<?= htmlspecialchars($email) ?>">
    </div>

    <div class="form-group">
        <label>Phone</label>
        <input type="text" class="form-control" name="phone" placeholder=" Enter your phone" value="<?= htmlspecialchars($phone) ?>">
    </div>

    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>