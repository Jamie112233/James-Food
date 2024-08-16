<?php
session_start();

require_once 'verify-private-section.php';

require_once 'connect.php';
require_once 'functions.php';

$error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$category_name = trim(filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_SPECIAL_CHARS));
	$category_id = trim(filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT));

	if ($category_name && $category_id) {		
		$slug_url = create_pretty_url($category_name);

		$query = 'UPDATE categories SET category_name = :category_name, slug_url = :slug_url WHERE category_id = :category_id';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':category_name', $category_name, PDO::PARAM_STR);
		$stmt->bindValue(':slug_url', $slug_url, PDO::PARAM_STR);
		$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'success',
				'message' => 'The Category has been updated successfully.'
			];
		} else {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'danger',
				'message' => 'There has been an error. Please try again later.'
			];
		}

		header('Location: categories.php');
		die();
	} else {
		$error = true;
	}
}
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$category_id = trim(filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT));

	if($category_id) {
		$query = 'SELECT * FROM categories WHERE category_id = :category_id';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
			$category_name = $row->category_name;
		}
		else {

		}
	}
}
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="wrapper">
    <?php
	$sidebar_item = 'categories';
	include 'includes/sidebar.php';
	?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Categories - Edit</h1>
        </div>
        <form method="post">
            <input type="hidden" id="category_id" name="category_id" value="<?= $category_id; ?>">
            <div class="mb-3">
                <label for="category_name" class="form-label">Name</label>
                <input type="text" class="form-control <?= $error ? 'is-invalid' : ''; ?>" id="category_name"
                    name="category_name" placeholder="Name of the category" value="<?= $category_name; ?>">
                <div class="invalid-feedback">
                    Please provide a valid Name for the new Category.
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-warning" type="submit">Save</button>
                <a class="btn btn-secondary" href="categories.php">Cancel</a>
            </div>
        </form>
    </main>
</div>

<?php include 'includes/foot.php'; ?>