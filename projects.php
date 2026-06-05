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
        /* =============================================
           CINEMATIC STUDIO THEME — PALETTE v2.0
           ============================================= */
        :root {
            --bg-deep:        #081A2E;   /* Deepest Cinema Shadow */
            --bg-card:        #0E2A47;   /* Studio Room Grid */
            --border-steel:   #1F4E6B;   /* Matte Steel Blue */
            --text-muted:     #9FB8C9;   /* Light Slate Blue */
            --text-main:      #F2F5F7;   /* Crisp Ice White */

            /* Legacy aliases kept for compat */
            --bg-deep-navy:   #081A2E;
            --bg-card-glass:  rgba(14, 42, 71, 0.75);
            --accent-cyan:    #F2F5F7;
            --accent-blue:    #9FB8C9;
            --glass-border:   rgba(31, 78, 107, 0.6);
            --font-heading:   'Montserrat', sans-serif;
            --font-body:      'Inter', sans-serif;
            --neon-glow:      0 0 20px rgba(242, 245, 247, 0.12);
            --steel-glow:     0 0 25px rgba(31, 78, 107, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        html {
            overflow-x: hidden;
        }

        body {
            overflow-x: hidden;
            position: relative;
            font-family: var(--font-body);
            background-color: var(--bg-deep);
            background-image:
                radial-gradient(ellipse at 15% 10%, rgba(31,78,107,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 85%, rgba(31,78,107,0.12) 0%, transparent 50%);
            color: var(--text-main);
            line-height: 1.7;
        }

        .container {
            width: 90%;
            max-width: 1240px;
            margin: 0 auto;
        }

        /* Abstract ambient blobs */
        @keyframes pulseGlow {
            0%   { transform: scale(1) translate(0,0);       opacity: 0.5; }
            50%  { transform: scale(1.12) translate(30px,-20px); opacity: 0.75; }
            100% { transform: scale(1) translate(0,0);       opacity: 0.5; }
        }

        .glow-blob {
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(31,78,107,0.12) 0%, rgba(8,26,46,0) 70%);
            pointer-events: none;
            z-index: -1;
            filter: blur(90px);
            animation: pulseGlow 14s ease-in-out infinite alternate;
        }

        /* ── FIXED CINEMATIC NAVBAR ── */
        header {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            z-index: 1000;
            padding: 28px 0;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        header.scrolled {
            background: rgba(8, 26, 46, 0.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            padding: 16px 0;
            border-bottom: 1px solid var(--border-steel);
            box-shadow: 0 8px 30px rgba(0,0,0,0.6);
        }

        nav { display: flex; justify-content: space-between; align-items: center; }

        .logo {
            font-family: var(--font-heading);
            font-weight: 900;
            font-size: 1.3rem;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .logo i { color: var(--text-muted); font-size: 1.1rem; }

        .nav-links { display: flex; list-style: none; gap: 38px; align-items: center; }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: color 0.3s ease;
            position: relative;
            padding-bottom: 4px;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 1px;
            background: var(--text-main);
            transition: width 0.3s ease;
        }
        .nav-links a:hover { color: var(--text-main); }
        .nav-links a:hover::after { width: 100%; }

        /* ── SECTIONS GENERAL ── */
        section { padding: 140px 0; position: relative; }

        .section-header { text-align: center; margin-bottom: 80px; }

        .section-header h2 {
            font-family: var(--font-heading);
            font-size: 2.6rem;
            color: var(--text-main);
            font-weight: 900;
            letter-spacing: 3px;
            margin-bottom: 16px;
            text-transform: uppercase;
        }
        .section-header h2 span { color: var(--text-muted); }

        .section-header p {
            color: var(--text-muted);
            font-size: 0.98rem;
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Steel divider under heading */
        .section-header::after {
            content: '';
            display: block;
            width: 50px; height: 2px;
            background: var(--border-steel);
            margin: 22px auto 0;
        }

        /* ── HERO SECTION — ASYMMETRIC FILM POSTER ── */
        #hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 120px;
            background:
                linear-gradient(160deg, rgba(31,78,107,0.08) 0%, transparent 60%);
        }

        .hero-layout {
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            gap: 90px;
            align-items: center;
        }

        /* LEFT — Film Poster Frame */
        .hero-image-side {
            position: relative;
            display: flex;
            justify-content: center;
        }

        .profile-card-wrapper {
            position: relative;
            width: 100%;
            max-width: 360px;
            aspect-ratio: 3 / 4;
            border-radius: 16px;
            border: 1px solid var(--border-steel);
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(31,78,107,0.3), 0 40px 80px rgba(0,0,0,0.6);
            transition: box-shadow 0.5s ease, transform 0.5s ease;
        }
        .profile-card-wrapper:hover {
            transform: translateY(-8px);
            box-shadow: 0 0 0 1px var(--border-steel), 0 50px 100px rgba(0,0,0,0.7), var(--steel-glow);
        }

        .profile-inner-frame { width: 100%; height: 100%; position: relative; }

        .profile-inner-frame img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center top;
            display: block;
            transition: transform 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .profile-card-wrapper:hover .profile-inner-frame img { transform: scale(1.06); }

        /* Dark gradient overlay at bottom of portrait */
        .profile-inner-frame::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 45%;
            background: linear-gradient(to top, var(--bg-deep) 0%, transparent 100%);
            pointer-events: none;
        }

        /* Floating Cinematic Badges */
        .floating-pill {
            position: absolute;
            background: rgba(8,26,46,0.88);
            border: 1px solid var(--border-steel);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 10px 20px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-main);
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            animation: floatingMotion 5s ease-in-out infinite alternate;
            z-index: 5;
            transition: all 0.3s ease;
        }
        .floating-pill:hover { border-color: var(--text-main); }

        @keyframes floatingMotion {
            0%   { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }

        .pill-1 { top: 20px; left: 20px; animation-delay: 0s; }
        .pill-2 { bottom: 30px; right: 20px; animation-delay: 1s; }
        .floating-pill i { color: var(--text-muted); font-size: 0.95rem; }

        /* RIGHT — Hero Text */
        .hero-text-side { display: flex; flex-direction: column; }

        .hero-badge {
            align-self: flex-start;
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-steel);
            padding: 6px 18px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .hero-text-side h1 {
            font-family: var(--font-heading);
            font-size: 4rem;
            font-weight: 900;
            line-height: 1.05;
            margin-bottom: 20px;
            letter-spacing: -1.5px;
            color: var(--text-main);
        }
        .hero-text-side h1 span { color: var(--text-muted); }

        .hero-role-title {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .hero-role-title::before {
            content: '';
            display: inline-block;
            width: 30px; height: 2px;
            background: var(--border-steel);
        }

        .hero-desc {
            font-size: 1rem;
            color: var(--text-muted);
            line-height: 1.85;
            margin-bottom: 44px;
            max-width: 480px;
        }

        /* Social Row */
        .social-container {
            border-top: 1px solid var(--border-steel);
            padding-top: 32px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .social-headline {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            opacity: 0.7;
        }
        .social-flex-row { display: flex; gap: 10px; }

        .social-neon-btn {
            width: 44px; height: 44px;
            border-radius: 8px;
            background: transparent;
            border: 1px solid var(--border-steel);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .social-neon-btn:hover {
            background: var(--border-steel);
            color: var(--text-main);
            transform: translateY(-4px);
            box-shadow: var(--steel-glow);
        }

        /* ══════════════════════════════════════════════
           FILMSTRIP TIMELINE — Production Ledger Style
           ══════════════════════════════════════════════ */
        #experiences { background: rgba(8,26,46,0.6); }

        .timeline-container {
            position: relative;
            max-width: 950px;
            margin: 0 auto;
        }

        /* Dashed center track like a film reel */
        .timeline-container::before {
            content: '';
            position: absolute;
            width: 1px;
            border-left: 2px dashed var(--border-steel);
            top: 10px; bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.5;
        }

        .timeline-node {
            position: relative;
            margin-bottom: 60px;
            width: 100%;
        }
        .timeline-node::after { content: ''; display: table; clear: both; }

        /* Film frame circle pointer */
        .timeline-pointer {
            width: 14px; height: 14px;
            border-radius: 50%;
            background: var(--bg-deep);
            border: 2px solid var(--text-main);
            position: absolute;
            left: 50%; top: 28px;
            transform: translateX(-50%);
            z-index: 5;
            box-shadow: 0 0 0 5px rgba(8,26,46,0.9), 0 0 18px rgba(242,245,247,0.3);
            transition: all 0.4s ease;
        }
        .timeline-node:hover .timeline-pointer {
            background: var(--text-main);
            transform: translateX(-50%) scale(1.5);
            box-shadow: 0 0 0 6px rgba(8,26,46,0.95), 0 0 30px rgba(242,245,247,0.5);
        }

        /* Card — industrial dark architecture */
        .timeline-bubble {
            width: 43%;
            background: var(--card-bg);
            border: 1px solid var(--border-steel);
            border-radius: 12px;
            padding: 32px 36px;
            position: relative;
            box-shadow: 0 12px 32px rgba(0,0,0,0.5);
            transition: all 0.45s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
        }
        .timeline-bubble::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--border-steel), var(--text-muted));
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .timeline-node:hover .timeline-bubble {
            transform: translateY(-6px) scale(1.01);
            border-color: var(--text-muted);
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
        }
        .timeline-node:hover .timeline-bubble::before { opacity: 1; }

        .timeline-node:nth-child(even) .timeline-bubble { float: right; }
        .timeline-node:nth-child(odd)  .timeline-bubble { float: left; }

        /* Year badge — top-right corner */
        .timeline-meta-year {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-muted);
            background: rgba(31,78,107,0.25);
            border: 1px solid var(--border-steel);
            padding: 4px 12px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .timeline-role {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            color: var(--text-main);
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .timeline-org {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }
        .timeline-org i { color: var(--border-steel); }

        .timeline-paragraph {
            color: var(--text-muted);
            font-size: 0.88rem;
            line-height: 1.7;
        }

        /* CERTIFICATES SECTION - GLASSMORPHIC SLIDER */
        .cert-slider-outer {
            position: relative;
            width: 100%;
        }

        .cert-swiper-container {
            overflow: hidden;
            width: 100%;
            padding: 20px 0 40px 0;
        }

        .cert-slider-track {
            display: flex;
            gap: 30px;
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .cert-glass-card {
            flex: 0 0 calc((100% - 60px) / 3);
            background: var(--bg-card-glass);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 24px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .cert-glass-card:hover {
            transform: translateY(-8px);
            border-color: rgba(0, 242, 254, 0.3);
            box-shadow: 0 30px 60px rgba(0, 242, 254, 0.08);
        }

        .cert-asset-box {
            width: 100%;
            height: 190px;
            background: #020711;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .cert-asset-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .cert-glass-card:hover .cert-asset-box img {
            transform: scale(1.06);
        }

        .cert-asset-box iframe {
            width: 100%;
            height: 100%;
            border: none;
            pointer-events: none;
        }

        .cert-asset-box .asset-shield-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: transparent;
            z-index: 2;
        }

        .cert-asset-box .icon-fallback {
            font-size: 3rem;
            color: rgba(159,184,201,0.3);
        }

        .cert-details h3 {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            color: var(--text-main);
            font-weight: 800;
            line-height: 1.4;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em;
            letter-spacing: 0.3px;
        }

        .cert-details p {
            color: var(--text-muted);
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
        }

        .cert-details p i { color: var(--border-steel); }

        .btn-action-trigger {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--border-steel);
            padding: 11px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            width: 100%;
        }

        .btn-action-trigger:hover {
            background: var(--border-steel);
            color: var(--text-main);
            box-shadow: var(--steel-glow);
        }

        /* Slider Controls */
        .slider-custom-controls {
            display: flex; align-items: center; justify-content: center;
            gap: 22px; margin-top: 28px;
        }
        .control-arrow-btn {
            width: 42px; height: 42px; border-radius: 8px;
            background: transparent; border: 1px solid var(--border-steel);
            color: var(--text-muted); font-size: 0.85rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s ease;
        }
        .control-arrow-btn:hover {
            background: var(--border-steel); color: var(--text-main);
        }
        .slider-pills-indicator { display: flex; gap: 6px; align-items: center; }
        .slider-pill-dot {
            width: 14px; height: 3px; border-radius: 4px;
            background: rgba(159,184,201,0.2); cursor: pointer; transition: all 0.3s ease;
        }
        .slider-pill-dot.active {
            width: 28px; background: var(--text-muted);
        }

        /* ── PORTFOLIO SHOWCASE — 16:9 CINEMATIC CARDS ── */
        .portfolio-matrix {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(370px, 1fr));
            gap: 28px;
        }

        .portfolio-neon-card {
            background: var(--card-bg);
            border: 1px solid var(--border-steel);
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.45s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .portfolio-neon-card:hover {
            transform: translateY(-10px);
            border-color: var(--text-muted);
            box-shadow: 0 30px 60px rgba(0,0,0,0.5), var(--steel-glow);
        }

        /* 16:9 widescreen viewport */
        .portfolio-carousel-viewport {
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #050D18;
            position: relative;
            overflow: hidden;
        }
        .project-swiper { width: 100%; height: 100%; }
        .project-swiper .swiper-slide img { width: 100%; height: 100%; object-fit: cover; }
        .project-swiper .swiper-slide iframe { width: 100%; height: 100%; border: none; }

        .project-swiper .swiper-button-next,
        .project-swiper .swiper-button-prev {
            color: var(--text-main);
            background: rgba(8,26,46,0.75);
            border: 1px solid var(--border-steel);
            width: 34px; height: 34px; border-radius: 6px;
            opacity: 0; transition: all 0.3s ease;
        }
        .project-swiper:hover .swiper-button-next,
        .project-swiper:hover .swiper-button-prev { opacity: 1; }
        .project-swiper .swiper-button-next::after,
        .project-swiper .swiper-button-prev::after { font-size: 11px; font-weight: bold; }
        .project-swiper .swiper-pagination-bullet { background: var(--text-muted); opacity: 0.5; }
        .project-swiper .swiper-pagination-bullet-active { background: var(--text-main) !important; opacity: 1; }

        .portfolio-main-body { padding: 26px 28px; flex-grow: 1; }
        .portfolio-main-body h3 {
            font-family: var(--font-heading);
            font-size: 1.2rem; color: var(--text-main);
            font-weight: 800; margin-bottom: 10px; letter-spacing: 0.3px;
        }
        .portfolio-main-body p {
            color: var(--text-muted); font-size: 0.88rem; line-height: 1.65;
            display: -webkit-box; -webkit-line-clamp: 3;
            -webkit-box-orient: vertical; overflow: hidden;
        }

        .portfolio-interactive-footer { padding: 0 28px 28px; display: flex; gap: 10px; }

        .btn-internal-action {
            flex: 1; padding: 11px 14px; border-radius: 8px;
            font-size: 0.78rem; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; text-align: center; text-decoration: none;
            transition: all 0.3s ease; display: flex;
            align-items: center; justify-content: center; gap: 8px; cursor: pointer;
        }
        .btn-internal-primary {
            background: var(--text-main); color: var(--bg-deep); border: none;
        }

        .btn-internal-primary:hover {
            background: var(--text-muted);
            transform: translateY(-2px);
        }
        .btn-internal-outline {
            background: transparent; color: var(--text-muted);
            border: 1px solid var(--border-steel);
        }
        .btn-internal-outline:hover {
            background: var(--border-steel); color: var(--text-main);
            transform: translateY(-2px);
        }

        /* VIEW ALL PROJECTS pill button */
        .btn-view-all {
            display: inline-flex; align-items: center; gap: 12px;
            margin: 56px auto 0; padding: 18px 52px;
            border: 1px solid var(--border-steel);
            border-radius: 60px; color: var(--text-main);
            font-family: var(--font-heading); font-weight: 800;
            font-size: 0.85rem; letter-spacing: 2.5px; text-transform: uppercase;
            text-decoration: none; background: transparent;
            transition: all 0.4s ease; cursor: pointer;
        }
        .btn-view-all:hover {
            background: var(--text-main); color: var(--bg-deep);
            box-shadow: 0 0 40px rgba(242,245,247,0.2);
            transform: translateY(-3px);
        }
        .portfolio-cta { text-align: center; }
        .back-btn-container { display: flex; justify-content: flex-start; margin-bottom: 30px; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; transition: all 0.3s ease; padding: 10px 20px; border: 1px solid var(--border-steel); border-radius: 8px; }
        .btn-back:hover { color: var(--text-main); border-color: var(--text-main); transform: translateX(-5px); background: rgba(31,78,107,0.1); }


        /* ── PREMIUM VIDEO PLAYER MODAL ── */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(4, 10, 20, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
            padding: 24px;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-box {
            background-color: #060F1E;
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            width: 100%;
            max-width: 850px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(0,0,0,0.8);
            transform: scale(0.92);
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1.1);
        }

        .modal-overlay.active .modal-box { transform: scale(1); }

        .modal-header {
            padding: 25px 35px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title-group h3 {
            font-family: var(--font-heading);
            font-size: 1.3rem;
            color: var(--text-main);
            font-weight: 700;
        }

        .modal-title-group p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 2px;
        }

        .modal-close-trigger {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.3s;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close-trigger:hover {
            background: rgba(31,78,107,0.4);
            color: var(--text-main);
        }

        .modal-main-viewport {
            padding: 24px;
            overflow-y: auto;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #050D18;
        }
        .modal-main-viewport img { max-width:100%; max-height:60vh; border-radius:10px; object-fit:contain; }
        .modal-main-viewport iframe { width:100%; height:58vh; border:none; border-radius:10px; }

        /* Project split-pane */
        .modal-split-layout { display: flex; flex-direction: column; flex-grow: 1; overflow-y: auto; background-color: #050D18; max-height: 75vh; }
        .modal-gallery-pane { position: relative; background: #030A16; min-height: 450px; width: 100%; flex-shrink: 0; }
        .modal-swiper { width:100%; height:100%; position:absolute; }
        .modal-swiper .swiper-slide { display:flex; align-items:center; justify-content:center; }
        .modal-swiper .swiper-slide img { width:100%; height:100%; object-fit:contain; }
        .modal-swiper .swiper-slide iframe { width:100%; height:100%; border:none; }
        .modal-swiper .swiper-button-next, .modal-swiper .swiper-button-prev {
            color: var(--text-main); background: rgba(8,26,46,0.8);
            border: 1px solid var(--border-steel); width:34px; height:34px; border-radius:6px;
        }
        .modal-swiper .swiper-button-next::after, .modal-swiper .swiper-button-prev::after { font-size:11px; }

        .modal-text-pane { padding: 32px; display: flex; flex-direction: column; background: var(--card-bg); border-top: 1px solid var(--border-steel); }
        .modal-text-pane::-webkit-scrollbar { width: 4px; }
        .modal-text-pane::-webkit-scrollbar-track { background: transparent; }
        .modal-text-pane::-webkit-scrollbar-thumb { background: var(--border-steel); border-radius: 4px; }

        .modal-scrollable-text {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.8;
            margin-bottom: 30px;
            white-space: pre-wrap;
        }

        /* Modern Hamburger Toggle Mobile Menu */
        .mobile-nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--text-main);
            cursor: pointer;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--glass-border);
            transition: all 0.3s;
            z-index: 1002;
        }

        .mobile-nav-toggle:hover {
            background: rgba(0, 242, 254, 0.05);
            color: var(--accent-cyan);
        }

        /* Footer System */
        footer {
            background-color: #02060f;
            padding: 70px 0;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        footer .logo { justify-content: center; margin-bottom: 25px; }
        footer p { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 10px; }
        footer a { color: var(--text-muted); text-decoration: none; transition: color 0.3s; }
        footer a:hover { color: var(--accent-cyan); }

        /* MULTI-DEVICE RESPONSIVE LAYOUT MATRIX */
        @media (max-width: 991px) {
            #hero {
                min-height: auto;
                padding-top: 140px;
                padding-bottom: 80px;
                display: block;
            }
            .hero-layout {
                grid-template-columns: 1fr;
                gap: 55px;
                text-align: center;
            }
            .hero-badge { align-self: center; }
            .hero-image-side { order: -1; }
            .profile-card-wrapper { max-width: 340px; }
            .hero-text-side h1 { font-size: 2.8rem; }
            .hero-role-title { justify-content: center; }
            .hero-role-title::before { display: none; }
            .social-container { flex-direction: column; gap: 15px; }
            
            .floating-pill {
                padding: 8px 16px;
                font-size: 0.75rem;
            }
            .pill-1 { top: 20px; left: 20px; }
            .pill-2 { bottom: 20px; right: 20px; }

            .timeline-container::before { left: 30px; }
            .timeline-pointer { left: 30px; }
            .timeline-bubble { width: calc(100% - 60px); float: right !important; }

            .cert-glass-card { flex: 0 0 calc((100% - 30px) / 2); }
        }

        @media (max-width: 768px) {
            section { padding: 90px 0; }
            .section-header h2 { font-size: 2rem; }
            .mobile-nav-toggle { display: flex; }
            
            .nav-links {
                position: fixed;
                top: 0;
                right: 0;
                transform: translateX(100%);
                width: 80%;
                max-width: 300px;
                height: 100vh;
                background-color: rgba(2, 6, 15, 0.98);
                backdrop-filter: blur(25px);
                -webkit-backdrop-filter: blur(25px);
                flex-direction: column;
                justify-content: center;
                gap: 35px;
                padding: 40px;
                box-shadow: -10px 0 40px rgba(0, 0, 0, 0.6);
                transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                z-index: 1001;
                border-left: 1px solid var(--glass-border);
            }
            .nav-links.active {
                transform: translateX(0);
            }
            
            .cert-glass-card { flex: 0 0 100%; }

            .modal-split-layout { display: flex; flex-direction: column; flex-grow: 1; overflow-y: auto; background-color: #050D18; max-height: 75vh; }
            .modal-gallery-pane { position: relative; background: #030A16; min-height: 450px; width: 100%; flex-shrink: 0; }
            .modal-text-pane { padding: 32px; display: flex; flex-direction: column; background: var(--card-bg); border-top: 1px solid var(--border-steel); }
display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title-group h3 {
            font-family: var(--font-heading);
            font-size: 1.3rem;
            color: var(--text-main);
            font-weight: 700;
        }

        .modal-title-group p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 2px;
        }

        .modal-close-trigger {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.3s;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close-trigger:hover {
            background: rgba(31,78,107,0.4);
            color: var(--text-main);
        }

        .modal-main-viewport {
            padding: 24px;
            overflow-y: auto;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #050D18;
        }
        .modal-main-viewport img { max-width:100%; max-height:60vh; border-radius:10px; object-fit:contain; }
        .modal-main-viewport iframe { width:100%; height:58vh; border:none; border-radius:10px; }

        /* Project split-pane */
        .modal-split-layout { display: flex; flex-direction: column; flex-grow: 1; overflow-y: auto; background-color: #050D18; max-height: 75vh; }
        .modal-gallery-pane { position: relative; background: #030A16; min-height: 450px; width: 100%; flex-shrink: 0; }
        .modal-swiper { width:100%; height:100%; position:absolute; }
        .modal-swiper .swiper-slide { display:flex; align-items:center; justify-content:center; }
        .modal-swiper .swiper-slide img { width:100%; height:100%; object-fit:contain; }
        .modal-swiper .swiper-slide iframe { width:100%; height:100%; border:none; }
        .modal-swiper .swiper-button-next, .modal-swiper .swiper-button-prev {
            color: var(--text-main); background: rgba(8,26,46,0.8);
            border: 1px solid var(--border-steel); width:34px; height:34px; border-radius:6px;
        }
        .modal-swiper .swiper-button-next::after, .modal-swiper .swiper-button-prev::after { font-size:11px; }

        .modal-text-pane { padding: 32px; display: flex; flex-direction: column; background: var(--card-bg); border-top: 1px solid var(--border-steel); }
        .modal-text-pane::-webkit-scrollbar { width: 4px; }
        .modal-text-pane::-webkit-scrollbar-track { background: transparent; }
        .modal-text-pane::-webkit-scrollbar-thumb { background: var(--border-steel); border-radius: 4px; }

        .modal-scrollable-text {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.8;
            margin-bottom: 30px;
            white-space: pre-wrap;
        }

        /* Modern Hamburger Toggle Mobile Menu */
        .mobile-nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--text-main);
            cursor: pointer;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--glass-border);
            transition: all 0.3s;
            z-index: 1002;
        }

        .mobile-nav-toggle:hover {
            background: rgba(0, 242, 254, 0.05);
            color: var(--accent-cyan);
        }

        /* Footer System */
        footer {
            background-color: #02060f;
            padding: 70px 0;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        footer .logo { justify-content: center; margin-bottom: 25px; }
        footer p { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 10px; }
        footer a { color: var(--text-muted); text-decoration: none; transition: color 0.3s; }
        footer a:hover { color: var(--accent-cyan); }

        /* MULTI-DEVICE RESPONSIVE LAYOUT MATRIX */
        @media (max-width: 991px) {
            #hero {
                min-height: auto;
                padding-top: 140px;
                padding-bottom: 80px;
                display: block;
            }
            .hero-layout {
                grid-template-columns: 1fr;
                gap: 55px;
                text-align: center;
            }
            .hero-badge { align-self: center; }
            .hero-image-side { order: -1; }
            .profile-card-wrapper { max-width: 340px; }
            .hero-text-side h1 { font-size: 2.8rem; }
            .hero-role-title { justify-content: center; }
            .hero-role-title::before { display: none; }
            .social-container { flex-direction: column; gap: 15px; }
            
            .floating-pill {
                padding: 8px 16px;
                font-size: 0.75rem;
            }
            .pill-1 { top: 20px; left: 20px; }
            .pill-2 { bottom: 20px; right: 20px; }

            .timeline-container::before { left: 30px; }
            .timeline-pointer { left: 30px; }
            .timeline-bubble { width: calc(100% - 60px); float: right !important; }

            .cert-glass-card { flex: 0 0 calc((100% - 30px) / 2); }
        }

        @media (max-width: 768px) {
            section { padding: 90px 0; }
            .section-header h2 { font-size: 2rem; }
            .mobile-nav-toggle { display: flex; }
            
            .nav-links {
                position: fixed;
                top: 0;
                right: 0;
                transform: translateX(100%);
                width: 80%;
                max-width: 300px;
                height: 100vh;
                background-color: rgba(2, 6, 15, 0.98);
                backdrop-filter: blur(25px);
                -webkit-backdrop-filter: blur(25px);
                flex-direction: column;
                justify-content: center;
                gap: 35px;
                padding: 40px;
                box-shadow: -10px 0 40px rgba(0, 0, 0, 0.6);
                transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                z-index: 1001;
                border-left: 1px solid var(--glass-border);
            }
            .nav-links.active {
                transform: translateX(0);
            }
            
            .cert-glass-card { flex: 0 0 100%; }

            .modal-split-layout { display: flex; flex-direction: column; flex-grow: 1; overflow-y: auto; background-color: #050D18; max-height: 75vh; }
            .modal-gallery-pane { position: relative; background: #030A16; min-height: 450px; width: 100%; flex-shrink: 0; }
            .modal-text-pane { padding: 32px; display: flex; flex-direction: column; background: var(--card-bg); border-top: 1px solid var(--border-steel); }
        }

        @media (max-width: 480px) {
            .hero-text-side h1 { font-size: 2.2rem; }
            .profile-card-wrapper { max-width: 290px; }
            .floating-pill { padding: 6px 12px; font-size: 0.7rem; }
            .pill-1 { top: 15px; left: 15px; }
            .pill-2 { bottom: 15px; right: 15px; }
            .timeline-bubble { padding: 25px; }
        }
    </style>
</head>
<body>

    <div class="glow-blob" style="top: -100px; right: -100px;"></div>
    <div class="glow-blob" style="top: 35%; left: -250px; background: radial-gradient(circle, rgba(79, 172, 254, 0.05) 0%, rgba(3, 10, 22, 0) 70%); animation-delay: -3s;"></div>
    <div class="glow-blob" style="bottom: 10%; right: -200px; background: radial-gradient(circle, rgba(0, 242, 254, 0.05) 0%, rgba(3, 10, 22, 0) 70%); animation-delay: -6s;"></div>

    <!-- Navigation Bar -->
    <header id="navbar">
        <div class="container">
            <nav>
                <a href="index.php" class="logo">
                    <i class="fa-solid fa-clapperboard"></i>
                    <span><?= !empty($name) ? htmlspecialchars($name) : 'PORTFOLIO' ?></span>
                </a>
                
                <ul class="nav-links">
                    <li><a href="index.php#hero">Home</a></li>
                    <li><a href="index.php#experiences">Experiences</a></li>
                    <li><a href="index.php#certificates">Certificates</a></li>
                    <li><a href="index.php#portfolio">Portfolio</a></li>
                </ul>

                <!-- Mobile Hamburger Toggle -->
                <button class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
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
                <h2><span>PRODUCTION</span> LEDGER</h2>
                <p>A comprehensive documentation and overview of all projects, videography, and visual platforms I have produced.</p>
            </div>
            
            <?php if (empty($portfolio)): ?>
                <div style="text-align: center; padding: 50px 0;" data-aos="fade-up" data-aos-duration="1000">
                    <i class="fa-solid fa-folder-open" style="font-size: 4rem; color: var(--rose-gold-light); margin-bottom: 20px;"></i>
                    <p style="color: var(--text-muted); font-size: 1.1rem;">No projects uploaded yet.</p>
                </div>
            <?php else: ?>
                <div class="portfolio-matrix">
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
                        <div class="portfolio-neon-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100" onclick="openProjectModal(<?= htmlspecialchars(json_encode($proj)) ?>)" style="cursor: pointer;">
                            <div>
                                <div class="portfolio-carousel-viewport">
                                    <div class="swiper project-swiper">
                                        <div class="swiper-wrapper">
                                            <?php if (!empty($proj_files)): ?>
                                                <?php foreach ($proj_files as $file): ?>
                                                    <div class="swiper-slide">
                                                        <?php 
                                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                        if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                                            <img src="files/<?= htmlspecialchars($file) ?>" alt="Project Capture">
                                                        <?php elseif ($ext === 'pdf'): ?>
                                                            <iframe src="files/<?= htmlspecialchars($file) ?>#page=1&toolbar=0&navpanes=0&scrollbar=0" scrolling="no"></iframe>
                                                            <div class="iframe-overlay"></div>
                                                        <?php else: ?>
                                                            <div class="icon-fallback" style="font-size:3rem; color:var(--rose-gold); text-align: center;"><i class="fa-solid fa-clapperboard"></i></div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="swiper-slide">
                                                    <div class="icon-fallback" style="font-size:3rem; color:var(--text-muted); text-align: center;"><i class="fa-solid fa-folder-open"></i></div>
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
                                
                                <div class="portfolio-main-body">
                                    <h3><?= htmlspecialchars($proj['title']) ?></h3>
                                    <p><?= nl2br(htmlspecialchars($proj['description'] ?: '')) ?></p>
                                </div>
                            </div>
                            
                            <div class="portfolio-interactive-footer" onclick="event.stopPropagation()">
                                <button class="btn-internal-action btn-internal-primary" onclick="openProjectModal(<?= htmlspecialchars(json_encode($proj)) ?>)">
                                    <i class="fa-solid fa-cube"></i>
                                    <span>VIEW DETAILS</span>
                                </button>
                                
                                <?php if (!empty($proj['link'])): ?>
                                    <a href="<?= htmlspecialchars($proj['link']) ?>" target="_blank" class="btn-internal-action btn-internal-outline">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        <span>LAUNCH</span>
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
                    <a href="${proj.link}" target="_blank" class="btn-internal-action btn-internal-primary" style="width: 100%; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fa-solid fa-play"></i>
                        <span>WATCH / VISIT</span>
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
            document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px';
            
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
            document.body.style.overflow = 'auto'; document.body.style.paddingRight = '0px';
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
            document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px'; // Disable scroll under modal
        }

        function hideDocModal() {
            const modal = document.getElementById('documentModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto'; document.body.style.paddingRight = '0px'; // Enable scroll
            
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

        // Mobile Nav Drawer Viewport Interactions
        function toggleMobileNav() {
            const links = document.querySelector('.nav-links');
            const icon = document.querySelector('.mobile-nav-toggle i');
            links.classList.toggle('active');
            icon.className = links.classList.contains('active') ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
        }

        document.querySelectorAll('.nav-links a').forEach(a => {
            a.addEventListener('click', () => {
                const links = document.querySelector('.nav-links');
                if (links.classList.contains('active')) {
                    links.classList.remove('active');
                    document.querySelector('.mobile-nav-toggle i').className = 'fa-solid fa-bars';
                }
            });
        });
    </script>
</body>
</html>
