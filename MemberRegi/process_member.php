<?php
// 1. Database connection creating and setting the path
include 'config/db.php';

// 2. Saving Members
if (isset($_POST['save_member'])) {
    $member_id = mysqli_real_escape_string($conn, $_POST['member_id']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "INSERT INTO member (member_id, first_name, last_name, birthday, email) 
              VALUES ('$member_id', '$first_name', '$last_name', '$birthday', '$email')";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
    header("Location: member_registration.php?status=success");
    exit(0);
} else {
    // when error comes it shows the error
    header("Location: member_registration.php?status=duplicate");
    exit(0);
}
    }


// 3. Deleting a member
if (isset($_POST['delete_member'])) {
    $member_id = mysqli_real_escape_string($conn, $_POST['delete_member']);

    $query = "DELETE FROM member WHERE member_id='$member_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        header("Location: member_registration.php?status=deleted");
        exit(0);
    } else {
        header("Location: member_registration.php?status=error");
        exit(0);
    }
}
// 4. Updating a member
if (isset($_POST['update_member'])) {
    $old_id = mysqli_real_escape_string($conn, $_POST['old_member_id']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "UPDATE member SET first_name='$first_name', last_name='$last_name', birthday='$birthday', email='$email' WHERE member_id='$old_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        header("Location: member_registration.php?status=updated");
        exit(0);
    } else {
        header("Location: member_registration.php?status=error");
        exit(0);
    }
}
?>