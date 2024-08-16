<?php
if (isset($_SESSION['jamesfood_user_logged'])) {
    header('Location: '.$root_path);
    die();
}

$email_error = false;
$password_error = false;
$login_error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS));
    if (!$email) {
        $email_error = true;
    }

    $password = trim(filter_input(INPUT_POST, 'password'));
    if (!$password) {
        $password_error = true;
    }

    if (!$email_error && !$password_error) {
        require_once 'admin/connect.php';

        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($password, $row->password)) {

                $_SESSION['jamesfood_user_logged'] = true;
                $_SESSION['jamesfood_user_full_name'] = $row->full_name;
                $_SESSION['jamesfood_user_id'] = $row->user_id;
                $_SESSION['jamesfood_user_first_letter'] = substr($row->full_name,0,1);

                header('Location: '.$root_path);
                die();
            }
        }

        $login_error_message = 'The email and/or the password are incorrect.';
    }
}
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="container mt-5">
    <form method="post" class="row d-flex justify-content-center">
        <div class="col-6">
            <?php if ($login_error_message !== '') : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $login_error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <div class="text-center h5 mb-3">
                Sign in to the site
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control <?= $email_error ? 'is-invalid' : ''; ?>" name="email"
                    id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                <div class="invalid-feedback">
                    Please provide a valid email address.
                </div>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control <?= $password_error ? 'is-invalid' : ''; ?>" name="password"
                    id="password" value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                <div class="invalid-feedback">
                    Please provide a password.
                </div>
            </div>
            <div class="text-start mb-3">
                If you don't have an account, please <a href="<?= $root_path; ?>/sign-up"
                    class="text-decoration-none fw-bold text-warning">Sign up</a>.
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-warning">Sign in</button>
            </div>
        </div>
    </form>
</div>


<?php include 'includes/foot.php'; ?>