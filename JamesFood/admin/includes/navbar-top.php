<nav class="navbar navbar-light bg-danger border border-danger border-start-0 border-end-0 fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="index.php">
            <!-- <img src="images/logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top"> -->
            <strong>JamesFood</strong>
        </a>

        <?php if (isset($_SESSION['jamesfood_admin_logged'])): ?>
        <div class="flex-shrink-0 dropdown">
            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle text-white"
                data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar">AD</div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="sign-out.php">Sign out</a></li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</nav>
<div class="extra-height-space">&nbsp;</div>