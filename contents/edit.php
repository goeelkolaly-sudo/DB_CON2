<?php
include "config.php";

$errors = [];
$imageName = null;
$name = $email = $phone = $departmentID = "";

$stmt = $connection->prepare("SELECT * FROM departments");
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);


$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!isset($id) || !is_numeric($id)) {
    die("invalid student id");
}
$stmt = $connection->prepare("SELECT * FROM students  WHERE id = '$id'");
$stmt->execute();

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($student)) {
    die("enter Valid student id");
}

$name = $student['name'];
$email = $student['email'];
$phone = $student['phone'];
$departmentId = $student['department_id'];
$imagePath = $student['image'];



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
        $stmt = $connection->prepare("SELECT   email from students WHERE email = '$email' AND id != '$id' ");
        $stmt->execute();
        if ($stmt->fetch()) {
            $errors[] = "email aready exist";
        }
    }



    if ($phone != "") {
        $stmt = $connection->prepare("SELECT   phone from students WHERE phone = '$phone' AND id != '$id' ");
        $stmt->execute();

        if ($stmt->fetch()) {
            $errors[] = "phone aready exist ";
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
        $imagePath = "uploads/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }


    if (empty($errors)) {
        $stmt = $connection->prepare("UPDATE students SET name = :name, email = :email, phone = :phone, department_id = :departmentId, image = :imagePath WHERE id = :id");
        $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'id' => $id, 'departmentId' => $departmentId, 'imagePath' => $imagePath]);

        header("location: index.php");
        exit;
    }
}
?>

<h3> edit student form </h3>

<?php
if (!empty($errors)) {
    foreach ($errors as $err) {
        echo "<p style='color:red'>$err</p>";
    }
}
?>



?>
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
        <label> Department <span class="text-danger">*</span></label>
        <select name="department_id" class="form-control">
            <option value="" selected disabled>-- اختر من القائمة --</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?= $department['id'] ?>" <?= ($department['id'] == $departmentId) ? 'selected' : '' ?>> <?= $department['name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="form-group">
        <label>image</label>
        <img src="<?= $imagePath ?>" alt="img" style="width: 45px;">
        <input type="file" class="form-control" name="image">
    </div>


    <button type="submit" class="btn btn-primary" name="submit">edit</button>
</form>