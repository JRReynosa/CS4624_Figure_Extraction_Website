<?php
include '../../src/auth/redirect.php';
include '../../src/auth/is_verified.php';
include 'header.php';
?>

<body>
    <?php include 'menu.php' ?>
    <br><br><br>
    <div class="flex-center">
        <div class="card" style="width: 45rem;">
            <div class="card-header">User Dashboard</div>
            <div class="card-body">
                <?php include 'current_user.php'; ?>
                <br>
                <button onclick="location.href = 'change_username.php'">
                    Change Username
                </button>
                <button onclick="location.href = 'change_password.php'">
                    Change Password
                </button>
                <button style="float: right;" onclick="location.href = 'lists.php'">
                    Lists
                </button>
                <button style="float: right; margin-right: 10px;" onclick="location.href = 'likes.php'">
                    Likes
                </button>
                <button style="float: right; margin-right: 10px;" onclick="location.href = 'search_history_page.php'">
                    Search History
                </button>
            </div>
        </div>
    </div>
    <br>
    </div>
</body>

</html>