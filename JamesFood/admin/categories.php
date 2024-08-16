<?php
session_start();

require_once 'verify-private-section.php';

require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = trim(filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS));
	$category_id = trim(filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT));

	if ($action === 'delete category' && $category_id) {		
		$query = 'DELETE FROM categories WHERE category_id = :category_id';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'success',
				'message' => 'The Category has been deleted successfully.'
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
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="wrapper">
	<?php
	$sidebar_item = 'categories';
	include 'includes/sidebar.php';
	?>

	<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h1 class="h2">Categories</h1>
			<a class="btn btn-warning" href="add-category.php"><i class="bi bi-plus-square-fill"></i> Add</a>
		</div>
		
		<?php include 'flash-message.php'; ?>
		
		<div class="table-responsive pt-4">
			<?php if ($stmt->rowCount() > 0) : ?>
				<table class="table table-striped  table-hover">
					<?php while ($row = $stmt->fetch()) : ?>
						<tr>
							<td><?= $row->category_name; ?></td>
							<td class="text-end">
								<a href="edit-category.php?category_id=<?= $row->category_id; ?>" class="me-2 text-warning" title="Edit this category"><i class="bi bi-pencil-square"></i></a>
								<a href="#" class="me-2 text-warning" title="Hide this category"><i class="bi bi-eye-slash-fill"></i></a>
								<form method="post" class="d-inline">
									<input type="hidden" name="category_id" value="<?= $row->category_id; ?>">
									<input type="hidden" name="action" value="delete category">
									<a href="#" class="me-2 text-warning" title="Delete this category"><i class="bi bi-trash3-fill" data-action="delete category"></i></a>
								</form>
							</td>
						</tr>
					<?php endwhile; ?>
				</table>
			<?php else : ?>
				<p class="m-4 text-center">No categories has been added</p>
			<?php endif; ?>
		</div>
	</main>
</div>
<script>
	document.addEventListener('click', (e) => {
		if(e.target.dataset.action === 'delete category') {
			e.preventDefault();
			if(confirm('Are you sure you want to delete this category?')) {
				e.target.closest('form').submit();
			}
		}
	});
</script>
<?php include 'includes/foot.php'; ?>