<?php

session_start();

$result = ['success' => true, 'message' => ''];
$mysqli = new mysqli('localhost', 'root', '', 'users');

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (strlen($username) > 30 || strlen($password) > 30) return;

   checkUser($username, $password);
}

else if (isset($_POST['logout'])) {
    logout();
}

function getUser($username) {
    global $mysqli;
    $stmt = $mysqli->stmt_init();
    $stmt->prepare('SELECT * FROM registered_users WHERE username = ?;');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $sql_result = $stmt->get_result();
    $user = mysqli_num_rows($sql_result) > 0 ? $sql_result->fetch_assoc() : null;
    $stmt->close();
    return $user;
}

function checkUser($username, $password)
{
    global $result;
    if ($username != '' && $password != '') {
        $user = getUser($username);

        if (!$user) {
            $result['success'] = false;
            $result['message'] = 'Username or password is incorrect';
            return;
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
        } else {
            $result['success'] = false;
            $result['message'] = 'Username or password is incorrect';
        }
    } else {
        $result['success'] = false;
        $result['message'] = 'There is no username or password';
    }
}

function logout() {
    unset($_SESSION['loggedin']);
}

?>
<html>

<link rel="stylesheet" href="style.css">

<body>
    <div id="wrap">
        <?php if (isset($_SESSION['loggedin'])) { ?>
            <form action="" method="POST">
                <input type="hidden" name="logout" value="logout">
                <button>Logout</button>
            </form>
        <?php } ?>
        <?php if (!isset($_SESSION['loggedin'])) { ?>
            <?php if ($result['message'] != '') { ?>
                <p class="message" id="error"><?php echo $result['message'] ?></p>
            <?php } ?>
            <form action="" method="POST">

                <div>
                    <label for="username">Username</label>
                    <input name="username" id="username" maxlength="30" required placeholder="Maximum Username length is 30">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input name="password" id="password" type="password" required>
                </div>
                <div>
                    <button>Login</button>
                </div>
            </form>
        <?php } ?>
    </div>
</body>

</html>