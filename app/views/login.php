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
    <title>Login | Product Management</title>
    <style>
        :root {
            --ink: #172033;
            --muted: #667085;
            --line: #d0d5dd;
            --primary: #5b4ee8;
            --primary-dark: #493ccf;
            --danger: #b42318;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            color: var(--ink);
            background:
                radial-gradient(circle at 15% 15%, rgba(91, 78, 232, .18), transparent 28rem),
                radial-gradient(circle at 90% 90%, rgba(30, 184, 166, .12), transparent 24rem),
                #f6f7fb;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .login-card {
            width: min(440px, 100%);
            overflow: hidden;
            border: 1px solid #e4e7ec;
            border-radius: 20px;
            background: white;
            box-shadow: 0 28px 70px rgba(16, 24, 40, .12);
        }

        .heading { padding: 34px 34px 22px; }

        .mark {
            display: grid;
            width: 46px;
            height: 46px;
            margin-bottom: 22px;
            place-items: center;
            border-radius: 13px;
            color: white;
            background: var(--primary);
            font-size: 22px;
            font-weight: 850;
            box-shadow: 0 10px 22px rgba(91, 78, 232, .28);
        }

        .eyebrow {
            margin: 0 0 7px;
            color: var(--primary);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        h1 { margin: 0; font-size: 31px; letter-spacing: -.035em; }
        .subtitle { margin: 9px 0 0; color: var(--muted); line-height: 1.55; }
        form { padding: 0 34px 34px; }

        .alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 650;
        }

        .alert-error { color: var(--danger); background: #fff1f3; }
        .alert-info { color: #3448a5; background: #eef2ff; }

        label {
            display: block;
            margin: 16px 0 7px;
            color: #344054;
            font-size: 13px;
            font-weight: 750;
        }

        input {
            width: 100%;
            min-height: 47px;
            padding: 10px 13px;
            border: 1px solid var(--line);
            border-radius: 10px;
            color: var(--ink);
            background: white;
            font: inherit;
            outline: none;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(91, 78, 232, .1);
        }

        button {
            width: 100%;
            min-height: 47px;
            margin-top: 24px;
            border: 0;
            border-radius: 10px;
            color: white;
            background: var(--primary);
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        button:hover { background: var(--primary-dark); }

        .footer {
            padding: 16px 34px;
            border-top: 1px solid #eaecf0;
            color: #98a2b3;
            background: #fafafa;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
<main class="login-card">
    <header class="heading">
        <div class="mark" aria-hidden="true">P</div>
        <p class="eyebrow">Laboratory Exercise No. 5</p>
        <h1>Welcome back</h1>
        <p class="subtitle">Sign in to manage the product inventory.</p>
    </header>

    <form method="post" action="<?= $escape(site_url('login')) ?>" novalidate>
        <?php if (!empty($message)) : ?>
            <div class="alert alert-info" role="status"><?= $escape($message) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-error" role="alert"><?= $escape($error) ?></div>
        <?php endif; ?>

        <label for="username">Username</label>
        <input id="username" name="username" type="text" value="<?= $escape($username ?? '') ?>" maxlength="100" autocomplete="username" autofocus required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>

        <button type="submit">Sign in</button>
    </form>

    <div class="footer">LavaLust session authentication</div>
</main>
</body>
</html>
