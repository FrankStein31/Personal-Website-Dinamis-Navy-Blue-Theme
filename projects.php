<?php
// projects.php - All Projects Portfolio Showcase Page
require_once __DIR__ . '/config.php';

$db_data = get_db_data();

$biodata = isset($db_data['biodata']) ? $db_data['biodata'] : [];
$portfolio = isset($db_data['portfolio']) ? $db_data['portfolio'] : [];
$socmed = isset($db_data['socmed']) ? $db_data['socmed'] : [];

$name = isset($biodata['name']) ? $biodata['name'] : '';
$role = isset($biodata['role']) ? $biodata['role'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Projects - <?= !empty($name) ? htmlspecialchars($name) : 'Personal Portfolio' ?></title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="All projects portfolio showcase. Explore complete project details, attachment files, and visit links.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS (Animate on Scroll) CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        /* CSS Custom Variables for Cinematic Navy Theme */
        :root {
            --rose-gold: #8da4c4; /* Silver/Charcoal Accent */
            --rose-gold-dark: #64748b;
            --rose-gold-light: #cbd5e1;
            --soft-blush: #0f172a; /* Navy Blue */
            --ivory-white: #0a1128; /* Midnight Blue */
            --charcoal: #020617; /* Deep Navy */
            --text-dark: #f8fafc; /* White Ivory */
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --font-heading: 'Montserrat', sans-serif;
            --font-body: 'Inter', sans-serif;
            --glass-bg: rgba(15, 23, 42, 0.65);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, #020617 0%, #0b1329 50%, #0f172a 100%);
            color: var(--text-dark);
            line-height: 1.6;
            position: relative;
        }

        /* Floating Navbar */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            transition: all 0.4s ease;
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--charcoal);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.5px;
        }

        .logo i {
            color: var(--rose-gold);
            font-size: 1.25rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-size: 0.95rem;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: var(--rose-gold);
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--rose-gold);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .mobile-nav-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--charcoal);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* All Projects Section */
        #all-projects-section {
            padding: 150px 0 100px 0;
            min-height: 85vh;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .back-btn-container {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 30px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--rose-gold);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            color: var(--rose-gold-dark);
            transform: translateX(-5px);
        }

        .section-header h1 {
            font-family: var(--font-heading);
            font-size: 2.8rem;
            color: var(--charcoal);
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            display: inline-block;
        }

        .section-header h1::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background-color: var(--rose-gold);
            margin: 12px auto 0 auto;
            border-radius: 2px;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1.05rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Projects Grid and Cards */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 35px;
        }

        .project-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 25px;
            backdrop-filter: blur(12px);
            box-shadow: var(--glass-shadow);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--rose-gold-light), var(--rose-gold));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(183, 110, 121, 0.14);
            border-color: rgba(183, 110, 121, 0.3);
        }

        .project-card:hover::before {
            opacity: 1;
        }

        /* Swiper Custom styling for Instagram look */
        .project-preview-box {
            padding: 0;
            cursor: pointer;
            overflow: hidden;
            width: 100%;
            height: 250px;
            background-color: var(--soft-blush);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            position: relative;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.2);
        }
        
        .project-swiper {
            width: 100%;
            height: 100%;
            position: relative;
        }
        
        .project-swiper .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #020617;
            overflow: hidden;
        }
        
        .project-swiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .project-swiper .swiper-slide iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .project-swiper .swiper-button-next,
        .project-swiper .swiper-button-prev {
            color: #ffffff;
            background: rgba(0, 0, 0, 0.4);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .project-swiper .swiper-button-next::after,
        .project-swiper .swiper-button-prev::after {
            font-size: 11px;
            font-weight: bold;
        }
        
        .project-swiper:hover .swiper-button-next,
        .project-swiper:hover .swiper-button-prev {
            opacity: 1;
        }
        
        .project-swiper .swiper-pagination-bullet {
            background: #ffffff;
            opacity: 0.5;
        }
        
        .project-swiper .swiper-pagination-bullet-active {
            background: var(--rose-gold);
            opacity: 1;
        }

        .project-card:hover .project-preview-box img {
            transform: scale(1.02);
        }

        .project-info h3 {
            font-family: var(--font-heading);
            font-size: 1.35rem;
            color: var(--text-dark);
            margin-bottom: 10px;
            font-weight: 700;
            line-height: 1.4;
        }

        .project-info p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.65;
            margin-bottom: 25px;
        }

        .project-footer {
            display: flex;
            gap: 12px;
            margin-top: auto;
        }

        .btn-portfolio-action {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            font-family: var(--font-body);
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
        }

        .btn-portfolio-primary {
            background-color: var(--rose-gold);
            color: #020617;
            border: none;
            box-shadow: 0 4px 10px rgba(141, 164, 196, 0.2);
        }

        .btn-portfolio-primary:hover {
            background-color: var(--rose-gold-dark);
            color: #ffffff;
            box-shadow: 0 6px 15px rgba(141, 164, 196, 0.3);
            transform: translateY(-1px);
        }

        .btn-portfolio-outline {
            background-color: transparent;
            color: var(--rose-gold);
            border: 1px solid var(--rose-gold);
        }

        .btn-portfolio-outline:hover {
            background-color: var(--rose-gold);
            color: #020617;
            box-shadow: 0 4px 12px rgba(141, 164, 196, 0.2);
            transform: translateY(-1px);
        }

        /* POP-UP MODAL MULTI-FORMAT */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-box {
            background-color: var(--soft-blush);
            border-radius: 28px;
            width: 90%;
            max-width: 950px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transform: scale(0.9);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.15);
        }

        .modal-overlay.active .modal-box {
            transform: scale(1);
        }

        .modal-header {
            padding: 20px 30px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--soft-blush);
        }

        .modal-title-container h3 {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 4px;
        }

        .modal-title-container p {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 2rem;
            cursor: pointer;
            transition: color 0.3s ease;
            line-height: 1;
        }

        .modal-close-btn:hover {
            color: var(--rose-gold);
        }

        /* Split-screen detail modal for project */
        .modal-grid-body {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            background-color: var(--charcoal);
        }

        .modal-slider-side {
            position: relative;
            background-color: #020617;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            min-height: 350px;
        }

        .modal-swiper {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .modal-swiper .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #020617;
        }

        .modal-swiper .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .modal-swiper .swiper-slide iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .modal-desc-side {
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            background-color: var(--soft-blush);
            border-left: 1px solid var(--border-color);
            overflow-y: auto;
            max-height: 60vh;
        }

        .modal-scroll-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 25px;
            white-space: pre-wrap;
            overflow-y: auto;
        }

        .modal-proj-action {
            margin-top: auto;
            padding-top: 15px;
        }

        /* Modal Swiper Navigation button overrides */
        .modal-swiper .swiper-button-next,
        .modal-swiper .swiper-button-prev {
            color: #ffffff;
            background: rgba(0, 0, 0, 0.5);
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }

        .modal-swiper .swiper-button-next::after,
        .modal-swiper .swiper-button-prev::after {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .modal-grid-body {
                grid-template-columns: 1fr;
                max-height: 65vh;
                overflow-y: auto;
            }
            .modal-slider-side {
                height: 250px;
                min-height: 250px;
            }
            .modal-desc-side {
                border-left: none;
                border-top: 1px solid var(--border-color);
                padding: 20px;
                max-height: none;
            }
        }

        /* FOOTER */
        footer {
            background-color: var(--charcoal);
            color: var(--ivory-white);
            padding: 60px 0;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        footer .logo {
            color: var(--ivory-white);
            justify-content: center;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }

        footer p {
            font-size: 0.95rem;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991px) {
            .nav-links {
                display: none;
            }
            .mobile-nav-toggle {
                display: block;
            }
            .section-header h1 {
                font-size: 2.3rem;
            }
        }

        @media (max-width: 767px) {
            .projects-grid {
                grid-template-columns: 1fr;
            }
            .project-card {
                padding: 20px;
            }
            .project-preview-box {
                height: 180px;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <header id="navbar">
        <div class="container">
            <nav>
                <a href="index.php" class="logo">
                    <i class="fa-solid fa-gem"></i>
                    <span><?= !empty($name) ? htmlspecialchars($name) : 'Personal Website' ?></span>
                </a>
                
                <ul class="nav-links">
                    <li><a href="index.php#hero">Home</a></li>
                    <li><a href="index.php#experiences">Experiences</a></li>
                    <li><a href="index.php#certificates">Certificates</a></li>
                    <li><a href="index.php#portfolio">Portfolio</a></li>
                </ul>

                <!-- Mobile Hamburger Toggle -->
                <button class="mobile-nav-toggle" onclick="window.location.href='index.php'" aria-label="Home">
                    <i class="fa-solid fa-house"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- ALL PROJECTS SHOWCASE -->
    <section id="all-projects-section">
        <div class="container">
            <div class="back-btn-container" data-aos="fade-right" data-aos-duration="1000">
                <a href="index.php#portfolio" class="btn-back">
                    <i class="fa-solid fa-arrow-left-long"></i>
                    <span>Back to Home</span>
                </a>
            </div>

            <div class="section-header" data-aos="fade-up" data-aos-duration="1000">
                <h1>Featured Projects Showcase</h1>
                <p>A comprehensive documentation and overview of all projects, tools, and platforms I have built.</p>
            </div>
            
            <?php if (empty($portfolio)): ?>
                <div style="text-align: center; padding: 50px 0;" data-aos="fade-up" data-aos-duration="1000">
                    <i class="fa-solid fa-folder-open" style="font-size: 4rem; color: var(--rose-gold-light); margin-bottom: 20px;"></i>
                    <p style="color: var(--text-muted); font-size: 1.1rem;">No projects uploaded yet.</p>
                </div>
            <?php else: ?>
                <div class="projects-grid">
                    <?php 
                    foreach ($portfolio as $proj): 
                        // Normalize files
                        $proj_files = [];
                        if (!empty($proj['files']) && is_array($proj['files'])) {
                            $proj_files = $proj['files'];
                        } elseif (!empty($proj['file'])) {
                            $proj_files = [$proj['file']];
                        }
                        $proj['files'] = $proj_files; // Ensure normalized files array is encoded in JSON
                    ?>
                        <div class="project-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100" onclick="openProjectModal(<?= htmlspecialchars(json_encode($proj)) ?>)" style="cursor: pointer;">
                            <div>
                                <div class="project-preview-box">
                                    <div class="swiper project-swiper">
                                        <div class="swiper-wrapper">
                                            <?php if (!empty($proj_files)): ?>
                                                <?php foreach ($proj_files as $file): ?>
                                                    <div class="swiper-slide">
                                                        <?php 
                                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                        if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                                            <img src="files/<?= htmlspecialchars($file) ?>" alt="<?= htmlspecialchars($proj['title']) ?>">
                                                        <?php elseif ($ext === 'pdf'): ?>
                                                            <iframe src="files/<?= htmlspecialchars($file) ?>#page=1&toolbar=0&navpanes=0&scrollbar=0" scrolling="no"></iframe>
                                                            <div class="iframe-overlay"></div>
                                                        <?php else: ?>
                                                            <div class="no-preview"><i class="fa-solid fa-file-invoice"></i></div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="swiper-slide">
                                                    <div class="no-preview"><i class="fa-solid fa-file-circle-exclamation"></i></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (count($proj_files) > 1): ?>
                                            <div class="swiper-pagination"></div>
                                            <div class="swiper-button-next"></div>
                                            <div class="swiper-button-prev"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="project-info">
                                    <h3><?= htmlspecialchars($proj['title']) ?></h3>
                                    <p><?= nl2br(htmlspecialchars($proj['description'] ?: '')) ?></p>
                                </div>
                            </div>
                            
                            <div class="project-footer" onclick="event.stopPropagation()">
                                <button class="btn-portfolio-action btn-portfolio-primary" onclick="openProjectModal(<?= htmlspecialchars(json_encode($proj)) ?>)">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <span>Detail Proyek</span>
                                </button>
                                
                                <?php if (!empty($proj['link'])): ?>
                                    <a href="<?= htmlspecialchars($proj['link']) ?>" target="_blank" class="btn-portfolio-action btn-portfolio-outline">
                                        <i class="fa-solid fa-play"></i>
                                        <span>Tonton Video</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <a href="index.php" class="logo">
                <i class="fa-solid fa-film"></i>
                <span><?= !empty($name) ? htmlspecialchars($name) : 'Personal Website' ?></span>
            </a>
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($name) ?>. All Rights Reserved.</p>
            <p style="font-size: 0.8rem; opacity: 0.7;"><a href="admin/index.php" style="color: var(--ivory-white); text-decoration: underline;">Administrator Panel</a>.</p>
        </div>
    </footer>

    <!-- POP-UP MODAL MULTI-FORMAT (FOR DOCUMENTS) -->
    <div class="modal-overlay" id="documentModal" onclick="closeDocModal(event)">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div class="modal-title-container">
                    <h3 id="modalDocTitle">Document Preview</h3>
                    <p id="modalDocIssuer">Document Issuer</p>
                </div>
                <button class="modal-close-btn" onclick="hideDocModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalDocBody">
                <!-- Content will be injected dynamically via JS -->
            </div>
        </div>
    </div>

    <!-- POP-UP MODAL DETAIL PROJECT (FOR PORTFOLIO) -->
    <div class="modal-overlay" id="projectModal" onclick="closeProjectModal(event)">
        <div class="modal-box" onclick="event.stopPropagation()" style="max-width: 950px;">
            <div class="modal-header">
                <div class="modal-title-container">
                    <h3 id="modalProjTitle">Project Detail</h3>
                </div>
                <button class="modal-close-btn" onclick="hideProjectModal()">&times;</button>
            </div>
            <div class="modal-grid-body">
                <div class="modal-slider-side">
                    <div class="swiper modal-swiper">
                        <div class="swiper-wrapper" id="modalSwiperWrapper">
                            <!-- Swiper slides injected here -->
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                <div class="modal-desc-side">
                    <h4 style="font-family: var(--font-heading); color: var(--text-dark); font-size: 1.3rem; margin-bottom: 12px;" id="modalProjTitleDetail">Project Title</h4>
                    <div class="modal-scroll-desc" id="modalProjDesc">
                        <!-- Project description here -->
                    </div>
                    <div class="modal-proj-action" id="modalProjAction">
                        <!-- Button Link here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Animation JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script>
        // Initialize AOS animations
        AOS.init({
            once: true,
            offset: 80
        });

        // Initialize Swiper for Portfolio Cards
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.project-swiper').forEach(swiperEl => {
                const slideCount = swiperEl.querySelectorAll('.swiper-slide').length;
                new Swiper(swiperEl, {
                    loop: slideCount > 1,
                    pagination: {
                        el: swiperEl.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    navigation: {
                        nextEl: swiperEl.querySelector('.swiper-button-next'),
                        prevEl: swiperEl.querySelector('.swiper-button-prev'),
                    },
                });
            });
        });

        let modalSwiper = null;

        function openProjectModal(proj) {
            const modal = document.getElementById('projectModal');
            document.getElementById('modalProjTitle').textContent = proj.title;
            document.getElementById('modalProjTitleDetail').textContent = proj.title;
            document.getElementById('modalProjDesc').textContent = proj.description || '';
            
            // Actions / Link button
            const actionContainer = document.getElementById('modalProjAction');
            actionContainer.innerHTML = '';
            if (proj.link) {
                actionContainer.innerHTML = `
                    <a href="${proj.link}" target="_blank" class="btn-portfolio-action btn-portfolio-primary" style="width: 100%; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fa-solid fa-play"></i>
                        <span>Tonton Video / Lihat Project</span>
                    </a>
                `;
            }
            
            // Populate Swiper Slides
            const wrapper = document.getElementById('modalSwiperWrapper');
            wrapper.innerHTML = '';
            
            // Normalize files key
            const files = proj.files || (proj.file ? [proj.file] : []);
            
            if (files.length === 0) {
                wrapper.innerHTML = `
                    <div class="swiper-slide">
                        <div class="no-preview" style="color: #94a3b8; font-size: 3rem; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-file-circle-exclamation"></i>
                            <span style="font-size: 1rem;">No Media Available</span>
                        </div>
                    </div>
                `;
            } else {
                files.forEach(file => {
                    const ext = file.split('.').pop().toLowerCase();
                    let slideContent = '';
                    
                    if (['jpg', 'jpeg', 'png'].includes(ext)) {
                        slideContent = `<img src="files/${file}" alt="${proj.title}">`;
                    } else if (ext === 'pdf') {
                        slideContent = `<iframe src="files/${file}#toolbar=0" type="application/pdf"></iframe>`;
                    } else {
                        slideContent = `
                            <div class="no-preview" style="color: #94a3b8; font-size: 3rem; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                <i class="fa-solid fa-file-invoice"></i>
                                <span style="font-size: 1rem;">Preview not supported</span>
                            </div>`;
                    }
                    
                    const slide = document.createElement('div');
                    slide.className = 'swiper-slide';
                    slide.innerHTML = slideContent;
                    wrapper.appendChild(slide);
                });
            }
            
            // Show Modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Initialize Swiper
            setTimeout(() => {
                if (modalSwiper) {
                    modalSwiper.destroy(true, true);
                }
                modalSwiper = new Swiper('.modal-swiper', {
                    loop: files.length > 1,
                    pagination: {
                        el: '.modal-swiper .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.modal-swiper .swiper-button-next',
                        prevEl: '.modal-swiper .swiper-button-prev',
                    },
                });
            }, 50);
        }

        function hideProjectModal() {
            const modal = document.getElementById('projectModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            if (modalSwiper) {
                modalSwiper.destroy(true, true);
                modalSwiper = null;
            }
        }

        function closeProjectModal(event) {
            if (event.target.id === 'projectModal') {
                hideProjectModal();
            }
        }

        // Close project modal on Escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideProjectModal();
            }
        });

        // Pop-up Modal Multi-format Detection and Rendering
        function openDocModal(fileUrl, docTitle, docIssuer) {
            const modal = document.getElementById('documentModal');
            const titleElem = document.getElementById('modalDocTitle');
            const issuerElem = document.getElementById('modalDocIssuer');
            const bodyElem = document.getElementById('modalDocBody');
            
            titleElem.textContent = docTitle;
            issuerElem.textContent = docIssuer;
            
            // Get file extension
            const extension = fileUrl.split('.').pop().toLowerCase();
            
            let contentHtml = '';
            
            if (extension === 'pdf') {
                contentHtml = `<iframe src="${fileUrl}" title="${docTitle}" type="application/pdf"></iframe>`;
            } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
                contentHtml = `<img src="${fileUrl}" alt="${docTitle}">`;
            } else {
                contentHtml = `<p style="color: var(--charcoal); font-weight: 500;">Direct preview is not supported for this file format.</p>`;
            }
            
            bodyElem.innerHTML = contentHtml;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Disable scroll under modal
        }

        function hideDocModal() {
            const modal = document.getElementById('documentModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto'; // Enable scroll
            
            // Clear content after animation
            setTimeout(() => {
                document.getElementById('modalDocBody').innerHTML = '';
            }, 400);
        }

        function closeDocModal(event) {
            if (event.target.id === 'documentModal') {
                hideDocModal();
            }
        }

        // Close doc modal on Escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideDocModal();
            }
        });
    </script>
</body>
</html>
