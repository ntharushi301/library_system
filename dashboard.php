<?php
require_once 'includes/header.php';
require_once 'config/db.php';

$books    = $conn->query("SELECT COUNT(*) AS c FROM book")->fetch_assoc()['c'];
$members  = $conn->query("SELECT COUNT(*) AS c FROM member")->fetch_assoc()['c'];
$borrowed = $conn->query("SELECT COUNT(*) AS c FROM bookborrower WHERE borrow_status='borrowed'")->fetch_assoc()['c'];
$fines    = $conn->query("SELECT SUM(fine_amount) AS c FROM fine")->fetch_assoc()['c'] ?? 0;
?>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="mb-0 fw-bold">Dashboard</h5>
            <small class="text-muted">Welcome back, <?= htmlspecialchars($_SESSION['first_name']) ?>!</small>
        </div>
        <div class="text-muted" style="font-size:0.9rem;"><i class="bi bi-calendar3 me-1"></i><?= date('D, d M Y') ?></div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card p-4 text-center">
                <div style="font-size:2.2rem; color:#1a237e;"><i class="bi bi-journals"></i></div>
                <h3 class="fw-bold mt-2 mb-0"><?= $books ?></h3>
                <p class="text-muted mb-0">Total Books</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 text-center">
                <div style="font-size:2.2rem; color:#2e7d32;"><i class="bi bi-people"></i></div>
                <h3 class="fw-bold mt-2 mb-0"><?= $members ?></h3>
                <p class="text-muted mb-0">Members</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 text-center">
                <div style="font-size:2.2rem; color:#e65100;"><i class="bi bi-arrow-left-right"></i></div>
                <h3 class="fw-bold mt-2 mb-0"><?= $borrowed ?></h3>
                <p class="text-muted mb-0">Books Borrowed</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 text-center">
                <div style="font-size:2.2rem; color:#c62828;"><i class="bi bi-cash-coin"></i></div>
                <h3 class="fw-bold mt-2 mb-0">LKR <?= number_format($fines) ?></h3>
                <p class="text-muted mb-0">Total Fines</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Borrowings</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Book</th><th>Member</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php
                        $rows = $conn->query("SELECT bb.borrow_status, b.book_name, CONCAT(m.first_name,' ',m.last_name) AS mname FROM bookborrower bb JOIN book b ON bb.book_id=b.book_id JOIN member m ON bb.member_id=m.member_id ORDER BY bb.borrow_id DESC LIMIT 5");
                        while($r = $rows->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($r['book_name']) ?></td>
                            <td><?= htmlspecialchars($r['mname']) ?></td>
                            <td><span class="badge badge-status-<?= $r['borrow_status'] ?>"><?= ucfirst($r['borrow_status']) ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0"><i class="bi bi-cash me-2 text-danger"></i>Recent Fines</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Member</th><th>Book</th><th>Amount</th></tr></thead>
                        <tbody>
                        <?php
                        $frows = $conn->query("SELECT f.fine_amount, b.book_name, CONCAT(m.first_name,' ',m.last_name) AS mname FROM fine f JOIN book b ON f.book_id=b.book_id JOIN member m ON f.member_id=m.member_id ORDER BY f.fine_id DESC LIMIT 5");
                        while($fr = $frows->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fr['mname']) ?></td>
                            <td><?= htmlspecialchars($fr['book_name']) ?></td>
                            <td class="text-danger fw-semibold">LKR <?= $fr['fine_amount'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
