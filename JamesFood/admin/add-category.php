<?php
session_start();

require_once 'verify-private-section.php';

$error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$category_name = trim(filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_SPECIAL_CHARS));
	if ($category_name) {
		require_once 'connect.php';
		require_once 'functions.php';

		$slug_url = create_pretty_url($category_name);

		$query = 'INSERT INTO categories (category_name,slug_url) VALUE (:category_name,:slug_url)';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':category_name', $category_name, PDO::PARAM_STR);
		$stmt->bindValue(':slug_url', $slug_url, PDO::PARAM_STR);

		if ($stmt->execute()) {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'success',
				'message' => 'The new Category has been added successfully.'
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
            <h1 class="h2">Categories - Add</h1>
        </div>
        <form method="post">
            <div class="mb-3">
                <label for="category_name" class="form-label">Name</label>
                <input type="text" class="form-control <?= $error ? 'is-invalid' : ''; ?>" id="category_name"
                    name="category_name" placeholder="Name of the category">
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