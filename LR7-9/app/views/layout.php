<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Moov — Образование за рубежом</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

    <!-- ===== ШАПКА ===== -->
    <header class="hero" style="height: auto; min-height: auto; padding: 30px 0; background: #1a1a2e;">
        <div class="container-fluid">
            <nav class="header-nav">
                <div class="nav-left-side">
                    <div class="logo-block">
                        <div class="logo">Study<span>&gt;</span>Moov</div>
                        <div class="logo-subtitle">Образование за рубежом</div>
                    </div>
                    <ul class="nav-menu">
                        <li><a href="/index.html">Главная</a></li>
                        <li><a href="/app.php?page=catalog">Университеты</a></li>
                        <li><a href="/app.php?page=cart">Корзина</a></li>
                        <li><a href="#contact">Контакты</a></li>
                    </ul>
                </div>
                <div class="header-actions">
                    <a href="tel:+74952644423" class="phone">+7 495 264-44-23</a>
                    <button class="btn btn-outline">Обратный звонок</button>
                    <div class="lang-switcher">
                        <span class="lang-icon">🌐</span>
                        <span class="lang-text dropdown">Ru</span>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- ===== ОСНОВНОЙ КОНТЕНТ ===== -->
    <main>
        <?php echo $content; ?>
    </main>

    <!-- ===== ФУТЕР ===== -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-links-grid">
                <div class="footer-col">
                    <h4>Компания</h4>
                    <ul>
                        <li><a href="#">Высшее образование</a></li>
                        <li><a href="#">Страны</a></li>
                        <li><a href="#">Услуги</a></li>
                        <li><a href="#">Контакты</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-social-row">
                <div class="social-icons">
                    <a href="#" class="social-link fb-round"><span class="footer-social-svg"></span></a>
                    <a href="#" class="social-link vk-round"><span class="footer-social-svg"></span></a>
                    <a href="#" class="social-link ig-round"><span class="footer-social-svg"></span></a>
                </div>
            </div>
            <div class="footer-sub-menu">
                <a href="#">О нас</a>
                <a href="#">Блог</a>
                <a href="#">Политика конфиденциальности</a>
                <a href="#">Контакты</a>
            </div>
            <div class="footer-copyright">
                <div class="footer-logo">Study&gt;<span>Moov</span></div>
                <p>© 2019-2021 StudyMOOV — образование за рубежом</p>
            </div>
        </div>
    </footer>

</body>
</html>