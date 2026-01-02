<?php $user = Auth::user(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen – Postamt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="app-page">
    <div class="app-layout">
        <?php include __DIR__ . '/_sidebar.php'; ?>

        <main class="main-content">
            <header class="page-header">
                <div>
                    <h1>Einstellungen</h1>
                    <p>Verwalte dein Konto</p>
                </div>
            </header>

            <div style="max-width: 600px;">
                <section class="sidebar-card" style="margin-bottom: 24px;">
                    <h3 style="margin-bottom: 20px;">Profil</h3>

                    <form id="profile-form">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">E-Mail</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            <p style="font-size: 12px; color: #71717a; margin-top: 4px;">E-Mail kann aktuell nicht geaendert werden</p>
                        </div>

                        <button type="submit" class="btn-primary">Speichern</button>
                    </form>
                </section>

                <section class="sidebar-card" style="margin-bottom: 24px;">
                    <h3 style="margin-bottom: 20px;">Passwort aendern</h3>

                    <form id="password-form">
                        <div class="form-group">
                            <label for="current_password">Aktuelles Passwort</label>
                            <input type="password" id="current_password" name="current_password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">Neues Passwort</label>
                            <input type="password" id="new_password" name="new_password" minlength="8">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Passwort bestaetigen</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>

                        <button type="submit" class="btn-secondary">Passwort aendern</button>
                    </form>
                </section>

                <section class="sidebar-card" style="border-color: rgba(239, 68, 68, 0.3);">
                    <h3 style="margin-bottom: 12px; color: #ef4444;">Gefahrenzone</h3>
                    <p style="font-size: 14px; color: #a1a1aa; margin-bottom: 16px;">
                        Das Loeschen deines Accounts kann nicht rueckgaengig gemacht werden.
                    </p>
                    <button class="btn-secondary" style="border-color: #ef4444; color: #ef4444;" onclick="deleteAccount()">
                        Account loeschen
                    </button>
                </section>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Profil-Update kommt bald!');
        });

        document.getElementById('password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;

            if (newPass !== confirmPass) {
                alert('Passwoerter stimmen nicht ueberein!');
                return;
            }

            alert('Passwort-Aenderung kommt bald!');
        });

        function deleteAccount() {
            if (confirm('Bist du sicher? Diese Aktion kann nicht rueckgaengig gemacht werden.')) {
                alert('Account-Loeschung kommt bald!');
            }
        }

        async function logout() {
            await fetch('/api/auth/logout', { method: 'POST' });
            window.location.href = '/';
        }
    </script>

    <!-- 100% privacy-first analytics -->
    <script data-collect-dnt="true" async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
    <noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif?collect-dnt=true" alt="" referrerpolicy="no-referrer-when-downgrade"/></noscript>
</body>
</html>
