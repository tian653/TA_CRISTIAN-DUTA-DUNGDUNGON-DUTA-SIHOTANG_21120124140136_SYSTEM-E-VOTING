<?php
    session_start();
    include("connect.php");

    // Initialize an empty stack for validation errors
    $validationErrors = [];

    // Get form data
    $name = $_POST["name"];
    $jenis_kelamin = $_POST["jeniskelamin"];
    $alamat = $_POST["alamat"];
    $password = $_POST["password"];
    $c_password = $_POST["c_password"];
    $image = $_FILES['photo']['name'];
    $tmp_name = $_FILES['photo']['tmp_name'];
    $role = $_POST['role'];


    
        if (empty($jenis_kelamin)) {
        array_push($validationErrors, "Jenis kelamin harus dipilih.");
        }

        // Check if image is uploaded
        if (empty($image)) {
            array_push($validationErrors, "Foto harus diunggah.");
        }

        // Check if passwords match
        if ($password !== $c_password) {
            array_push($validationErrors, "Kata sandi tidak cocok.");
        }

    // If there are validation errors, display them and stop further execution
    if (!empty($validationErrors)) {
        $errorMessages = implode("<br>", $validationErrors);  // Combine errors into a single string
        echo "<script>
                alert('$errorMessages');
                window.location = 'register.html';  // Kembali ke halaman register
            </script>";
        exit();
    }


    move_uploaded_file($tmp_name, "uploads/$image");

    // Insert user data into the database
    $insert = mysqli_query($connect, "INSERT INTO user (name, jeniskelamin, alamat, password, photo, role, status, votes) 
                                    VALUES ('$name', '$jenis_kelamin', '$alamat', '$password', '$image', '$role', 0, 0)");

    // If registration is successful
    if ($insert) {
        echo "<script>
                alert('Registrasi berhasil');
                window.location = 'index.html';  // Arahkan ke halaman login
            </script>";
    } else {
        echo "<script>
                alert('Registrasi gagal');
                window.location = 'register.html';  // Kembali ke halaman register
            </script>";
    }
?>
