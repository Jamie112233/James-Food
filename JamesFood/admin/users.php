<?php
session_start();

require_once 'verify-private-section.php';

require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
	$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

	if ($action == 'delete user' && $user_id) {
		$query = 'DELETE FROM users WHERE user_id = :user_id';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'success',
				'message' => 'The user has been deleted successfully.'
			];
		} else {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'danger',
				'message' => 'There has been an error. Please try again later.'
			];
		}
	}
}

$query = "SELECT u.user_id,u.full_name,u.username,r.role FROM users u LEFT JOIN roles r ON u.role_id = r.role_id";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll();
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
            <h1 class="h2">Users</h1>
            <a class="btn btn-warning" href="add-user.php"><i class="bi bi-plus-square-fill"></i> Add</a>
        </div>
        <?php include 'flash-message.php'; ?>
        <div class="table-responsive mb-5">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Full name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="10%"></th>
                </tr>
                <?php foreach ($users as $u) : ?>
                <tr>
                    <td width="30%"><?= $u->full_name; ?></td>
                    <td><?= $u->role != 'admin' ? $u->username : '-'; ?></td>
                    <td><?= $u->role; ?></td>
                    <td class="text-start" width="10%" nowrap="nowrap">
                        <a href="edit-user.php?user_id=<?= $u->user_id; ?>" class="me-2 text-warning"
                            title="Edit this user"><i class="bi bi-pencil-square"></i></a>
                        <?php if ($u->role != 'admin') : ?>
                        <a href="#" class="me-2 text-warning" title="Prohibit user access to the website"><i
                                class="bi bi-eye-slash-fill"></i></a>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $u->user_id; ?>">
                            <input type="hidden" name="action" value="delete user">
                            <a href="#" class="me-2 text-warning" title="Delete this user"><i class="bi bi-trash3-fill"
                                    data-action="delete user"></i></a>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
</div>
<script src="js/delete-user.js?js=<?= time(); ?>"></script>
<?php include 'includes/foot.php'; ?>