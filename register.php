<?php

session_start();

if (isset($_SESSION['loggedin'])) {
    header('location: ./login.php');
    exit();
}

$result = ['success' => false, 'message' => ''];
$mysqli = new mysqli('localhost', 'root', '', 'users');

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (strlen($username) > 30 || strlen($password) > 30) return;

    register($username, $password);
}

function register($username, $password) {
    global $mysqli, $result;
    $user = getUser($username);
    if (!$user) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->stmt_init();
        $stmt->prepare('INSERT INTO registered_users (username, password) VALUES(?, ?)');
        $stmt->bind_param('ss', $username, $hashed);
        if($stmt->execute()) {
            $result['success'] = true;
            $result['message'] = 'Registered successfully';
        };
        $stmt->close();
    }
    else {
        $result['success'] = false;
        $result['message'] = 'There is already a user registered  with this username';
    }
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

?>

<html>

<link rel="stylesheet" href="style.css">

<body>
    <div id="wrap">
        <?php if ($result['message'] != '') { ?>
            <p class="message" id="<?php echo $result['success'] === true ? 'success' : 'error' ?>"><?php echo $result['message'] ?></p>
        <?php } ?>
        <?php if ($result['success'] === false) { ?>
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
                    <button>Register</button>
                </div>
            </form>
        <?php } ?>
    </div>
</body>

</html>