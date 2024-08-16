<?php if (isset($_SESSION['jamesfood_flash_message'])) : ?>
    <div class="alert alert-<?= $_SESSION['jamesfood_flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['jamesfood_flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['jamesfood_flash_message']); ?>
<?php endif; ?>