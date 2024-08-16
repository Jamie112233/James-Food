<?php
if (isset($_SESSION['jamesfood_user_logged'])) {
    header('Location: '.$root_path);
    die();
}

$full_name_error = false;
$email_error = false;
$password_error = false;
$repeat_password_error = false;
$success_sign_up = false;
$sign_up_error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['jamesfood_flash_token'] == $_POST['flash_token'])) {

    unset($_SESSION['jamesfood_flash_token']);

    $full_name = trim(filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS));
    if (!$full_name) {
        $full_name_error = true;
    }

    $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    if (!$email) {
        $email_error = true;
    }

    $password = trim(filter_input(INPUT_POST, 'password'));
    if (!$password) {
        $password_error = true;
    }

    $repeat_password = trim(filter_input(INPUT_POST, 'repeat_password'));
    if (!$repeat_password || ($password != $repeat_password)) {
        $repeat_password_error = true;
    }

    if (!$full_name_error && !$email_error && !$password_error && !$repeat_password_error) {
        require_once 'admin/connect.php';

        // Checking no other user exists with this email address
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch();
        if ($stmt->rowCount() == 0) {
            $query = "INSERT INTO users (full_name, username, password, role_id) VALUE (:full_name, :username, :password, :role_id)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':full_name', $full_name, PDO::PARAM_STR);
            $stmt->bindValue(':username', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':role_id', 2);

            if ($stmt->execute()) {
                $success_sign_up = true;
            }
        } else {
            $sign_up_error_message = 'Already exists a user with this email address, please sign up with another.';
        }
    }
}

$_SESSION['jamesfood_flash_token'] = md5(time());
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="container mt-5">
    <form method="post" class="row d-flex justify-content-center">
        <input type="hidden" name="flash_token" value="<?= $_SESSION['jamesfood_flash_token'] ?>" />
        <div class="col-6">
            <?php if ($sign_up_error_message !== '') : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $sign_up_error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <div class="text-center h5 mb-3">
                Sign up to the site
            </div>
            <?php if (!$success_sign_up) : ?>
            <div class="mb-3">
                <label>Full name</label>
                <input type="text" class="form-control <?= $full_name_error ? 'is-invalid' : ''; ?>" name="full_name"
                    id="full_name" value="<?= isset($_POST['full_name']) ? $_POST['full_name'] : ''; ?>">
                <div class="invalid-feedback">
                    Please provide your full name.
                </div>
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
            <div class="mb-3">
                <label>Repeat password</label>
                <input type="password" class="form-control <?= $repeat_password_error ? 'is-invalid' : ''; ?>"
                    name="repeat_password" id="repeat_password"
                    value="<?= isset($_POST['repeat_password']) ? $_POST['repeat_password'] : ''; ?>">
                <div class="invalid-feedback">
                    The password provided do not matches.
                </div>
            </div>
            <div class="text-start mb-3">
                <a href="<?= $root_path; ?>/sign-in" class="text-decoration-none fw-bold text-warning">Sign in</a> if
                you already have an account.
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-warning">Sign up</button>
            </div>
            <?php else : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= 'You have successfully signed up into the site'; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <p>Please <a href="<?= $root_path; ?>/sign-in" class="text-warning fw-bold text-decoration-none">Sign in</a>
                to continue.</p>
            <?php endif; ?>
        </div>
    </form>
</div>


<?php include 'includes/foot.php'; ?>