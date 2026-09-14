<?php
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$flash_type = $flash['type'] ?? '';
$flash_message = $flash['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | LavaLust</title>
    <style>
        :root {
            --ink: #172033;
            --muted: #667085;
            --line: #e4e7ec;
            --surface: #ffffff;
            --canvas: #f6f7fb;
            --primary: #5b4ee8;
            --primary-dark: #493ccf;
            --danger: #c4323f;
            --success-bg: #ecfdf3;
            --success-text: #027a48;
            --error-bg: #fff1f3;
            --error-text: #b42318;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background:
                radial-gradient(circle at 8% 4%, rgba(91, 78, 232, .12), transparent 24rem),
                var(--canvas);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .shell {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 56px 0;
        }

        .eyebrow {
            margin: 0 0 8px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .page-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0;
            font-size: clamp(30px, 5vw, 46px);
            letter-spacing: -.04em;
        }

        .summary {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 15px;
        }

        .btn {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            border: 1px solid transparent;
            border-radius: 10px;
            font: inherit;
            font-size: 13px;
            font-weight: 750;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary { color: white; background: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-edit { color: #344054; border-color: #d0d5dd; background: white; }
        .btn-delete { color: var(--danger); border-color: #f3c4c8; background: #fff7f8; }

        .alert {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 650;
        }

        .alert-success { color: var(--success-text); background: var(--success-bg); }
        .alert-error { color: var(--error-text); background: var(--error-bg); }

        .card {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface);
            box-shadow: 0 20px 50px rgba(16, 24, 40, .07);
        }

        table { width: 100%; border-collapse: collapse; }

        th, td {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: middle;
        }

        th {
            color: var(--muted);
            background: #fafafa;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #fcfcff; }
        .name { font-weight: 750; }
        .subtle { color: var(--muted); font-size: 13px; }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .actions form { margin: 0; }
        .empty { padding: 72px 24px; text-align: center; }
        .empty h2 { margin: 0 0 8px; }
        .empty p { margin: 0 0 22px; color: var(--muted); }

        .footnote {
            margin-top: 18px;
            color: #98a2b3;
            font-size: 12px;
            text-align: center;
        }

        @media (max-width: 760px) {
            .shell { padding: 32px 0; }
            .page-head { align-items: stretch; flex-direction: column; }
            .page-head .btn { width: 100%; }
            .card { overflow-x: auto; }
            table { min-width: 720px; }
        }
    </style>
</head>
<body>
<main class="shell">
    <header class="page-head">
        <div>
            <p class="eyebrow">LavaLust MVC Laboratory</p>
            <h1>User management</h1>
            <p class="summary"><?= count($users) ?> <?= count($users) === 1 ? 'record' : 'records' ?> in the database</p>
        </div>
        <a class="btn btn-primary" href="<?= $escape(site_url('users/create')) ?>">Add user</a>
    </header>

    <?php if ($flash_message !== '') : ?>
        <div class="alert <?= $flash_type === 'success' ? 'alert-success' : 'alert-error' ?>" role="status">
            <?= $escape($flash_message) ?>
        </div>
    <?php endif; ?>

    <section class="card" aria-label="Users">
        <?php if ($users) : ?>
            <table>
                <thead>
                    <tr>
                        <th scope="col">User</th>
                        <th scope="col">Email</th>
                        <th scope="col">Username</th>
                        <th scope="col"><span class="subtle">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $row) : ?>
                        <tr>
                            <td>
                                <div class="name"><?= $escape($row['firstname'] . ' ' . $row['lastname']) ?></div>
                                <div class="subtle">User #<?= (int) $row['id'] ?></div>
                            </td>
                            <td><?= $escape($row['email']) ?></td>
                            <td><?= $escape($row['username']) ?></td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-edit" href="<?= $escape(site_url('users/edit/' . (int) $row['id'])) ?>">Edit</a>
                                    <form method="post" action="<?= $escape(site_url('users/delete/' . (int) $row['id'])) ?>" onsubmit="return confirm('Delete this user?');">
                                        <button class="btn btn-delete" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="empty">
                <h2>No users yet</h2>
                <p>Create the first record to begin the CRUD demonstration.</p>
                <a class="btn btn-primary" href="<?= $escape(site_url('users/create')) ?>">Create first user</a>
            </div>
        <?php endif; ?>
    </section>

    <p class="footnote">Routes → UsersController → UsersModel → MySQL → PHP views</p>
</main>
</body>
</html>
