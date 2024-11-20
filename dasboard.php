<?php 
    session_start(); 

    // Fungsi untuk memeriksa apakah pengguna sudah login
    function checkUserSession() {
        if (!isset($_SESSION['userdata'])) {  
            header('location:login.php');  
            exit();  
        }
    }

    // Fungsi untuk mendapatkan status pengguna (sudah memberikan suara atau belum)
    function getUserStatus() {
        return ($_SESSION['userdata']['status'] == 0) ? '<b> Berikan suara </b>' : '<b> Suara telah diberikan </b>';
    }

    function prepareGroupData($groupsdata) {
        $group_names = [];  
        $group_votes = [];  
        for ($i = 0; $i < count($groupsdata); $i++) {  
            $group_names[] = $groupsdata[$i]['name'];  
            $group_votes[] = $groupsdata[$i]['votes'];  
        }
        return [  
            'names_json' => json_encode($group_names),  
            'votes_json' => json_encode($group_votes)  
        ];
    }

    // Panggil fungsi untuk memeriksa sesi pengguna
    checkUserSession(); 

    // Ambil data pengguna dan grup dari sesi
    $userdata = $_SESSION['userdata']; 
    $groupsdata = $_SESSION['groupsdata']; 

    // Dapatkan status pengguna
    $status = getUserStatus();

    // Siapkan data grup untuk JavaScript
    $groupData = prepareGroupData($groupsdata);
    $names_json = $groupData['names_json'];
    $votes_json = $groupData['votes_json'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
    <div class="container-header">
        <a href="logout.php"> <button class="btnlogout">Logout</button></a>
        <a href="index.html"> <button class="btnlogout">Back</button></a>
        <h1>System E-Voting</h1>
    </div>

    <!-- Menampilkan profil pengguna -->
    <div class="profile">
        <img src="uploads/<?php echo $userdata['photo']; ?>" height="100" width="100"> <br><br>
        <b>Nama:</b> <?php echo $userdata['name']; ?> <br><br>
        <b>Jenis Kelamin:</b> <?php echo $userdata['jeniskelamin']; ?> <br><br>
        <b>Alamat:</b> <?php echo $userdata['alamat']; ?><br><br>
        <b>Status:</b> <?php echo $status; ?><br><br>
    </div>

    <!-- Menampilkan daftar grup yang dapat dipilih untuk memberi suara -->
    <div class="grup">
        <?php 
            if (!empty($groupsdata)) {  
                for ($i = 0; $i < count($groupsdata); $i++) {  
                    ?>
                    <div class="group-item">
                        <img style="float: right;" src="uploads/<?php echo $groupsdata[$i]['photo']; ?>" height="100" width="100">
                        <b>Nama Group: <?php echo $groupsdata[$i]['name']; ?></b><br>
                        <b>Votes: <?php echo $groupsdata[$i]['votes']; ?></b> <br><br>
                        <form action="vote.php" method="post"> 
                            <input type="hidden" name="gvotes" value="<?php echo $groupsdata[$i]['votes']; ?>">
                            <input type="hidden" name="gid" value="<?php echo $groupsdata[$i]['name']; ?>">
                            <?php 
                                if ($_SESSION['userdata']['status'] == 0){  
                                    ?> 
                                        <input type="submit" name="votebtn" value="vote" class="votebtn">  
                                    <?php
                                }
                                else{
                                    ?> 
                                        <button disabled type="button" name="votebtn" value="Vote" class="voted">voted</button> 
                                     <?php
                                }
                             ?>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No groups available</p>";  
            }
        ?>
    </div>

    <!-- Menampilkan grafik hasil voting -->
    <div class="chart-container" style="width: 80%; margin: 30px auto; padding: 20px; background-color: #f4f4f4; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h3 style="text-align:center; color: #333;">Vote Distribution by Group</h3>
        <canvas id="votesChart"></canvas>
    </div>

    <script>
        // Step 1: Initialize data
        var groupNames = <?php echo $names_json; ?>;
        var groupVotes = <?php echo $votes_json; ?>;

        // Step 2: Configure Chart.js with stacking enabled
        var ctx = document.getElementById('votesChart').getContext('2d');
        var votesChart = new Chart(ctx, {
            type: 'bar',  // Chart type: bar
            data: {
                labels: groupNames,  // Labels (group names)
                datasets: [{
                    label: 'Votes',
                    data: groupVotes,  // The data for the votes of each group
                    backgroundColor: 'rgba(0, 0, 0, 0.6)',  // Color of the bars
                    borderColor: 'rgba(0, 0, 0, 1)',  // Border color of the bars
                    borderWidth: 1  // Width of the bar borders
                }]
            },
            options: {
                responsive: true,  // Make the chart responsive
                scales: {
                    y: {
                        beginAtZero: true,  // Ensure the y-axis starts at zero
                        stacked: true  // Enable stacking for the y-axis
                    },
                    x: {
                        stacked: true  // Enable stacking for the x-axis
                    }
                },
                plugins: {
                    tooltip: {
                        enabled: true,  // Enable tooltips
                        backgroundColor: 'rgba(0,0,0,0.8)',  // Tooltip background color
                        titleColor: '#fff',  // Tooltip title color
                        bodyColor: '#fff'  // Tooltip body color
                    }
                }
            }
        });
    </script>

</body>
</html> 
