<?php
$query = 'SELECT * FROM contents WHERE slug_url = :slug_url';
$stmt = $db->prepare($query);
$stmt->bindValue(':slug_url', $param);
$stmt->execute();
$row = $stmt->fetch();

if ($stmt->rowCount() == 0) {
    header('Location: ' . $root_path);
    die();
}

$description = $row->description;
$content_id = $row->content_id;

$query = "SELECT c.*,u.full_name FROM comments c LEFT JOIN users u ON u.user_id = c.user_id WHERE c.content_id = :content_id AND c.is_active = true ORDER BY c.created_at DESC";
$stmt = $db->prepare($query);
$stmt->bindValue(':content_id', $content_id, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

$comments = [];
foreach ($rows as $row) {
    $comments[] = [
        'comment_id' => $row->comment_id,
        'full_name' => $row->full_name,
        'first_letter' => substr($row->full_name, 0, 1),
        'comment' => $row->comment,
        'date' => date('F j, Y, g:i a', strtotime($row->created_at))
    ];
}

if (count($comments) == 0) {
    $str_comments = 'No comments have been posted yet.';
} else if (count($comments) == 1) {
    $str_comments = '1 Comment';
} else {
    $str_comments = count($comments) . ' Comments';
}
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>

<div class="container my-5">
    <?= $description; ?>

    <div class="mt-5">
        <p class="h5" id="comments-header"><?= $str_comments; ?></p>
        <textarea rows="1" class="form-control border-success border-0 border-bottom rounded-0"
            placeholder="Add a comment <?= isset($_SESSION['jamesfood_user_logged']) ? '' : '(To post a comment the user must be registered)'; ?> "
            id="comment" name="comment"
            <?= isset($_SESSION['jamesfood_user_logged']) ? '' : 'disabled="disabled"'; ?>></textarea>
        <p class="mt-2 <?= isset($_SESSION['jamesfood_user_logged']) ? '' : 'd-none'; ?>">
            <button class="btn btn-secondary btn-sm" id="cancel-comment">Cancel</button>
            <button class="btn btn-warning btn-sm" id="add_a_comment">Comment</button>
        </p>
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-warning position-absolute d-none" role="status" id="spinner">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="my-4" id="comments-container">
        <?php foreach ($comments as $c) : ?>
        <div class="bg-success-subtle rounded-3 mb-3 p-3 d-flex w-100">
            <div>
                <div class="logged-user bg-success rounded-circle text-white text-center me-3">
                    <?= $c['first_letter']; ?></div>
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
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<input type="hidden" id="user_is_logged_in" name="user_is_logged_in"
    value="<?= isset($_SESSION['jamesfood_user_logged']) ? true : false; ?>">
<input type="hidden" id="content_id" name="content_id" value="<?= $content_id; ?>">
<input type="hidden" id="root_path" name="root_path" value="<?= $root_path; ?>">

<!-- This directs to the JS file that handles comments -->
<script src="<?= $root_path; ?>/js/review.js?js=<?= time(); ?>"></script>
<?php include 'includes/foot.php'; ?>