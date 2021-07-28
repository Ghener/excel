<?php
require 'database/db.php';
include 'SimpleXLSX.php';
$msg =""; 
if(isset($_POST["upload"])){
        $excel = $_FILES['file']['tmp_name'];
        $xlsx = new SimpleXLSX($excel);
        $fp = fopen('file.csv' ,'w');
        foreach($xlsx->rows() as $fields){
            fputcsv($fp,$fields);
        }
        fclose($fp);

        $fi = fopen('file.csv', 'r');
        $c = 0;

        while(($csv = fgetcsv($fi, 10000,","))!==false){
            if($c++ == 0) continue;
            $lname = $csv[0];
            $fname = $csv[1];
            $mname = $csv[2];
            $course = $csv[3];

            $sql = "INSERT INTO students(LastName, FirstName, MiddleName, Course) VALUES ('$lname', '$fname', '$mname', '$course')" ;
            $statement = $connection->prepare($sql);
            $statement->execute();

            $c=$c+1;
        }
    
}
    $sql = 'SELECT * FROM students';
    $sqlstatement = $connection->prepare($sql);
    $sqlstatement->execute();
    $students = $sqlstatement->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
</head>
<body>
<?php if(!empty($msg)): ?>
    <div class="alert alert-success">
      <?= $msg; ?>
    </div>
  <?php endif; ?>
    <form enctype="multipart/form-data" method="post" role="form">
    <div class="form-group">
        <label for="exampleInputFile">File Upload</label>
        <input type="file" accept=.xls,.xlsx name="file" id="file" size="150">
        <p class="help-block">Import Excel.</p>
    </div>
    <div class="contain">
    <div class="mt-5 mb-5">
            <form method="post" class="form-inline">
                <button type="submit" name="upload" class="btn btn-primary ml-1">Upload to Database</button>
            </form>
            <table class="table">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Last Name</th>
          <th scope="col">First Name</th>
          <th scope="col">Middle Name</th>
          <th scope="col">Course</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($students as $student): ?>
        <tr>
          <td><?= $student->id; ?></td>
          <td><?= $student->LastName; ?></td>
          <td><?= $student->FirstName; ?></td>
          <td><?= $student->MiddleName; ?></td>
          <td><?= $student->Course; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
</div>
</body>
</html>