<?php
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product | LavaLust</title>
    <style>
        :root { --ink:#172033; --muted:#667085; --line:#e4e7ec; --primary:#5b4ee8; --danger:#c4323f; --danger-dark:#a92531; }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; display:grid; place-items:center; padding:24px; color:var(--ink); background:radial-gradient(circle at 15% 10%,rgba(196,50,63,.1),transparent 25rem),#f6f7fb; font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; }
        .card { width:min(520px,100%); overflow:hidden; border:1px solid var(--line); border-radius:20px; background:white; box-shadow:0 24px 60px rgba(16,24,40,.1); }
        .content { padding:34px; }
        .icon { display:grid; width:48px; height:48px; margin-bottom:20px; place-items:center; border-radius:50%; color:var(--danger); background:#fff1f3; font-size:24px; font-weight:800; }
        .eyebrow { margin:0 0 7px; color:var(--danger); font-size:11px; font-weight:850; letter-spacing:.12em; text-transform:uppercase; }
        h1 { margin:0; font-size:30px; letter-spacing:-.035em; }
        .message { margin:10px 0 24px; color:var(--muted); line-height:1.55; }
        .product { padding:16px; border:1px solid var(--line); border-radius:12px; background:#fafafa; }
        .product strong { display:block; margin-bottom:5px; }
        .product span { color:var(--muted); font-size:13px; }
        .actions { display:flex; justify-content:flex-end; gap:10px; margin-top:26px; }
        .btn { display:inline-flex; min-height:44px; align-items:center; justify-content:center; padding:0 18px; border:1px solid transparent; border-radius:10px; font:inherit; font-size:14px; font-weight:780; text-decoration:none; cursor:pointer; }
        .btn-secondary { color:#344054; border-color:#d0d5dd; background:white; }
        .btn-danger { color:white; background:var(--danger); }
        .btn-danger:hover { background:var(--danger-dark); }
        .footer { padding:15px 34px; border-top:1px solid var(--line); color:#98a2b3; background:#fafafa; font-size:12px; }
        form { margin:0; }
        @media(max-width:480px) { .content{padding:26px}.actions{flex-direction:column-reverse}.btn{width:100%} }
    </style>
</head>
<body>
<main class="card">
    <div class="content">
        <div class="icon" aria-hidden="true">!</div>
        <p class="eyebrow">Delete confirmation</p>
        <h1>Delete this product?</h1>
        <p class="message">This action permanently removes the product from the inventory.</p>

        <div class="product">
            <strong><?= $escape($product['product_name']) ?></strong>
            <span>#<?= (int) $product['id'] ?> · ₱<?= number_format((float) $product['price'], 2) ?> · <?= number_format((int) $product['quantity']) ?> in stock</span>
        </div>

        <div class="actions">
            <a class="btn btn-secondary" href="<?= $escape(site_url('products')) ?>">Cancel</a>
            <form method="post" action="<?= $escape(site_url('products/delete/' . (int) $product['id'])) ?>">
                <button class="btn btn-danger" type="submit">Delete Product</button>
            </form>
        </div>
    </div>
    <div class="footer">Signed in as <?= $escape($username) ?> · <a href="<?= $escape(site_url('logout')) ?>">Logout</a></div>
</main>
</body>
</html>
