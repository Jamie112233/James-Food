<?php
session_start();

require_once 'verify-private-section.php';

require_once 'connect.php';
require_once 'functions.php';

$error_title = false;
$error_category = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// From lines 12-16, the data received is sanitized by/via POST (4.2)
	$title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
	$category = trim(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS));
	$short_description = trim(filter_input(INPUT_POST, 'short_description', FILTER_SANITIZE_SPECIAL_CHARS));
	$content_id = trim(filter_input(INPUT_POST, 'content_id', FILTER_VALIDATE_INT));
	$image_has_been_deleted = trim(filter_input(INPUT_POST, 'image_has_been_deleted', FILTER_SANITIZE_SPECIAL_CHARS));

	// There must be a title/category to execute the code below, otherwise, an error appears (4.1)
	if ($title && $category) {
		$query = 'UPDATE contents SET category_id = :category_id,title = :title,short_description = :short_description, description = :description, slug_url = :slug_url WHERE content_id = :content_id';
		// Prepare and bind the query to prevent injections (4.2)
		$stmt = $db->prepare($query);
		$stmt->bindValue(':category_id', $_POST['category'], PDO::PARAM_INT);
		$stmt->bindValue(':title', $title, PDO::PARAM_STR);
		$stmt->bindValue(':short_description', $short_description, PDO::PARAM_STR);
		$stmt->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
		$stmt->bindValue(':content_id', $content_id, PDO::PARAM_INT);
		$stmt->bindValue(':slug_url', create_pretty_url($title), PDO::PARAM_STR);

		if ($stmt->execute()) {
			$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
			$upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

			if ($image_upload_detected) {
				$image_filename        = $_FILES['image']['name'];
				$temporary_image_path  = $_FILES['image']['tmp_name'];

				$allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
				$allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

				$new_path = '../uploads/' . $image_filename;

				$actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
				$file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);

				if ($file_extension_is_valid) {
					if (move_uploaded_file($temporary_image_path, $new_path)) {
						$query = 'UPDATE contents SET image = :image WHERE content_id = :content_id';
						$stmt = $db->prepare($query);
						$stmt->bindValue(':image', $image_filename, PDO::PARAM_STR);
						$stmt->bindValue(':content_id', $content_id, PDO::PARAM_STR);
						$stmt->execute();
					}
				} else {
					$file_extension_incorrect = true;
				}
			}
			else if($image_has_been_deleted == 'true') {
				$query = "SELECT image FROM contents WHERE content_id = :content_id";
				$stmt = $db->prepare($query);
				$stmt->bindValue(':content_id', $content_id, PDO::PARAM_INT);
				$stmt->execute();
				$row = $stmt->fetch(); 
	
				unlink('../uploads/'.$row->image);
				
				$query = "UPDATE contents SET image = NULL WHERE content_id = :content_id";
				$stmt = $db->prepare($query);
				$stmt->bindValue(':content_id', $content_id, PDO::PARAM_INT);
				$stmt->execute();
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
	} else {
		if($title == '') {
			$error_title = true;
		}
		if($category == '') {
			$error_category = true;
		}

		$query = 'SELECT * FROM contents WHERE content_id = :content_id';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':content_id', $content_id, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch();
		$title = !$error_title ? $row->title : '';
		$description = $row->description;
		$short_description = $row->short_description;
		$image = $row->image;
		$category_id = !$error_category ? $row->category_id : '';

		$query = 'SELECT * FROM categories';
		$stmt = $db->prepare($query);
		$stmt->execute();

		$categories = $stmt->fetchAll();

		
	}
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	// Sanitize the parameter received by/via GET (4.3)
	$content_id = trim(filter_input(INPUT_GET, 'content_id', FILTER_VALIDATE_INT));

	// If it is an integer (4.3)
	if ($content_id) {
		// Saving the content (4.3)
		$query = 'SELECT * FROM contents WHERE content_id = :content_id';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':content_id', $content_id, PDO::PARAM_INT);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch();
			$title = $row->title;
			$description = $row->description;
			$short_description = $row->short_description;
			$image = $row->image;
			$category_id = $row->category_id;
		} else {
			// Showing error/page-not-found 
			header('Location: page-not-found.php');
			die();
		}

		$query = 'SELECT * FROM categories';
		$stmt = $db->prepare($query);
		$stmt->execute();

		$categories = $stmt->fetchAll();
	} else {
		// Showing error/page-not-found
		header('Location: page-not-found.php');
		die();
	}
}
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="wrapper">
    <?php
	$sidebar_item = 'contents';
	include 'includes/sidebar.php';
	?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Content - Edit</h1>
        </div>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="content_id" value="<?= $content_id; ?>">
            <input type="hidden" name="image_has_been_deleted" id="image_has_been_deleted">
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select <?= $error_category ? 'is-invalid' : ''; ?>" id="category" name="category">
                    <option value="">Select</option>
                    <?php foreach ($categories as $c) : ?>
                    <option value="<?= $c->category_id; ?>"
                        <?= $c->category_id == $category_id ? 'selected="selected"' : ''; ?>><?= $c->category_name; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    Please provide a valid category for the new Content.
                </div>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control <?= $error_title ? 'is-invalid' : ''; ?>" id="title" name="title"
                    placeholder="Title of the content" value="<?= $title; ?>">
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

            <?php if ($image != '') : ?>
            <div class="mb-3">
                <img src="../uploads/<?= $image; ?>" alt="<?= $title; ?>" width="200">
                <a href="#" class="me-2 text-warning" title="Delete this image"><i class="bi bi-trash3-fill"
                        id="delete-this-image"></i></a>
            </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="short_description" class="form-label">Short description</label>
                <textarea class="form-control" id="short_description" name="short_description"
                    placeholder="Short description of the content" rows="4"><?= $short_description; ?></textarea>
                <div class="invalid-feedback">
                    Please provide a valid Name for the new Content.
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Full description</label>
                <textarea class="form-control" id="description" name="description"
                    placeholder="Short description of the content"><?= $description; ?></textarea>
            </div>

            <div class="text-center mb-5">
                <button class="btn btn-warning" type="submit">Save</button>
                <a class="btn btn-secondary" href="contents.php">Cancel</a>
            </div>
            <p>&nbsp;</p>
        </form>
    </main>
</div>

<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script src="js/edit-content.js?js=<?= time(); ?>"></script>
<?php include 'includes/foot.php'; ?>