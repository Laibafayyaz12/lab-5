<?php
// --- Step 1: Database Connection ---
$servername = "localhost";
$username = "root";
$password = "";

// Connect without database first
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Step 2: Create Database if not exists ---
$conn->query("CREATE DATABASE IF NOT EXISTS student_database1");
$conn->select_db("student_database1");

// --- Step 3: Create Table if not exists ---
$conn->query("CREATE TABLE IF NOT EXISTS student1 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    course VARCHAR(100)
)");

// --- Step 4: Handle Add ---
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $course = $_POST['course'];

    $conn->query("INSERT INTO student1 (name,email,phone,address,course) 
                  VALUES ('$name','$email','$phone','$address','$course')");
}

// --- Step 5: Handle Delete ---
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM student1 WHERE id=$id");
}

// --- Step 6: Handle Edit fetch ---
$editData = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM student1 WHERE id=$id");
    $editData = $result->fetch_assoc();
}

// --- Step 7: Handle Update ---
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $course = $_POST['course'];

    $conn->query("UPDATE student1 SET 
        name='$name',
        email='$email',
        phone='$phone',
        address='$address',
        course='$course'
        WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
        }
        .card{
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .table thead{
            background: #343a40;
            color: white;
        }
        .btn-primary{
            background: #667eea;
            border: none;
        }
        .btn-primary:hover{
            background: #5a67d8;
        }
        .title{
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <h2 class="text-center title mb-4">🎓 Student Management System</h2>

    <!-- Form Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST">
                <?php if($editData){ ?>
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                <?php } ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Full Name" required
                        value="<?php echo $editData['name'] ?? ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="email" name="email" class="form-control" placeholder="Email Address" required
                        value="<?php echo $editData['email'] ?? ''; ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" name="phone" class="form-control" placeholder="Phone Number" required
                        value="<?php echo $editData['phone'] ?? ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="course" class="form-control" placeholder="Course" required
                        value="<?php echo $editData['course'] ?? ''; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <textarea name="address" class="form-control" placeholder="Address" required><?php echo $editData['address'] ?? ''; ?></textarea>
                </div>

                <div class="text-end">
                    <?php if($editData){ ?>
                        <button type="submit" name="update" class="btn btn-success px-4">Update</button>
                        <a href="index.php" class="btn btn-secondary px-4">Cancel</a>
                    <?php } else { ?>
                        <button type="submit" name="add" class="btn btn-primary px-4">Add Student</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-body">
            <table class="table table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = $conn->query("SELECT * FROM student1");
                while($row = $result->fetch_assoc()){
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['course']; ?></td>
                        <td>
                            <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Are you sure?')" 
                               class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>