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
    <title>Products | LavaLust</title>
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

        .topbar { border-bottom: 1px solid var(--line); background: rgba(255, 255, 255, .86); }

        .topbar-inner {
            width: min(1180px, calc(100% - 32px));
            min-height: 64px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand { font-weight: 850; letter-spacing: -.02em; }
        .account { display: flex; align-items: center; gap: 16px; color: var(--muted); font-size: 13px; }
        .logout { color: #344054; font-weight: 750; text-decoration: none; }

        .shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 46px 0 56px;
        }

        .eyebrow {
            margin: 0 0 8px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 850;
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

        h1 { margin: 0; font-size: clamp(30px, 5vw, 46px); letter-spacing: -.04em; }
        .summary { margin: 10px 0 0; color: var(--muted); font-size: 15px; }

        .btn {
            display: inline-flex;
            min-height: 40px;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
            border: 1px solid transparent;
            border-radius: 10px;
            font: inherit;
            font-size: 13px;
            font-weight: 750;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary { min-height: 44px; color: white; background: var(--primary); }
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

        .table-wrap { overflow-x: auto; }
        table { width: 100%; min-width: 980px; border-collapse: collapse; }

        th, td {
            padding: 15px 16px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: middle;
        }

        th {
            color: var(--muted);
            background: #fafafa;
            font-size: 11px;
            font-weight: 850;
            letter-spacing: .07em;
            text-transform: uppercase;
        }

        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #fcfcff; }
        .product-name { font-weight: 780; }
        .description { max-width: 280px; color: var(--muted); line-height: 1.45; }
        .number { font-variant-numeric: tabular-nums; white-space: nowrap; }

        .actions { display: flex; gap: 8px; }
        .empty { padding: 72px 24px; text-align: center; }
        .empty h2 { margin: 0 0 8px; }
        .empty p { margin: 0 0 22px; color: var(--muted); }

        .footnote { margin-top: 18px; color: #98a2b3; font-size: 12px; text-align: center; }

        @media (max-width: 680px) {
            .shell { padding-top: 30px; }
            .page-head { align-items: stretch; flex-direction: column; }
            .page-head .btn { width: 100%; }
            .account span { display: none; }
        }
    </style>
</head>
<body>
<nav class="topbar">
    <div class="topbar-inner">
        <div class="brand">Product Management</div>
        <div class="account">
            <span>Signed in as <?= $escape($username) ?></span>
            <a class="logout" href="<?= $escape(site_url('logout')) ?>">Logout</a>
        </div>
    </div>
</nav>

<main class="shell">
    <header class="page-head">
        <div>
            <p class="eyebrow">Laboratory Exercise No. 5</p>
            <h1>Product inventory</h1>
            <p class="summary"><?= count($products) ?> <?= count($products) === 1 ? 'product' : 'products' ?> in the catalog</p>
        </div>
        <a class="btn btn-primary" href="<?= $escape(site_url('products/create')) ?>">Add Product</a>
    </header>

    <?php if ($flash_message !== '') : ?>
        <div class="alert <?= $flash_type === 'success' ? 'alert-success' : 'alert-error' ?>" role="status">
            <?= $escape($flash_message) ?>
        </div>
    <?php endif; ?>

    <section class="card" aria-label="Products">
        <?php if ($products) : ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Date Created</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td class="number">#<?= (int) $product['id'] ?></td>
                                <td class="product-name"><?= $escape($product['product_name']) ?></td>
                                <td class="description"><?= $escape($product['description'] ?: '—') ?></td>
                                <td class="number">₱<?= number_format((float) $product['price'], 2) ?></td>
                                <td class="number"><?= number_format((int) $product['quantity']) ?></td>
                                <td class="number"><?= $escape(date('M j, Y g:i A', strtotime($product['created_at']))) ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="btn btn-edit" href="<?= $escape(site_url('products/edit/' . (int) $product['id'])) ?>">Edit</a>
                                        <a class="btn btn-delete" href="<?= $escape(site_url('products/delete/' . (int) $product['id'])) ?>">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="empty">
                <h2>No products yet</h2>
                <p>Add the first inventory item to begin.</p>
                <a class="btn btn-primary" href="<?= $escape(site_url('products/create')) ?>">Add Product</a>
            </div>
        <?php endif; ?>
    </section>

    <p class="footnote">Routes → AuthMiddleware → ProductController → ProductModel → MySQL → PHP views</p>
</main>
</body>
</html>
