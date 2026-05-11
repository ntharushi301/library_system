<?php 
// Creating database connection with the path
include 'config/db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration | Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #2971b9; }
        .card-header { background-color: #043970; color: white; }
    </style>
</head>
<body>
    

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Add New Library Member</h4>
                </div>
                <div class="card-body">
                    <form action="process_member.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><b>Member ID</b></label>
                                <input type="text" name="member_id" class="form-control" placeholder="M001" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><b>First Name</b></label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><b>Last Name</b></label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><b>Birthday</b></label>
                                <input type="date" name="birthday" class="form-control" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label"><b>Email Address</b></label>
                                <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" name="save_member" class="btn btn-dark px-4">Register Member</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Registered Members List</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Birthday</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Getting data from database using php
                                $query = "SELECT * FROM member";
                                $query_run = mysqli_query($conn, $query);

                                if(mysqli_num_rows($query_run) > 0) {foreach($query_run as $row) {
                                        ?>
                                        <tr>
                                            <td><?= $row['member_id']; ?></td>
                                            <td><?= $row['first_name']; ?></td>
                                            <td><?= $row['last_name']; ?></td>
                                            <td><?= $row['birthday']; ?></td>
                                            <td><?= $row['email']; ?></td>
                                            <td>
                                                <a href="edit_member.php?id=<?= $row['member_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="process_member.php" method="POST" class="d-inline">
                                                    <button type="submit" name="delete_member" value="<?= $row['member_id']; ?>" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    } 
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No Records Found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
