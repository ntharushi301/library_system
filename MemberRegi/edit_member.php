<?php //Setting database connection
include 'config/db.php';// creating the path

//Getting data to check from database
if(isset($_GET['id'])) {
    $member_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM member WHERE member_id='$member_id'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0) {
        $member = mysqli_fetch_array($query_run);
    } else {
        echo "<h4>No Such Member Found</h4>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body style="background-color: #1e88d4 !important;">
    
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header text-white" style="background-color: #000080 ;">
                <h4>Edit Member Details</h4>
            </div>
            <div class="card-body">

               
                <form action="process_member.php" method="POST">
                    <input type="hidden" name="old_member_id" value="<?= $member['member_id']; ?>">
                    
                    <div class="mb-3">
                        <label><b>Member ID</b></label>
                        <input type="text" name="member_id" value="<?= $member['member_id']; ?>" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label><b>First Name</b></label>
                        <input type="text" name="first_name" value="<?= $member['first_name']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label><b>Last Name</b></label>
                        <input type="text" name="last_name" value="<?= $member['last_name']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label><b>Birthday</b></label>
                        <input type="date" name="birthday" value="<?= $member['birthday']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label><b>Email</b></label>
                        <input type="email" name="email" value="<?= $member['email']; ?>" class="form-control" required>
                    </div>
                    <button type="submit" name="update_member" class="btn btn-primary">Update Member</button>
                    <a href="member_registration.php" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
