<?php
session_start();

require_once 'verify-private-section.php';

require_once 'connect.php';

$sql = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = filter_input(INPUT_POST,'action', FILTER_SANITIZE_SPECIAL_CHARS);
	$content_id = filter_input(INPUT_POST, 'content_id', FILTER_VALIDATE_INT);

	if($action == 'change category' && $_POST['category'] != '') {
		$sql = ' AND category_id = :category_id';
	}
	else if($action == 'delete content' && $content_id) {
		$query = 'DELETE FROM contents WHERE content_id = :content_id';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':content_id', $content_id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'success',
				'message' => 'The Content has been deleted successfully.'
			];
		} else {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'danger',
				'message' => 'There has been an error. Please try again later.'
			];
		}
	}
}

$query = "SELECT * FROM categories";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll();

$query = "SELECT * FROM contents WHERE true".$sql;
$stmt = $db->prepare($query);

if($sql != '') {
	$stmt->bindValue(':category_id', $_POST['category'], PDO::PARAM_INT);
}

$stmt->execute();

$content = $stmt->fetchAll();
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
			<h1 class="h2">Content</h1>
			<a class="btn btn-warning" href="add-content.php"><i class="bi bi-plus-square-fill"></i> Add</a>
		</div>

		<?php include 'flash-message.php'; ?>

		<form method="post">
			<input type="hidden" name="action" value="change category">
			<div class="mt-4">
				<select class="form-select w-auto" id="category" name="category">
					<option value="">All categories</option>
					<?php foreach ($categories as $c) : ?>
						<option value="<?= $c->category_id; ?>" <?= (isset($_POST['category']) && $c->category_id == $_POST['category']) ? 'selected="selected"' : ''; ?>><?= $c->category_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</form>

		<div class="table-responsive pt-4 mb-5">
			<?php if (count($content) > 0) : ?>
				<table class="table table-striped  table-hover">
					<?php foreach($content as $r) : ?>
						<?php $image = $r->image ? $r->image : 'Image-Not-Available.jpg'; ?>
						<tr>
							<td>
								<img src="../uploads/<?= $image; ?>" class="img-responsive" alt="<?= $r->title; ?>" width="45"> 
								<?= $r->title; ?>
							</td>
							<td class="text-end">
								<a href="edit-content.php?content_id=<?= $r->content_id; ?>" class="me-2 text-warning" title="Edit this content"><i class="bi bi-pencil-square"></i></a>
								<a href="#" class="me-2 text-warning" title="Hide this content"><i class="bi bi-eye-slash-fill"></i></a>
								<form method="post" class="d-inline">
									<input type="hidden" name="content_id" value="<?= $r->content_id; ?>">
									<input type="hidden" name="action" value="delete content">
									<a href="#" class="me-2 text-warning" title="Delete this content"><i class="bi bi-trash3-fill" data-action="delete content"></i></a>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php else : ?>
				<p class="m-4 text-center">No content has been added</p>
			<?php endif; ?>
		</div>
		<p class="mb-5">&nbsp;</p>
	</main>
</div>

<script>
	const category = document.getElementById('category');
	category.addEventListener('input', (e) => {
		category.closest('form').submit();
	});

	document.addEventListener('click', (e) => {
		if(e.target.dataset.action === 'delete content') {
			e.preventDefault();
			if(confirm('Are you sure you want to delete this content?')) {
				e.target.closest('form').submit();
			}
		}
	});
</script>

<?php include 'includes/foot.php'; ?>