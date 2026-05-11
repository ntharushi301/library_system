<div class="sidebar">
    <div class="brand">
        <i class="bi bi-book-half" style="font-size:1.5rem;"></i>
        LibraryMS
    </div>
    <div class="mt-2">
        <div class="nav-label">Main</div>
        <a href="/library_system/dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
            <i class="bi bi-grid"></i> Dashboard
        </a>

        <div class="nav-label">Catalogue</div>
        <a href="/library_system/categories/index.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'],'categories')!==false?'active':'' ?>">
            <i class="bi bi-tags"></i> Book Categories
        </a>
        <a href="/library_system/books/index.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'],'books')!==false?'active':'' ?>">
            <i class="bi bi-journals"></i> Books
        </a>

        <div class="nav-label">People</div>
        <a href="/library_system/members/index.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'],'members')!==false?'active':'' ?>">
            <i class="bi bi-people"></i> Members
        </a>

        <div class="nav-label">Transactions</div>
        <a href="/library_system/borrowers/index.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'],'borrowers')!==false?'active':'' ?>">
            <i class="bi bi-arrow-left-right"></i> Book Borrowing
        </a>
        <a href="/library_system/fines/index.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'],'fines')!==false?'active':'' ?>">
            <i class="bi bi-cash-coin"></i> Fines
        </a>

        <div class="nav-label">Account</div>
        <a href="/library_system/auth/logout.php" class="nav-link text-danger">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>
