<?php
session_start();

require_once 'verify-private-section.php';

require_once 'connect.php';
require_once 'functions.php';

$error_title = false;
$error_category = false;
$error_image = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
	$short_description = trim(filter_input(INPUT_POST, 'short_description', FILTER_SANITIZE_SPECIAL_CHARS));

	if(!$title) {
		$error_title = true;
	}

	if($_POST['category'] === '') {
		$error_category = true;
	}

	if (!$error_title && !$error_category) {
		$query = 'INSERT INTO contents (category_id,title,short_description,description,slug_url) VALUE (:category_id,:title,:short_description,:description,:slug_url)';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':category_id', $_POST['category'], PDO::PARAM_INT);
		$stmt->bindValue(':title', $title, PDO::PARAM_STR);
		$stmt->bindValue(':short_description', $short_description, PDO::PARAM_STR);
		$stmt->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
		$stmt->bindValue(':slug_url', create_pretty_url($title), PDO::PARAM_STR);

		if ($stmt->execute()) {
			$content_id = $db->lastInsertId();

			$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
			$upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

			if ($image_upload_detected) {
				$image_filename        = $_FILES['image']['name'];
				$temporary_image_path  = $_FILES['image']['tmp_name'];

				$allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
				$allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

				$new_path = '../uploads/'.$image_filename;

				$actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION); 
				$file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);

				if($file_extension_is_valid) {
					if (move_uploaded_file($temporary_image_path, $new_path)) {
						$query = "UPDATE contents SET image = :image WHERE content_id = :content_id";
						$stmt = $db->prepare($query);
						$stmt->bindValue(':image', $image_filename, PDO::PARAM_STR);
						$stmt->bindValue(':content_id', $content_id, PDO::PARAM_STR);
						$stmt->execute();
					}
				}
				else {
					$file_extension_incorrect = true;
				}
			}

			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'success',
				'message' => 'The Content has been created successfully.'
			];
		} else {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'danger',
				'message' => 'There has been an error. Please try again later.'
			];
		}

		header('Location: contents.php');
		die();
	}
}

$query = "SELECT * FROM categories";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="wrapper">
	<?php
	$sidebar_item = 'contents';
	include 'includes/sidebar.php';
	?>

	<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h1 class="h2">Content - Add</h1>
		</div>
		<form method="post" enctype="multipart/form-data">
			<div class="mb-3">
				<label for="category" class="form-label">Category</label>
				<select class="form-select <?= $error_category ? 'is-invalid' : ''; ?>" id="category" name="category">
					<option value="">Select</option>
					<?php while ($row = $stmt->fetch()) : ?>
						<option value="<?= $row->category_id; ?>"><?= $row->category_name; ?></option>
					<?php endwhile; ?>
				</select>
				<div class="invalid-feedback">
					Please select the Category for the new Content.
				</div>
			</div>
			<div class="mb-3">
				<label for="title" class="form-label">Title</label>
				<input type="text" class="form-control <?= $error_title ? 'is-invalid' : ''; ?>" id="title" name="title" placeholder="Title of the content">
				<div class="invalid-feedback">
					Please provide a valid Title for the new Content.
				</div>
			</div>
			<div class="mb-3">
				<label for="image" class="form-label">Image</label>
				<input type="file" class="form-control" id="image" name="image" placeholder="Name of the content">
				<div class="invalid-feedback">
					Please provide a valid Image for the new Content.
				</div>
			</div>
			<div class="mb-3">
				<label for="short_description" class="form-label">Short description</label>
				<textarea class="form-control" id="short_description" name="short_description" placeholder="Short description of the content" rows="4"></textarea>
				<div class="invalid-feedback">
					Please provide a valid Name for the new Content.
				</div>
			</div>
			<div class="mb-3">
				<label for="description" class="form-label">Full description</label>
				<textarea class="form-control" id="description" name="description" placeholder="Short description of the content"></textarea>
			</div>

			<div class="text-center mb-4">
				<button class="btn btn-warning" type="submit">Save</button>
				<a class="btn btn-secondary" href="contents.php">Cancel</a>
			</div>
			<p>&nbsp;</p>
		</form>
	</main>
</div>

<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>

<script>
	CKEDITOR.replace('description');
	document.getElementById('sidebar').style.height = '150%';
</script>

<?php include 'includes/foot.php'; ?>