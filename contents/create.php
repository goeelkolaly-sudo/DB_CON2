<?php
include "config.php";

$errors = [];
$imageName = null;
$name = $email = $phone = $departmentId = "";

$stmt = $connection->prepare("SELECT * FROM departments");
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $departmentId = trim($_POST['department_id']);

    if ($name == "") {
        $errors[] = "name filed is required";
    }
    if ($email == "") {
        $errors[] = "email filed is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "invalid email format";
    }

    if ($email != "") {
        $stmt = $connection->prepare("SELECT email from students where email = '$email'");
        $stmt->execute();
        if ($stmt->fetch()) {
            $errors[] = "email aready exist";
        }
    }


    if ($phone != "") {
        $stmt = $connection->prepare("SELECT phone from students where phone = '$phone'");
        $stmt->execute();
        if ($stmt->fetch()) {
            $errors[] = "phone aready exist";
        }
    }

    if ($departmentId == "") {
        $errors[] = "departmentId filed is required";
    }

    if ($phone == "") {
        $phone = null;
    }

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . $_FILES['image']['name'];
        $imagePath= "uploads/" . $imageName ;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath );
    }


    //// لما يعدي كل الي فوق
    if (empty($errors)) {

        $stmt = $connection->prepare("INSERT INTO students (name, email, phone, department_id, image) VALUES ('$name', '$email','$phone','$departmentId', '$imagePath')");
        $stmt->execute();
        header("location: index.php");
        exit;
    }
}
?>

<h3>Add Student</h3>

<?php
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" placeholder=" Enter your name" value="<?= htmlspecialchars($name) ?>">
    </div>

    <div class="form-group">
        <label>Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" name="email" placeholder=" Enter your email" value="<?= htmlspecialchars($email) ?>">
    </div>

    <div class="form-group">
        <label>Phone</label>
        <input type="text" class="form-control" name="phone" placeholder=" Enter your phone" value="<?= htmlspecialchars($phone) ?>">
    </div>

    <div class="form-group">
        <label>Department <span class="text-danger">*</span></label>
        <select name="department_id" class="form-control">
            <option value="" selected disabled>-- اختر من القائمة --</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>



    <div class="form-group">
        <label>image</label>
        <input type="file" class="form-control" name="image">
    </div>


    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>