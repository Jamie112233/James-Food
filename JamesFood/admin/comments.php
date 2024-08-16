<?php
session_start();

require_once 'verify-private-section.php';

require_once 'connect.php';

$sql = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = filter_input(INPUT_POST,'action', FILTER_SANITIZE_SPECIAL_CHARS);
	$comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);

    if($action == 'change content' && $_POST['content'] != '') {
		$sql = ' AND c.content_id = :content_id';
	}
	else if($action == 'delete comment' && $comment_id) {
		$query = 'DELETE FROM comments WHERE comment_id = :comment_id';
		$stmt = $db->prepare($query);
		$stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'success',
				'message' => 'The comment has been deleted successfully.'
			];
		} else {
			$_SESSION['jamesfood_flash_message'] = [
				'type' => 'danger',
				'message' => 'There has been an error. Please try again later.'
			];
		}
	}
}

$query = "SELECT * FROM contents WHERE content_id IN (SELECT DISTINCT content_id FROM comments)";
$stmt = $db->prepare($query);
$stmt->execute();
$rows = $stmt->fetchAll();

$content_with_comments = [];
foreach ($rows as $row) {
    $content_with_comments[] = [
        'content_id' => $row->content_id,
        'title' => $row->title
    ];
}

$query = "SELECT c.*,u.full_name FROM comments c LEFT JOIN users u ON u.user_id = c.user_id WHERE true ".$sql." ORDER BY c.created_at DESC";
$stmt = $db->prepare($query);

if($sql != '') {
	$stmt->bindValue(':content_id', $_POST['content'], PDO::PARAM_INT);
}

$stmt->execute();
$rows = $stmt->fetchAll();

$comments = [];
foreach ($rows as $row) {
    $comments[] = [
        'comment_id' => $row->comment_id,
        'full_name' => $row->full_name,
        'first_letter' => substr($row->full_name, 0, 1),
        'comment' => $row->comment,
        'date' => date('F j, Y, g:i a', strtotime($row->created_at)),
        'title_visibility' => $row->is_active ? 'Hide this comment' : 'Show this comment',
        'class_icon' => $row->is_active ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'
    ];
}
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="wrapper">
    <?php
    $sidebar_item = 'comments';
    include 'includes/sidebar.php';
    ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Comments</h1>
        </div>

        <?php include 'flash-message.php'; ?>

        <form method="post">
			<input type="hidden" name="action" value="change content">
			<div class="mt-4">
				<select class="form-select w-auto" id="content" name="content">
					<option value="">All pages</option>
					<?php foreach ($content_with_comments as $c) : ?>
						<option value="<?= $c['content_id']; ?>" <?= (isset($_POST['content']) && $c['content_id'] == $_POST['content']) ? 'selected="selected"' : ''; ?>><?= $c['title']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</form>
        
        <div class="my-4" id="comments-container">
            <?php foreach ($comments as $c) : ?>
                <div class="bg-success-subtle rounded-3 mb-3 p-3 d-flex w-100">
                    <div>
                        <div class="logged-user bg-success rounded-circle text-white text-center me-3"><?= $c['first_letter']; ?></div>
                    </div>
                    <div class="w-100">
                        <div>
                            <small class="fw-bold float-start"><?= $c['full_name']; ?></small>
                            <small class="float-end"><?= $c['date']; ?></small>
                        </div>
                        <div class="clearfix mb-2"></div>
                        <div>
                            <?= $c['comment']; ?>
                        </div>
                        <div class="text-end">
                            <span>
                                <a href="#" class="me-2 text-warning" title="<?= $c['title_visibility']; ?>"><i class="<?= $c['class_icon']; ?>" data-action="manage visibility" data-id="<?= $c['comment_id']; ?>"></i></a>
                            </span>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="comment_id" value="<?= $c['comment_id']; ?>">
                                <input type="hidden" name="action" value="delete comment">
                                <a href="#" class="me-2 text-warning" title="Delete this comment"><i class="bi bi-trash3-fill" data-action="delete comment"></i></a>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <p style="height:100px">&nbsp;</p>
        </div>
    </main>
</div>

<script src="js/comments.js?js=<?= time(); ?>"></script>
<?php include 'includes/foot.php'; ?>