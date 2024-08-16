<?php
session_start();

require_once 'verify-private-section.php';
require_once "connect.php";
require_once "functions.php";

$update_error = false;
$role_error = false;
$full_name_error = false;
$email_error = false;
$password_error = false;
$edit_user_error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_SESSION["jamesfood_security_flash_token"] == $_POST["flash_token"])) {

	unset($_SESSION["jamesfood_security_flash_token"]);

	$user_id = $_POST["user_id"];

	$role_id = trim(filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS));
	if (!$role_id) {
		$role_error = true;
	}

	$full_name = trim(filter_input(INPUT_POST, "full_name", FILTER_SANITIZE_SPECIAL_CHARS));
	if (!$full_name) {
		$full_name_error = true;
	}

	$email = trim(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL));
	if (!$email) {
		$email_error = true;
	}

	$create_new_password = trim(filter_input(INPUT_POST, "create_new_password"));
	$password = trim(filter_input(INPUT_POST, "password"));
	if ($create_new_password && !$password) {
		$password_error = true;
	}

	if (!$role_error && !$full_name_error && !$email_error && !$password_error) {
		// Checking no other user exists with this email address
		$query = "SELECT * FROM users WHERE username = :username AND user_id != :user_id";
		$stmt = $db->prepare($query);
		$stmt->bindValue(":username", $email, PDO::PARAM_STR);
		$stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch();
		if ($stmt->rowCount() == 0) {
			$query = "UPDATE users SET full_name = :full_name, username = :username, role_id = :role_id WHERE user_id = :user_id";
			$stmt = $db->prepare($query);
			$stmt->bindValue(":full_name", $full_name, PDO::PARAM_STR);
			$stmt->bindValue(":username", $email, PDO::PARAM_STR);
			$stmt->bindValue(":role_id", $role_id, PDO::PARAM_INT);
			$stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

			if ($stmt->execute()) {
				if ($create_new_password === "on") {
					$query = "UPDATE users SET password = :password WHERE user_id = :user_id";
					$stmt = $db->prepare($query);
					$stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
					$stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);

					if (!$stmt->execute()) $update_error = true;
				}
			} else $update_error = true;

			if (!$update_error) {
				$_SESSION["jamesfood_flash_message"] = [
					"type" => "success",
					"message" => "The new User has been added successfully."
				];
			} else {
				$_SESSION["jamesfood_flash_message"] = [
					"type" => "danger",
					"message" => "There has been an error. Please try again later."
				];
			}

			header("Location: users.php");
			die();
		} else {
			$edit_user_error_message = "Already exists a user with this email address, please use another email.";
		}
	}
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
	$user_id = trim(filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT));

	if ($user_id) {
		$query = "SELECT * FROM users WHERE user_id = :user_id";
		$stmt = $db->prepare($query);
		$stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch();

			$full_name = $row->full_name;
			$email = $row->username;
			$role_id = $row->role_id;
		} else {

		}
	}
}

$_SESSION["jamesfood_security_flash_token"] = md5(time());

$query = "SELECT * FROM roles";
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
            <input type="hidden" name="flash_token" value="<?= $_SESSION["jamesfood_security_flash_token"]; ?>" />
            <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
            <?php if ($edit_user_error_message !== "") : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $edit_user_error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select <?= $role_error ? "is-invalid" : ""; ?>" name="role" id="role">
                    <option value="">Select</option>
                    <?php foreach ($roles as $r) : ?>
                    <option value="<?= $r->role_id; ?>" <?= $r->role_id == $role_id ? 'selected="selected"' : ""; ?>>
                        <?= $r->role; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    Please select a Role.
                </div>
            </div>
            <div class="mb-3">
                <label for="full_name" class="form-label">Full name</label>
                <input type="text" class="form-control <?= $full_name_error ? "is-invalid" : ""; ?>" name="full_name"
                    id="full_name" value="<?= $full_name; ?>">
                <div class="invalid-feedback">
                    Please type your full name.
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?= $email_error ? "is-invalid" : ""; ?>" name="email"
                    id="email" value="<?= $email; ?>">
                <div class="invalid-feedback">
                    Please type a correct email address.
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Create New Password</label>
                <input type="checkbox" name="create_new_password" id="create_new_password"
                    <?= isset($_POST['create_new_password']) && $_POST['create_new_password'] === 'on' ? 'checked="checked"' : ''; ?>>
            </div>
            <div class="mb-3 <?= isset($_POST['create_new_password']) && $_POST['create_new_password'] === 'on' ? '' : 'd-none'; ?>"
                id="show-password">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control <?= $password_error ? "is-invalid" : ""; ?>" name="password"
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
<script src="js/edit-user.js?js=<?= time(); ?>"></script>
<?php include 'includes/foot.php'; ?>