<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'হাটহাজারী ফুয়েল মনিটর সিস্টেম' }}</title>
        <meta name="description" content="হাটহাজারী উপজেলা, চট্টগ্রামের লাইভ স্টেশন মনিটরিং। অকটেন, পেট্রোল ও ডিজেলের বর্তমান সরবরাহ এক নজরে দেখুন।">
        <meta property="og:type" content="website">
        <meta property="og:title" content="হাটহাজারী ফুয়েল মনিটর সিস্টেম">
        <meta property="og:description" content="হাটহাজারী উপজেলা, চট্টগ্রামের লাইভ স্টেশন মনিটরিং। অকটেন, পেট্রোল ও ডিজেলের বর্তমান সরবরাহ এক নজরে দেখুন।">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('favicon.ico') }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="হাটহাজারী ফুয়েল মনিটর সিস্টেম">
        <meta name="twitter:description" content="হাটহাজারী উপজেলা, চট্টগ্রামের লাইভ স্টেশন মনিটরিং। অকটেন, পেট্রোল ও ডিজেলের বর্তমান সরবরাহ এক নজরে দেখুন।">
        <meta name="twitter:image" content="{{ asset('favicon.ico') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <style>
            :root {
                --page-bg: #f3f6fb;
                --page-bg-deep: #e8eef8;
                --surface: rgba(255, 255, 255, 0.88);
                --surface-strong: #ffffff;
                --surface-soft: rgba(255, 255, 255, 0.68);
                --border: rgba(148, 163, 184, 0.22);
                --text-main: #223047;
                --text-soft: #67768a;
                --text-faint: #94a3b8;
                --primary: #0f766e;
                --primary-deep: #115e59;
                --primary-soft: rgba(15, 118, 110, 0.12);
                --success: #16a34a;
                --success-soft: rgba(22, 163, 74, 0.12);
                --danger: #ef4444;
                --danger-soft: rgba(239, 68, 68, 0.12);
                --warning: #2563eb;
                --warning-soft: rgba(37, 99, 235, 0.14);
                --info: #3b82f6;
                --info-soft: rgba(14, 165, 233, 0.10);
                --shadow-sm: 0 10px 30px rgba(15, 23, 42, 0.05);
                --shadow-md: 0 24px 60px rgba(15, 23, 42, 0.08);
                --shadow-lg: 0 36px 90px rgba(15, 23, 42, 0.12);
                --hero-glow: radial-gradient(circle at top left, rgba(15, 118, 110, 0.16), transparent 28%), radial-gradient(circle at top right, rgba(14, 165, 233, 0.10), transparent 22%), linear-gradient(135deg, rgba(255,255,255,0.96) 0%, rgba(246,248,255,0.92) 55%, rgba(239,245,255,0.94) 100%);
            }
            * { box-sizing: border-box; }
            html { scroll-behavior: smooth; }
            body {
                margin: 0;
                min-height: 100vh;
                font-family: 'Noto Sans Bengali', sans-serif;
                color: var(--text-main);
                background: radial-gradient(circle at top left, rgba(15, 118, 110, 0.08), transparent 24%), radial-gradient(circle at top right, rgba(34, 197, 94, 0.08), transparent 18%), linear-gradient(180deg, #fbfcff 0%, var(--page-bg) 46%, var(--page-bg-deep) 100%);
            }
            body::before {
                content: '';
                position: fixed;
                inset: 0;
                pointer-events: none;
                background-image: linear-gradient(rgba(255,255,255,0.12) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.12) 1px, transparent 1px);
                background-size: 36px 36px;
                mask-image: linear-gradient(180deg, rgba(0,0,0,0.16), rgba(0,0,0,0));
                z-index: -1;
            }
            .container-shell { width: min(1240px, calc(100vw - 32px)); margin: 0 auto; }
            .site-nav {
                position: sticky; top: 0; z-index: 1030; background: rgba(243, 246, 251, 0.76); backdrop-filter: blur(18px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.9); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
            }
            .site-nav-inner { min-height: 82px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
            .brand-wrap, .brand-wrap:hover { display: flex; align-items: center; gap: 14px; text-decoration: none; color: inherit; }
            .brand-mark {
                width: 50px; height: 50px; border-radius: 16px; display: grid; place-items: center; color: #ffffff;
                background: linear-gradient(135deg, #0f766e 0%, #115e59 55%, #0f766e 100%); box-shadow: 0 14px 28px rgba(15, 118, 110, 0.18); font-size: 1.15rem;
            }
            .brand-copy { display: flex; flex-direction: column; gap: 3px; }
            .brand-title { font-size: 1.18rem; font-weight: 800; line-height: 1.1; letter-spacing: -0.03em; color: var(--primary); }
            .brand-subtitle { color: var(--text-soft); font-size: 0.9rem; line-height: 1.3; }
            .nav-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
            .nav-mobile-toggle {
                display: none; align-items: center; justify-content: center; width: 46px; height: 46px; border: 1px solid rgba(148, 163, 184, 0.22);
                border-radius: 14px; background: rgba(255,255,255,0.88); color: var(--primary); box-shadow: var(--shadow-sm);
                transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease, background 0.28s ease;
            }
            .nav-mobile-toggle:hover { transform: translateY(-1px); border-color: rgba(15, 118, 110, 0.18); }
            .mobile-nav-panel {
                display: none; margin-top: 12px; padding: 12px; border: 1px solid rgba(255,255,255,0.84); border-radius: 20px;
                background: rgba(255,255,255,0.9); box-shadow: var(--shadow-sm);
            }
            .mobile-nav-actions { display: grid; gap: 10px; }
            .nav-action {
                display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; min-height: 42px; padding: 0 16px; border-radius: 14px;
                border: 1px solid rgba(148, 163, 184, 0.22); background: rgba(255,255,255,0.82); color: var(--text-main); text-decoration: none;
                font-size: 0.92rem; font-weight: 700; box-shadow: var(--shadow-sm);
                transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease, background 0.28s ease;
            }
            .nav-action:hover { transform: translateY(-2px); border-color: rgba(15, 118, 110, 0.18); color: var(--text-main); box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08); }
            .nav-action-primary, .nav-action-primary:hover, .search-form .btn, .search-form .btn:hover, .feedback-button, .feedback-button:hover {
                border-color: transparent; color: #ffffff; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-deep) 100%); box-shadow: 0 18px 34px rgba(15, 118, 110, 0.18);
            }
            .hero-shell { padding: 32px 0 20px; }
            .hero-panel { position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.8); border-radius: 32px; background: var(--hero-glow); box-shadow: var(--shadow-lg); }
            .hero-panel::before { content: ''; position: absolute; inset: 1px; border-radius: 31px; border: 1px solid rgba(255,255,255,0.7); pointer-events: none; }
            .hero-panel::after { content: ''; position: absolute; inset: auto -80px -120px auto; width: 320px; height: 320px; border-radius: 999px; background: radial-gradient(circle, rgba(15, 118, 110, 0.14) 0%, rgba(79, 70, 229, 0) 72%); pointer-events: none; }
            .hero-grid { position: relative; display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 26px; padding: 34px; }
            .hero-kicker { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; border: 1px solid rgba(15, 118, 110, 0.12); background: rgba(15, 118, 110, 0.08); color: var(--primary); font-size: 0.84rem; font-weight: 700; }
            .hero-title { margin: 18px 0 12px; max-width: none; font-size: clamp(1.32rem, 3vw, 2.4rem); line-height: 1.08; letter-spacing: -0.03em; font-weight: 800; color: var(--primary); }
            .hero-copy { max-width: 700px; color: var(--text-soft); font-size: 1rem; line-height: 1.85; }
            .hero-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-top: 26px; }
            .stat-card { border: 1px solid rgba(255,255,255,0.78); border-radius: 22px; background: rgba(255,255,255,0.74); box-shadow: var(--shadow-sm); backdrop-filter: blur(14px); padding: 18px; }
            .stat-label { margin-bottom: 8px; color: var(--text-soft); font-size: 0.82rem; font-weight: 700; }
            .stat-value { font-size: 1.75rem; line-height: 1; font-weight: 800; color: var(--text-main); }
            .stat-meta { margin-top: 8px; color: var(--text-faint); font-size: 0.8rem; line-height: 1.55; }
            .search-panel { border: 1px solid rgba(255,255,255,0.86); border-radius: 26px; background: rgba(255,255,255,0.72); box-shadow: var(--shadow-md); backdrop-filter: blur(16px); padding: 24px; }
            .panel-label { margin-bottom: 8px; color: var(--primary); font-size: 0.82rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; }
            .panel-title { margin-bottom: 8px; font-size: 1.4rem; line-height: 1.18; font-weight: 800; color: var(--primary); }
            .panel-copy { margin-bottom: 18px; color: var(--text-soft); font-size: 0.93rem; line-height: 1.7; }
            .search-form .input-group { align-items: center; gap: 0; border: 1px solid rgba(148, 163, 184, 0.18); border-radius: 18px; background: rgba(255,255,255,0.86); box-shadow: inset 0 1px 0 rgba(255,255,255,0.9), 0 18px 36px rgba(15, 23, 42, 0.08); overflow: hidden; }
            .search-form .input-group-text, .search-form .form-control { border: 0; background: transparent; box-shadow: none; }
            .search-form .input-group-text { padding-left: 18px; color: var(--primary); }
            .search-form .form-control { min-height: 58px; padding: 0 16px 0 8px; font-size: 0.98rem; color: var(--text-main); }
            .search-form .form-control::placeholder { color: var(--text-faint); }
            .search-form .btn { min-height: 50px; margin: 4px; border-radius: 14px; padding-inline: 22px; font-weight: 800; }
            .refresh-note { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; border: 1px solid rgba(148, 163, 184, 0.18); background: rgba(255,255,255,0.74); color: var(--text-soft); font-size: 0.84rem; font-weight: 700; box-shadow: var(--shadow-sm); }
            .live-dot { width: 8px; height: 8px; border-radius: 999px; background: var(--success); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.34); animation: livePulse 1.8s infinite; }
            @keyframes livePulse { 0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.34); } 70% { box-shadow: 0 0 0 12px rgba(22, 163, 74, 0); } 100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); } }
            .section-block { margin-top: 28px; }
            .section-header { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 18px; }
            .section-title { margin: 0 0 8px; font-size: clamp(1.5rem, 3vw, 2rem); line-height: 1.1; font-weight: 800; letter-spacing: -0.03em; color: var(--primary); }
            .section-copy { margin: 0; color: var(--text-soft); line-height: 1.75; }
            .filter-shell { border: 1px solid rgba(255,255,255,0.84); border-radius: 24px; background: rgba(255,255,255,0.76); box-shadow: var(--shadow-sm); backdrop-filter: blur(16px); padding: 20px; margin-bottom: 22px; }
            .filter-title { margin-bottom: 14px; font-size: 0.95rem; font-weight: 800; color: var(--primary); }
            .filter-bar { display: flex; flex-wrap: nowrap; gap: 8px; overflow-x: auto; overflow-y: hidden; padding-bottom: 4px; scrollbar-width: none; }
            .filter-chip { display: inline-flex; flex: 0 0 auto; align-items: center; gap: 0.45rem; min-height: 42px; padding: 0 15px; border-radius: 999px; border: 1px solid rgba(148, 163, 184, 0.20); background: rgba(255,255,255,0.82); color: var(--text-main); text-decoration: none; white-space: nowrap; font-size: 0.9rem; font-weight: 700; box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04); transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease, background 0.28s ease; }
            .filter-chip:hover, .filter-chip.active { transform: translateY(-2px); background: linear-gradient(135deg, rgba(15, 118, 110, 0.10) 0%, rgba(99, 102, 241, 0.18) 100%); border-color: rgba(15, 118, 110, 0.16); color: var(--primary-deep); box-shadow: 0 14px 28px rgba(15, 118, 110, 0.08); }
            .mobile-filter-panel { display: none; }
            .mobile-filter-grid { display: grid; gap: 12px; }
            .mobile-filter-field label { display: block; margin-bottom: 6px; color: var(--text-soft); font-size: 0.8rem; font-weight: 700; }
            .mobile-filter-field .form-select { min-height: 46px; border-radius: 14px; border-color: rgba(148, 163, 184, 0.20); background: rgba(255,255,255,0.86); color: var(--text-main); box-shadow: none; }
            .mobile-filter-field .form-select:focus { border-color: rgba(15, 118, 110, 0.18); box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.08); }
            .mobile-filter-actions { display: none; }
            .desktop-filter-bar { display: flex; }
            .filter-bar::-webkit-scrollbar { display: none; }
            .station-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
            .station-card { border: 1px solid rgba(255,255,255,0.84); border-radius: 28px; background: linear-gradient(180deg, rgba(255,255,255,0.9) 0%, rgba(248,250,255,0.94) 100%); box-shadow: var(--shadow-md); overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease; }
            .station-card:hover { transform: translateY(-6px); border-color: rgba(15, 118, 110, 0.14); box-shadow: 0 28px 60px rgba(15, 23, 42, 0.12); }
            .station-head { padding: 24px 24px 18px; border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
            .station-body { padding: 20px 24px; }
            .station-foot { padding: 16px 24px 20px; border-top: 1px solid rgba(226, 232, 240, 0.72); background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, rgba(247,250,255,0.92) 100%); }
            .station-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
            .station-identity { display: flex; gap: 14px; min-width: 0; }
            .station-icon { width: 54px; height: 54px; border-radius: 18px; display: grid; place-items: center; flex-shrink: 0; background: linear-gradient(135deg, rgba(15, 118, 110, 0.10) 0%, rgba(37, 99, 235, 0.16) 100%); color: var(--primary); box-shadow: 0 12px 24px rgba(15, 118, 110, 0.08); font-size: 1.15rem; }
            .station-name { margin: 0 0 6px; font-size: 1.3rem; font-weight: 800; line-height: 1.25; color: var(--primary); }
            .station-location { display: flex; align-items: flex-start; gap: 8px; color: var(--text-soft); font-size: 0.94rem; line-height: 1.6; }
            .status-badge { display: inline-flex; align-items: center; gap: 8px; min-height: 36px; padding: 0 12px; border-radius: 999px; font-size: 0.84rem; font-weight: 800; white-space: nowrap; }
            .status-available { background: #eafaf0; color: var(--success); border: 1px solid rgba(22, 163, 74, 0.12); }
            .status-unavailable { background: #fff1f2; color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.12); }
            .status-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
            .status-box { border: 1px solid rgba(148, 163, 184, 0.16); border-radius: 18px; background: rgba(255,255,255,0.82); padding: 14px 16px; min-width: 0; }
            .status-box:nth-child(1) { background: linear-gradient(180deg, rgba(255, 242, 244, 0.96) 0%, rgba(255,255,255,0.98) 100%); border-color: rgba(239, 68, 68, 0.18); }
            .status-box:nth-child(2) { background: linear-gradient(180deg, rgba(255, 249, 237, 0.96) 0%, rgba(255,255,255,0.98) 100%); border-color: rgba(37, 99, 235, 0.20); }
            .status-box:nth-child(3) { background: linear-gradient(180deg, rgba(239, 246, 255, 0.96) 0%, rgba(255,255,255,0.98) 100%); border-color: rgba(59, 130, 246, 0.20); }
            .status-label { color: var(--text-soft); font-size: 0.82rem; font-weight: 800; margin-bottom: 8px; }
            .status-value { font-size: 1.06rem; font-weight: 800; }
            .status-value.available { color: var(--success); }
            .status-value.unavailable { color: var(--danger); }
            .detail-list { display: grid; gap: 10px; }
            .detail-row { display: grid; grid-template-columns: 40px minmax(0, 1fr); gap: 12px; align-items: flex-start; border: 1px solid rgba(226, 232, 240, 0.92); border-radius: 18px; background: rgba(255,255,255,0.84); padding: 14px 15px; }
            .detail-card-supply { background: linear-gradient(135deg, rgba(240, 253, 250, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%); border-color: rgba(20, 184, 166, 0.18); }
            .detail-card-dealer { background: linear-gradient(135deg, rgba(239, 246, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%); border-color: rgba(59, 130, 246, 0.18); }
            .detail-card-crowd { background: linear-gradient(135deg, rgba(240, 253, 244, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%); border-color: rgba(34, 197, 94, 0.18); }
            .detail-icon { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; background: rgba(15, 118, 110, 0.10); color: var(--primary); }
            .detail-icon.warning { background: rgba(37, 99, 235, 0.12); color: var(--warning); }
            .detail-icon.success { background: rgba(22, 163, 74, 0.12); color: var(--success); }
            .detail-icon-supply { background: rgba(20, 184, 166, 0.14); color: #0f766e; }
            .detail-icon-dealer { background: rgba(59, 130, 246, 0.14); color: #2563eb; }
            .detail-icon-crowd { background: rgba(34, 197, 94, 0.14); color: #16a34a; }
            .detail-title { color: var(--text-soft); font-size: 0.8rem; font-weight: 700; margin-bottom: 5px; }
            .detail-value { color: var(--text-main); font-size: 0.98rem; font-weight: 700; line-height: 1.55; }
            .detail-meta { margin-top: 6px; color: var(--text-faint); font-size: 0.83rem; }
            .station-meta { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; color: var(--text-soft); font-size: 0.88rem; }
            .meta-list { display: flex; flex-wrap: wrap; gap: 12px; }
            .meta-chip { display: inline-flex; align-items: center; gap: 6px; padding: 8px 10px; border-radius: 999px; background: rgba(255,255,255,0.84); border: 1px solid rgba(226, 232, 240, 0.92); color: var(--text-soft); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04); }
            .feedback-button { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 42px; padding: 0 18px; border-radius: 14px; border: 0; font-size: 0.9rem; font-weight: 800; transition: transform 0.28s ease, box-shadow 0.28s ease; }
            .feedback-button:hover { transform: translateY(-2px); }
            .footer-panel { border: 1px solid rgba(255,255,255,0.84); border-radius: 28px; background: linear-gradient(180deg, rgba(255,255,255,0.84) 0%, rgba(248,250,255,0.92) 100%); box-shadow: var(--shadow-md); padding: 28px; text-align: center; }
            .info-strip { display: flex; justify-content: center; flex-wrap: wrap; gap: 12px; margin: 22px 0; }
            .info-chip { display: inline-flex; align-items: center; gap: 8px; min-height: 40px; padding: 0 14px; border-radius: 999px; border: 1px solid rgba(226, 232, 240, 0.9); background: rgba(255,255,255,0.86); color: var(--text-main); font-size: 0.9rem; font-weight: 700; }
            .notice-box { max-width: 780px; margin: 0 auto; border: 1px solid rgba(226, 232, 240, 0.92); border-radius: 18px; background: rgba(255,255,255,0.86); padding: 20px; color: var(--text-soft); line-height: 1.8; }
            .empty-state { grid-column: 1 / -1; border: 1px dashed rgba(148, 163, 184, 0.32); border-radius: 24px; background: rgba(255,255,255,0.84); padding: 48px 24px; text-align: center; color: var(--text-soft); box-shadow: var(--shadow-sm); }
            .crowd-modal-dialog { max-width: 560px; }
            .crowd-modal-option { transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease; }
            .crowd-modal-option:hover { transform: translateY(-2px); box-shadow: var(--shadow-sm); }
            @media (max-width: 991.98px) { .hero-grid, .station-grid { grid-template-columns: 1fr; } }
            @media (max-width: 767.98px) {
                .site-nav {
                    top: 0;
                    z-index: 1100;
                    background: rgba(243, 246, 251, 0.96);
                    backdrop-filter: blur(20px);
                    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
                }
                .container-shell { width: min(100vw - 16px, 1240px); }
                .site-nav-inner { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: start; gap: 12px; min-height: auto; padding: 10px 0; }
                .brand-wrap { width: 100%; min-width: 0; gap: 12px; }
                .brand-mark { width: 44px; height: 44px; border-radius: 14px; font-size: 1rem; }
                .brand-title { font-size: 1.05rem; }
                .brand-subtitle { font-size: 0.83rem; line-height: 1.45; }
                .nav-mobile-toggle { display: inline-flex; }
                .nav-actions { display: none; }
                .mobile-nav-panel { display: none; grid-column: 1 / -1; width: 100%; }
                .mobile-nav-panel.is-open { display: block; }
                .mobile-nav-actions .nav-action { width: 100%; justify-content: flex-start; min-height: 44px; padding: 0 14px; font-size: 0.9rem; }
                .nav-action { min-height: 40px; padding: 0 14px; font-size: 0.88rem; }
                .hero-shell { padding: 18px 0 18px; }
                .hero-panel, .filter-shell, .station-card, .footer-panel, .search-panel, .empty-state { border-radius: 22px; }
                .hero-grid, .search-panel, .footer-panel { padding: 16px; }
                .hero-grid { gap: 16px; }
                .hero-kicker { justify-content: center; margin-inline: auto; }
                .hero-title { margin: 14px 0 10px; max-width: none; font-size: 1.17rem; line-height: 1.12; text-align: center; }
                .hero-copy { font-size: 0.94rem; line-height: 1.75; text-align: center; }
                .hero-stats { grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px; }
                .stat-card { border-radius: 16px; padding: 14px; }
                .stat-card:last-child { grid-column: 1 / -1; }
                .search-panel { border-radius: 20px; text-align: center; }
                .panel-label { text-align: center; }
                .panel-title { font-size: 1.2rem; text-align: center; }
                .panel-copy { font-size: 0.88rem; line-height: 1.65; margin-bottom: 16px; text-align: center; }
                .section-header, .station-top { flex-direction: column; align-items: stretch; }
                .section-header { text-align: center; }
                .section-block { margin-top: 22px; }
                .section-title { font-size: 1.35rem; margin-bottom: 6px; text-align: center; }
                .section-copy { font-size: 0.9rem; line-height: 1.65; text-align: center; }
                .filter-shell { padding: 14px; margin-bottom: 16px; }
                .filter-title { display: block; font-size: 0.9rem; margin-bottom: 12px; }
                .filter-title::after { content: '\09AE\09CB\09AC\09BE\0987\09B2\09C7 \09A1\09CD\09B0\09AA\09A1\09BE\0989\09A8 \09AB\09BF\09B2\09CD\099F\09BE\09B0'; display: block; margin-top: 4px; color: var(--text-faint); font-size: 0.72rem; font-weight: 600; }
                .mobile-filter-panel { display: block; }
                .mobile-filter-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 12px; }
                .mobile-filter-actions .btn { min-height: 44px; border-radius: 12px; font-size: 0.88rem; font-weight: 700; }
                .desktop-filter-bar { display: none; }
                .station-grid { gap: 14px; }
                .status-badge { align-self: flex-start; min-height: 32px; font-size: 0.8rem; }
                .station-head, .station-body, .station-foot { padding-left: 16px; padding-right: 16px; }
                .station-head { padding-top: 18px; padding-bottom: 14px; }
                .station-body { padding-top: 16px; padding-bottom: 16px; }
                .station-foot { padding-top: 14px; padding-bottom: 16px; }
                .station-icon { width: 44px; height: 44px; border-radius: 14px; font-size: 1rem; }
                .station-name { font-size: 1.1rem; line-height: 1.35; }
                .station-location { font-size: 0.88rem; line-height: 1.55; }
                .status-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; margin-bottom: 12px; }
                .status-box { border-radius: 14px; padding: 10px; }
                .status-label { font-size: 0.74rem; margin-bottom: 5px; }
                .status-value { font-size: 0.95rem; }
                .detail-list { gap: 8px; }
                .detail-row { grid-template-columns: 34px minmax(0, 1fr); gap: 10px; padding: 12px; border-radius: 14px; }
                .detail-icon { width: 34px; height: 34px; border-radius: 10px; font-size: 0.92rem; }
                .detail-title { font-size: 0.74rem; margin-bottom: 4px; }
                .detail-value { font-size: 0.9rem; line-height: 1.5; }
                .detail-meta { font-size: 0.76rem; }
                .search-form .input-group { display: flex; flex-direction: column; gap: 10px; border: 0; background: transparent; box-shadow: none; overflow: visible; }
                .search-form .input-group-text { display: none; }
                .search-form .form-control { width: 100%; min-height: 48px; border: 1px solid rgba(148, 163, 184, 0.18) !important; border-radius: 14px !important; background: rgba(255,255,255,0.88) !important; padding: 0 14px; font-size: 0.95rem; }
                .search-form .btn { width: 100%; margin: 0; border-radius: 14px !important; min-height: 48px; }
                .station-meta { align-items: stretch; gap: 10px; }
                .meta-list { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; width: 100%; }
                .meta-chip { justify-content: center; font-size: 0.8rem; padding: 8px; }
                .feedback-button { width: 100%; min-height: 42px; font-size: 0.88rem; }
                .footer-panel { text-align: left; }
                .info-strip { justify-content: flex-start; gap: 8px; }
                .info-chip { width: 100%; justify-content: flex-start; font-size: 0.86rem; }
                .notice-box { border-radius: 16px; padding: 16px; font-size: 0.88rem; line-height: 1.7; }
                .empty-state { padding: 36px 18px; border-radius: 18px; }
                .crowd-modal-dialog { max-width: calc(100vw - 12px); margin: 6px auto; }
            }
        </style>
    </head>
    <body>
        <nav class="site-nav">
            <div class="container-shell">
                <div class="site-nav-inner">
                    <a href="{{ route('home') }}" class="brand-wrap">
                        <div class="brand-mark">
                            <i class="bi bi-fuel-pump-fill"></i>
                        </div>
                        <div class="brand-copy">
                            <div class="brand-title">&#x09B9;&#x09BE;&#x099F;&#x09B9;&#x09BE;&#x099C;&#x09BE;&#x09B0;&#x09C0; &#x09AB;&#x09C1;&#x09DF;&#x09C7;&#x09B2; &#x09AE;&#x09A8;&#x09BF;&#x099F;&#x09B0;</div>
                            <div class="brand-subtitle">&#x09B9;&#x09BE;&#x099F;&#x09B9;&#x09BE;&#x099C;&#x09BE;&#x09B0;&#x09C0; &#x0989;&#x09AA;&#x099C;&#x09C7;&#x09B2;&#x09BE;, &#x099A;&#x099F;&#x09CD;&#x099F;&#x0997;&#x09CD;&#x09B0;&#x09BE;&#x09AE;</div>
                        </div>
                    </a>
                    <button type="button" class="nav-mobile-toggle" id="mobileNavToggle" aria-controls="mobileNavPanel" aria-expanded="false" aria-label="Open menu">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <div class="nav-actions">
                        <a href="{{ route('home') }}" class="nav-action nav-action-primary"><i class="bi bi-broadcast-pin"></i> &#x09B2;&#x09BE;&#x0987;&#x09AD; &#x09AA;&#x09C7;&#x099C;</a>
                        <button type="button" class="nav-action" data-refresh-button><i class="bi bi-arrow-repeat"></i> &#x09B0;&#x09BF;&#x09AB;&#x09CD;&#x09B0;&#x09C7;&#x09B6;</button>
                        @auth
                            <a href="{{ route('dashboard') }}" class="nav-action"><i class="bi bi-speedometer2"></i> &#x09A1;&#x09CD;&#x09AF;&#x09BE;&#x09B6;&#x09AC;&#x09CB;&#x09B0;&#x09CD;&#x09A1;</a>
                        @else
                            <a href="{{ route('login') }}" class="nav-action"><i class="bi bi-box-arrow-in-right"></i> &#x09B2;&#x0997;&#x0987;&#x09A8;</a>
                        @endauth
                    </div>
                    <div class="mobile-nav-panel" id="mobileNavPanel">
                        <div class="mobile-nav-actions">
                            <a href="{{ route('home') }}" class="nav-action nav-action-primary"><i class="bi bi-broadcast-pin"></i> &#x09B2;&#x09BE;&#x0987;&#x09AD; &#x09AA;&#x09C7;&#x099C;</a>
                            <button type="button" class="nav-action" data-refresh-button><i class="bi bi-arrow-repeat"></i> &#x09B0;&#x09BF;&#x09AB;&#x09CD;&#x09B0;&#x09C7;&#x09B6;</button>
                            @auth
                                <a href="{{ route('dashboard') }}" class="nav-action"><i class="bi bi-speedometer2"></i> &#x09A1;&#x09CD;&#x09AF;&#x09BE;&#x09B6;&#x09AC;&#x09CB;&#x09B0;&#x09CD;&#x09A1;</a>
                            @else
                                <a href="{{ route('login') }}" class="nav-action"><i class="bi bi-box-arrow-in-right"></i> &#x09B2;&#x0997;&#x0987;&#x09A8;</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <main class="pb-5">
            @yield('content')
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            (() => {
                document.querySelectorAll('[data-refresh-button]').forEach((button) => {
                    button.addEventListener('click', () => window.location.reload());
                });

                const mobileNavToggle = document.getElementById('mobileNavToggle');
                const mobileNavPanel = document.getElementById('mobileNavPanel');
                if (!mobileNavToggle || !mobileNavPanel) return;

                mobileNavToggle.addEventListener('click', () => {
                    const isOpen = mobileNavPanel.classList.toggle('is-open');
                    mobileNavToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    mobileNavToggle.innerHTML = isOpen ? '<i class="bi bi-x-lg fs-5"></i>' : '<i class="bi bi-list fs-4"></i>';
                });
            })();
        </script>
        @stack('scripts')
    </body>
</html>




