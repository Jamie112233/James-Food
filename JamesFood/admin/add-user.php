<?php
session_start();

require_once 'verify-private-section.php';
require_once 'connect.php';

$role_error = false;
$full_name_error = false;
$email_error = false;
$password_error = false;
$repeat_password_error = false;
$add_user_error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['jamesfood_security_flash_token'] == $_POST['security_flash_token'])) {
	unset($_SESSION['jamesfood_security_flash_token']);

	$role = trim(filter_input(INPUT_POST, 'role', FILTER_SANITIZE_SPECIAL_CHARS));
	if (!$role) {
		$role_error = true;
	}

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

	if (!$role_error && !$full_name_error && !$email_error && !$password_error) {
		// Checking no other user exists with this email address
		$query = "SELECT * FROM users WHERE username = :username";
		$stmt = $db->prepare($query);
		$stmt->bindValue(':username', $email, PDO::PARAM_STR);
		$stmt->execute();

		$row = $stmt->fetch();
		if ($stmt->rowCount() == 0) {
			$query = "INSERT INTO users (full_name, username, password,role_id) VALUE (:full_name, :username, :password, :role_id)";
			$stmt = $db->prepare($query);
			$stmt->bindValue(':full_name', $full_name, PDO::PARAM_STR);
			$stmt->bindValue(':username', $email, PDO::PARAM_STR);
			$stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
			$stmt->bindValue(':role_id', $role, PDO::PARAM_INT);

			if ($stmt->execute()) {
				$_SESSION['jamesfood_flash_message'] = [
					'type' => 'success',
					'message' => 'The new User has been added successfully.'
				];
			} else {
				$_SESSION['jamesfood_flash_message'] = [
					'type' => 'danger',
					'message' => 'There has been an error. Please try again later.'
				];
			}

			header('Location: users.php');
			die();
		} else {
			$add_user_error_message = 'Already exists a user with this email address, please register the new user with another email.';
		}
	}
}

$_SESSION['jamesfood_security_flash_token'] = md5(time());

$query = 'SELECT * FROM roles';
$stmt = $db->prepare($query);
$stmt->execute();
$roles = $stmt->fetchAll();
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="wrapper">
    <?php
	$sidebar_item = 'users';
	include 'includes/sidebar.php';
	?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Users - Add</h1>
        </div>
        <?php include 'flash-message.php'; ?>

        <form method="post">
            <input type="hidden" name="security_flash_token"
                value="<?= $_SESSION['jamesfood_security_flash_token'] ?>" />
            <?php if ($add_user_error_message !== '') : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $add_user_error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select <?= $role_error ? 'is-invalid' : ''; ?>" name="role" id="role">
                    <option value="">Select</option>
                    <?php foreach ($roles as $r) : ?>
                    <option value="<?= $r->role_id; ?>"
                        <?= isset($_POST['role']) && $r->role_id == $_POST['role'] ? 'selected="selected"' : ''; ?>>
                        <?= $r->role; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    Please select a Role.
                </div>
            </div>
            <div class="mb-3">
                <label for="full_name" class="form-label">Full name</label>
                <input type="text" class="form-control <?= $full_name_error ? 'is-invalid' : ''; ?>" name="full_name"
                    id="full_name" value="<?= isset($_POST['full_name']) ? $_POST['full_name'] : ''; ?>">
                <div class="invalid-feedback">
                    Please type your the new user's full name.
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?= $email_error ? 'is-invalid' : ''; ?>" name="email"
                    id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                <div class="invalid-feedback">
                    Please type a correct email address.
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control <?= $password_error ? 'is-invalid' : ''; ?>" name="password"
                    id="password" value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                <div class="invalid-feedback">
                    Please type a password.
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-warning" type="submit">Save</button>
                <a class="btn btn-secondary" href="users.php">Cancel</a>
            </div>
        </form>
    </main>
</div>

<?php include 'includes/foot.php'; ?>