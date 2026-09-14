<?php
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$editing = ($mode ?? 'create') === 'edit';
$title = $editing ? 'Edit user' : 'Add user';
$description = $editing ? 'Update this database record.' : 'Create a new database record.';
$action = $editing
    ? site_url('users/update/' . (int) $id)
    : site_url('users/store');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escape($title) ?> | LavaLust</title>
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
            color: var(--ink);
            background:
                radial-gradient(circle at 90% 4%, rgba(91, 78, 232, .13), transparent 24rem),
                #f6f7fb;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .shell {
            width: min(720px, calc(100% - 32px));
            margin: 0 auto;
            padding: 48px 0;
        }

        .back {
            display: inline-block;
            margin-bottom: 18px;
            color: #475467;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .back:hover { color: var(--primary); }

        .card {
            overflow: hidden;
            border: 1px solid #e4e7ec;
            border-radius: 18px;
            background: white;
            box-shadow: 0 20px 50px rgba(16, 24, 40, .08);
        }

        header { padding: 30px 32px 24px; border-bottom: 1px solid #eaecf0; }

        .eyebrow {
            margin: 0 0 8px;
            color: var(--primary);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        h1 { margin: 0; font-size: 32px; letter-spacing: -.03em; }
        header p:last-child { margin: 8px 0 0; color: var(--muted); }
        form { padding: 28px 32px 32px; }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full { grid-column: 1 / -1; }

        label {
            display: block;
            margin-bottom: 7px;
            color: #344054;
            font-size: 13px;
            font-weight: 750;
        }

        input {
            width: 100%;
            min-height: 45px;
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

        input.invalid { border-color: #f04438; }

        .error {
            margin: 6px 0 0;
            color: var(--danger);
            font-size: 12px;
            font-weight: 650;
        }

        .alert {
            margin-bottom: 22px;
            padding: 13px 15px;
            border-radius: 10px;
            color: var(--danger);
            background: #fff1f3;
            font-size: 13px;
            font-weight: 700;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid #eaecf0;
        }

        .btn {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border: 1px solid transparent;
            border-radius: 10px;
            font: inherit;
            font-size: 14px;
            font-weight: 750;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-secondary { color: #344054; border-color: var(--line); background: white; }
        .btn-primary { color: white; background: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); }

        @media (max-width: 580px) {
            .shell { padding: 28px 0; }
            header, form { padding-left: 22px; padding-right: 22px; }
            .grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
            .actions { flex-direction: column-reverse; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
<main class="shell">
    <a class="back" href="<?= $escape(site_url('users')) ?>">← Back to users</a>

    <section class="card">
        <header>
            <p class="eyebrow">LavaLust MVC Laboratory</p>
            <h1><?= $escape($title) ?></h1>
            <p><?= $escape($description) ?></p>
        </header>

        <form method="post" action="<?= $escape($action) ?>" novalidate>
            <?php if (!empty($errors['general'])) : ?>
                <div class="alert" role="alert"><?= $escape($errors['general']) ?></div>
            <?php endif; ?>

            <div class="grid">
                <div>
                    <label for="firstname">First name</label>
                    <input id="firstname" name="firstname" type="text" maxlength="100" autocomplete="given-name" value="<?= $escape($user['firstname'] ?? '') ?>" class="<?= isset($errors['firstname']) ? 'invalid' : '' ?>" required>
                    <?php if (isset($errors['firstname'])) : ?>
                        <p class="error"><?= $escape($errors['firstname']) ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="lastname">Last name</label>
                    <input id="lastname" name="lastname" type="text" maxlength="100" autocomplete="family-name" value="<?= $escape($user['lastname'] ?? '') ?>" class="<?= isset($errors['lastname']) ? 'invalid' : '' ?>" required>
                    <?php if (isset($errors['lastname'])) : ?>
                        <p class="error"><?= $escape($errors['lastname']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="full">
                    <label for="email">Email address</label>
                    <input id="email" name="email" type="email" maxlength="150" autocomplete="email" value="<?= $escape($user['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>" required>
                    <?php if (isset($errors['email'])) : ?>
                        <p class="error"><?= $escape($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="full">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" minlength="3" maxlength="100" autocomplete="username" value="<?= $escape($user['username'] ?? '') ?>" class="<?= isset($errors['username']) ? 'invalid' : '' ?>" required>
                    <?php if (isset($errors['username'])) : ?>
                        <p class="error"><?= $escape($errors['username']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="actions">
                <a class="btn btn-secondary" href="<?= $escape(site_url('users')) ?>">Cancel</a>
                <button class="btn btn-primary" type="submit"><?= $editing ? 'Save changes' : 'Create user' ?></button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
