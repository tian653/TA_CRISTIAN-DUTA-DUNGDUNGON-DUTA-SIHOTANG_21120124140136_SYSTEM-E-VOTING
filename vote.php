<?php 
session_start();
include('connect.php');

// Retrieving data from POST and SESSION
$votes = $_POST['gvotes'];
$total_votes = $votes + 1;
$gid = $_POST['gid'];
$uid = $_SESSION['userdata']['name'];

// Updating the votes
$update = mysqli_query($connect, "UPDATE user SET votes='$total_votes' WHERE name='$gid'");
$update_user_status = mysqli_query($connect, "UPDATE user SET status=1 WHERE name='$uid'");

// Correct variable names in the condition
if ($update && $update_user_status) {
    // Fetching groups data
    $groups = mysqli_query($connect, "SELECT name, votes, photo FROM user WHERE role = 2");
    $groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);

    // Updating session data
    $_SESSION['userdata']['status'] = 1;
    $_SESSION['groupsdata'] = $groupsdata;

    echo
    "<script> alert('Berhasil')
        window.location ='dasboard.php' </script>";

  
} else {
    echo "<script>
        alert('Terjadi kesalahan');
        window.location = 'dasboard.php';
    </script>";
}
?>
