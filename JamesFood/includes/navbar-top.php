<nav class="navbar navbar-expand-lg bg-danger border border-danger border-start-0 border-end-0 sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand text-white me-5" href="./">

            <strong>JamesFood</strong>
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($categories as $c) : ?>
                <li class="nav-item me-3">
                    <a class="nav-link text-white text-uppercase <?= $slug_url === $c['slug_url'] ? 'active rounded' : ''; ?>"
                        href="<?= $root_path . '/' . $c['slug_url']; ?>"><?php echo $c['category_name']; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>

            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-warning me-4" type="submit">Search</button>
                <?php if (!isset($_SESSION['jamesfood_user_logged'])) : ?>
                <a class="btn btn-warning" href="<?= $root_path; ?>/sign-up">Sign&nbsp;up</a>
                <?php else : ?>
                <div class="flex-shrink-0 dropdown">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle text-white"
                        data-bs-toggle="dropdown" aria-expanded="false"
                        title="<?= $_SESSION['jamesfood_user_full_name']; ?>">
                        <span
                            class="rounded-circle text-white bg-success text-center d-inline-block logged-user"><?= $_SESSION['jamesfood_user_first_letter']; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= $root_path; ?>/sign-out">Sign out</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>