<?php
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'cookie_samesite' => 'Lax',
]);

include 'db_connect.php';

if (isset($_POST['login'])) {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        echo "<script>alert('Username and password are required');</script>";
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT user_id, username, password FROM users WHERE username = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
            ];
            header('Location: dashboard.php');
            exit;
        } else {
            echo "<script>alert('Invalid credentials');</script>";
        }
    }
}
?>
