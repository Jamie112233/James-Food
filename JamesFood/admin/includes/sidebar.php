<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar position-fixed">
    <div class="position-sticky pt-4">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="categories.php"><span class="h4">Dashboard</span></a>
            </li>
            <li class="nav-item <?= $sidebar_item == 'categories' ? 'active' : ''; ?>">
                <a class="nav-link text-white" href="categories.php">Categories</a>
            </li>
            <li class="nav-item <?= $sidebar_item == 'contents' ? 'active' : ''; ?>">
                <a class="nav-link text-white" href="contents.php">Contents</a>
            </li>
            <li class="nav-item <?= $sidebar_item == 'comments' ? 'active' : ''; ?>">
                <a class="nav-link text-white" href="comments.php">Comments</a>
            </li>
            <li class="nav-item <?= $sidebar_item == 'users' ? 'active' : ''; ?>">
                <a class="nav-link text-white" href="users.php">Users</a>
            </li>
        </ul>
    </div>
</nav>