<?php
    session_start();
    include("connect.php"); 

    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];


    $check = mysqli_query($connect, "SELECT * FROM user WHERE name = '$name' AND password = '$password' AND role = '$role'");

    if (mysqli_num_rows($check) > 0) {
        $userdata = mysqli_fetch_array($check, MYSQLI_ASSOC);
        
      
        $groups = mysqli_query($connect, "SELECT * FROM user WHERE role = 2");
        $groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);

        $_SESSION['userdata'] = $userdata;
        $_SESSION['groupsdata'] = $groupsdata;
        
        echo "<script>
            window.location = 'dasboard.php'; 
        </script>";
    } 
    
    else {
       
        echo "<script>
            alert('Invalid name, password, or role');
            window.location = 'index.html'; 
        </script>";
    }
?>
