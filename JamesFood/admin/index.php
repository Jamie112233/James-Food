<?php
session_start();

if (isset($_SESSION['jamesfood_admin_logged'])) {
    header('Location: categories.php');
    die();
}

$username_error = false;
$password_error = false;
$login_error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    if (!$username) {
        $username_error = true;
    }

    $password = trim(filter_input(INPUT_POST, 'password'));
    if (!$password) {
        $password_error = true;
    }

    if (!$username_error && !$password_error) {
        require_once 'connect.php';

        $query = "SELECT u.user_id, u.full_name, u.password, r.role FROM users u LEFT JOIN roles r ON r.role_id = u.role_id WHERE u.username = :username LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($password, $row->password) && $row->role === 'admin') {

                $_SESSION['jamesfood_admin_logged'] = true;
                $_SESSION['jamesfood_admin_full_name'] = $row->full_name;

                header('Location: categories.php');
                die();
            }
        }

        $login_error_message = 'The username and password are incorrect.';
    }
}
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>


<div class="container d-flex align-items-center justify-content-center height-center">
    <div class="row">

    </div>
    <form method="post" class="row">
        <div class="col">
            <?php if($login_error_message !== ''): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $login_error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <div class="text-center h5 mb-3">
                Access to the administration dashboard
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control <?= $username_error ? 'is-invalid' : ''; ?>" name="username"
                    id="username" placeholder="Enter your username">
                <div class="invalid-feedback">
                    Please provide a valid username.
                </div>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control <?= $password_error ? 'is-invalid' : ''; ?>" name="password"
                    id="password" autocomplete="off" placeholder="Enter your password">
                <div class="invalid-feedback">
                    Please provide a valid password.
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Log in</button>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/foot.php'; ?>