<?php require_once './views/components/header.php'; ?>

<h1>My Schedule</h1>

<?php if (!empty($schedules)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tour</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($schedules as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= $s['tour_name'] ?></td>
                <td><?= $s['date'] ?></td>
                <td><?= $s['status'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Không có lịch trình nào!</p>
<?php endif; ?>

<?php require_once './views/components/footer.php'; ?>
