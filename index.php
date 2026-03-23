<?php
include"config.php";
$stmt = $connection->prepare("SELECT * FROM students");
$stmt->execute();

//ass arr
$students=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<a href="create.php"> ADD STUDENT </a>

<table border= "1">
    <tr>
        <th> student id </th>
        <th> student name </th>
        <th> student email </th>
        <th> student phone </th>
        <th> action </th>
    </tr>

<?php foreach($students as $student) :?>
    <tr>
        <td><?= $student ['id'] ?></td>
        <td><?= $student ['name'] ?></td>
        <td><?= $student ['email'] ?></td>
        <td><?= $student ['phone'] ?></td>
        <td> 
            <a href="edit.php?id=<?=$student['id']?>"> edit </a> <br>
            <a href="delete.php?id=<?=$student['id']?>" onclick= "return confirm('are you sure to delete');" > delete  </a> 
    </td>
    </tr>
<?php endforeach; ?>
</table>
