<?php
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$editing = ($mode ?? 'create') === 'edit';
$title = $editing ? 'Edit Product' : 'Add Product';
$action = $editing
    ? site_url('products/edit/' . (int) $id)
    : site_url('products/create');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escape($title) ?> | LavaLust</title>
    <style>
        :root { --ink:#172033; --muted:#667085; --line:#d0d5dd; --primary:#5b4ee8; --primary-dark:#493ccf; --danger:#b42318; }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; color:var(--ink); background:radial-gradient(circle at 90% 4%,rgba(91,78,232,.13),transparent 24rem),#f6f7fb; font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; }
        .topbar { border-bottom:1px solid #e4e7ec; background:rgba(255,255,255,.86); }
        .topbar-inner { width:min(780px,calc(100% - 32px)); min-height:62px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; gap:20px; }
        .brand { font-weight:850; }
        .nav-links { display:flex; gap:16px; }
        .nav-links a { color:#475467; font-size:13px; font-weight:750; text-decoration:none; }
        .shell { width:min(780px,calc(100% - 32px)); margin:0 auto; padding:38px 0 52px; }
        .back { display:inline-block; margin-bottom:18px; color:#475467; font-size:14px; font-weight:700; text-decoration:none; }
        .card { overflow:hidden; border:1px solid #e4e7ec; border-radius:18px; background:white; box-shadow:0 20px 50px rgba(16,24,40,.08); }
        header { padding:29px 32px 23px; border-bottom:1px solid #eaecf0; }
        .eyebrow { margin:0 0 8px; color:var(--primary); font-size:11px; font-weight:850; letter-spacing:.12em; text-transform:uppercase; }
        h1 { margin:0; font-size:32px; letter-spacing:-.03em; }
        header p:last-child { margin:8px 0 0; color:var(--muted); }
        form { padding:28px 32px 32px; }
        .grid { display:grid; grid-template-columns:2fr 1fr; gap:20px; }
        .full { grid-column:1 / -1; }
        label { display:block; margin-bottom:7px; color:#344054; font-size:13px; font-weight:750; }
        input, textarea { width:100%; padding:10px 13px; border:1px solid var(--line); border-radius:10px; color:var(--ink); background:white; font:inherit; outline:none; }
        input { min-height:45px; }
        textarea { min-height:125px; resize:vertical; line-height:1.5; }
        input:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(91,78,232,.1); }
        .invalid { border-color:#f04438; }
        .error { margin:6px 0 0; color:var(--danger); font-size:12px; font-weight:650; }
        .alert { margin-bottom:22px; padding:13px 15px; border-radius:10px; color:var(--danger); background:#fff1f3; font-size:13px; font-weight:700; }
        .actions { display:flex; justify-content:flex-end; gap:10px; margin-top:28px; padding-top:24px; border-top:1px solid #eaecf0; }
        .btn { display:inline-flex; min-height:44px; align-items:center; justify-content:center; padding:0 18px; border:1px solid transparent; border-radius:10px; font:inherit; font-size:14px; font-weight:750; text-decoration:none; cursor:pointer; }
        .btn-secondary { color:#344054; border-color:var(--line); background:white; }
        .btn-primary { color:white; background:var(--primary); }
        .btn-primary:hover { background:var(--primary-dark); }
        @media(max-width:600px) { header,form{padding-left:22px;padding-right:22px}.grid{grid-template-columns:1fr}.full{grid-column:auto}.actions{flex-direction:column-reverse}.btn{width:100%} }
    </style>
</head>
<body>
<nav class="topbar">
    <div class="topbar-inner">
        <div class="brand">Product Management</div>
        <div class="nav-links"><a href="<?= $escape(site_url('products')) ?>">Products</a><a href="<?= $escape(site_url('logout')) ?>">Logout</a></div>
    </div>
</nav>

<main class="shell">
    <a class="back" href="<?= $escape(site_url('products')) ?>">← Back to products</a>
    <section class="card">
        <header>
            <p class="eyebrow">Laboratory Exercise No. 5</p>
            <h1><?= $escape($title) ?></h1>
            <p><?= $editing ? 'Update the selected inventory record.' : 'Create a new inventory record.' ?></p>
        </header>

        <form method="post" action="<?= $escape($action) ?>" novalidate>
            <?php if (!empty($errors['general'])) : ?><div class="alert" role="alert"><?= $escape($errors['general']) ?></div><?php endif; ?>

            <div class="grid">
                <div class="full">
                    <label for="product_name">Product Name</label>
                    <input id="product_name" name="product_name" type="text" maxlength="100" value="<?= $escape($product['product_name'] ?? '') ?>" class="<?= isset($errors['product_name']) ? 'invalid' : '' ?>" required autofocus>
                    <?php if (isset($errors['product_name'])) : ?><p class="error"><?= $escape($errors['product_name']) ?></p><?php endif; ?>
                </div>

                <div class="full">
                    <label for="description">Description <span style="color:#98a2b3;font-weight:500">(optional)</span></label>
                    <textarea id="description" name="description"><?= $escape($product['description'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="price">Price</label>
                    <input id="price" name="price" type="number" min="0" max="99999999.99" step="0.01" value="<?= $escape($product['price'] ?? '') ?>" class="<?= isset($errors['price']) ? 'invalid' : '' ?>" required>
                    <?php if (isset($errors['price'])) : ?><p class="error"><?= $escape($errors['price']) ?></p><?php endif; ?>
                </div>

                <div>
                    <label for="quantity">Quantity</label>
                    <input id="quantity" name="quantity" type="number" min="0" step="1" value="<?= $escape($product['quantity'] ?? '') ?>" class="<?= isset($errors['quantity']) ? 'invalid' : '' ?>" required>
                    <?php if (isset($errors['quantity'])) : ?><p class="error"><?= $escape($errors['quantity']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="actions">
                <a class="btn btn-secondary" href="<?= $escape(site_url('products')) ?>">Cancel</a>
                <button class="btn btn-primary" type="submit"><?= $editing ? 'Save Changes' : 'Create Product' ?></button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
