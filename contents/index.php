<?php
include "config.php";

$search = $_GET['search'] ?? '';

$limit = 10;

$page =  $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;

$stmt = $connection->prepare("SELECT students.*, departments.name as department_name FROM students INNER JOIN departments ON departments.id = students.department_id WHERE students.name like '%$search%' or students.email like '$search' limit $start , $limit  ");
$stmt->execute();

//>> array
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$countStudents = $connection->prepare("SELECT count(*) FROM students where name like '%$search%'");
$countStudents->execute();

$total = $countStudents->fetchColumn();
$pages = ceil($total / $limit)
?>
<a href="create.php" class="btn btn-primary">ADD STUDENT</a>

<form method="GET">
    <input type="text" class="form-control" name="search" placeholder="search..." value="<?= htmlspecialchars($search) ?>">

</form>


<table class="table table-bordered">
    <thead>
        <tr>
            <th>student id</th>
            <th>image</th>
            <th>student name</th>
            <th>student email</th>
            <th>student phone</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $row): ?>
            <tr>

                <td> <?= $row['id'] ?> </td>
                <td> <img src="<?= $row['image'] ?>" alt="img" style="width: 45px;" > </td>
                <td> <?= $row['name'] ?> </td>
                <td> <?= $row['email'] ?> </td>
                <td> <?= $row['phone'] ?> </td>
                <td>

                    <a href="edit.php?id=<?= $row['id'] ?>"> edit </a>
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are You Want TO Delete ?')"> delete </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>