<?php
$query = 'SELECT * FROM contents WHERE category_id IN (SELECT category_id FROM categories WHERE slug_url = :slug_url)';
$stmt = $db->prepare($query);
$stmt->bindValue(':slug_url',$_GET['slugurl']);
$stmt->execute();
$rows = $stmt->fetchAll();

$content = [];
foreach($rows as $row) {
    $image = $row->image ? $row->image : 'Image-Not-Available.jpg';
    $content[] = [
        'title' => $row->title,
        'image' => $image,
        'short_description' => $row->short_description,
        'slug_url' => $row->slug_url
    ];
}
?>

<?php include 'includes/head.php'; ?>

<?php include 'includes/navbar-top.php'; ?>


<div class="container mt-5">
    <div class="row">
        <?php if(count($content) > 0): ?>
            <?php foreach($content as $c): ?>
                <div class="col-lg-3 col-md-4 col-6 mx-auto">
                    <a href="<?= $slug_url; ?>/review/<?= $c['slug_url']; ?>" class="text-decoration-none">
                        <img src="./uploads/<?= $c['image']; ?>" class="img-thumbnail" alt="<?= $c['title']; ?>">
                    </a>
                    <p class="h4 my-3"><a href="<?= $slug_url; ?>/review/<?= $c['slug_url']; ?>" class="text-decoration-none"><?= $c['title']; ?></a></p>
                    <p><?= $c['short_description']; ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/foot.php'; ?>