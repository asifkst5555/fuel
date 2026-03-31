<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'হাটহাজারী ফুয়েল মনিটরিং সিস্টেম' }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <style>
            :root {
                --page-bg: #f9fafb;
                --surface: #ffffff;
                --surface-muted: #f8fafc;
                --border: #e5e7eb;
                --text-main: #111827;
                --text-soft: #6b7280;
                --text-faint: #94a3b8;
                --primary: #2563eb;
                --primary-deep: #1d4ed8;
                --success: #16a34a;
                --success-soft: #f0fdf4;
                --danger: #dc2626;
                --danger-soft: #fef2f2;
                --warning: #d97706;
                --warning-soft: #fffbeb;
                --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.06), 0 1px 1px rgba(15, 23, 42, 0.04);
                --shadow-md: 0 18px 40px rgba(15, 23, 42, 0.06);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: 'Noto Sans Bengali', sans-serif;
                color: var(--text-main);
                background:
                    radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 28%),
                    linear-gradient(180deg, #ffffff 0%, var(--page-bg) 100%);
            }

            .container-shell {
                width: min(1200px, calc(100vw - 32px));
                margin: 0 auto;
            }

            .site-nav {
                position: sticky;
                top: 0;
                z-index: 1030;
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(16px);
                border-bottom: 1px solid rgba(229, 231, 235, 0.9);
            }

            .site-nav-inner {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                min-height: 76px;
            }

            .brand-wrap {
                display: flex;
                align-items: center;
                gap: 14px;
                text-decoration: none;
            }

            .brand-mark {
                width: 48px;
                height: 48px;
                border-radius: 14px;
                display: grid;
                place-items: center;
                color: #fff;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-deep) 100%);
                box-shadow: var(--shadow-sm);
                font-size: 1.2rem;
            }

            .brand-title {
                font-size: 1.2rem;
                font-weight: 700;
                color: var(--text-main);
                line-height: 1.1;
            }

            .brand-subtitle {
                margin-top: 2px;
                color: var(--text-soft);
                font-size: 0.92rem;
            }

            .nav-actions {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .nav-action {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                min-height: 42px;
                padding: 0 16px;
                border-radius: 12px;
                border: 1px solid var(--border);
                background: #fff;
                color: var(--text-main);
                text-decoration: none;
                font-size: 0.95rem;
                font-weight: 600;
                box-shadow: var(--shadow-sm);
                transition: 0.18s ease;
            }

            .nav-action:hover {
                color: var(--text-main);
                border-color: #cbd5e1;
                transform: translateY(-1px);
            }

            .nav-action-primary {
                background: var(--primary);
                border-color: var(--primary);
                color: #fff;
            }

            .nav-action-primary:hover {
                color: #fff;
                background: var(--primary-deep);
                border-color: var(--primary-deep);
            }

            .hero-shell {
                padding: 36px 0 28px;
            }

            .hero-panel {
                position: relative;
                overflow: hidden;
                border: 1px solid var(--border);
                border-radius: 24px;
                background:
                    linear-gradient(135deg, rgba(37, 99, 235, 0.06) 0%, rgba(255,255,255,0.92) 42%),
                    var(--surface);
                box-shadow: var(--shadow-md);
            }

            .hero-panel::after {
                content: '';
                position: absolute;
                inset: auto -40px -60px auto;
                width: 220px;
                height: 220px;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(37, 99, 235, 0.14) 0%, rgba(37, 99, 235, 0) 72%);
                pointer-events: none;
            }

            .hero-grid {
                position: relative;
                display: grid;
                grid-template-columns: minmax(0, 1.3fr) minmax(320px, 0.9fr);
                gap: 24px;
                padding: 32px;
            }

            .hero-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                border: 1px solid #dbeafe;
                border-radius: 999px;
                background: #eff6ff;
                color: var(--primary);
                font-size: 0.88rem;
                font-weight: 700;
            }

            .hero-title {
                margin: 18px 0 14px;
                font-size: clamp(2rem, 4vw, 3.25rem);
                line-height: 1.08;
                letter-spacing: -0.03em;
                font-weight: 700;
            }

            .hero-copy {
                max-width: 700px;
                color: var(--text-soft);
                font-size: 1.04rem;
                line-height: 1.85;
            }

            .hero-stats {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 14px;
                margin-top: 28px;
            }

            .stat-card {
                border: 1px solid var(--border);
                border-radius: 18px;
                background: rgba(255,255,255,0.82);
                padding: 18px;
                box-shadow: var(--shadow-sm);
            }

            .stat-label {
                color: var(--text-soft);
                font-size: 0.88rem;
                font-weight: 600;
                margin-bottom: 10px;
            }

            .stat-value {
                font-size: 1.8rem;
                font-weight: 700;
                line-height: 1;
            }

            .stat-meta {
                margin-top: 8px;
                color: var(--text-faint);
                font-size: 0.82rem;
            }

            .search-panel {
                border: 1px solid var(--border);
                border-radius: 20px;
                background: rgba(255,255,255,0.94);
                box-shadow: var(--shadow-sm);
                padding: 24px;
            }

            .panel-label {
                color: var(--text-soft);
                font-size: 0.84rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                margin-bottom: 8px;
            }

            .panel-title {
                font-size: 1.45rem;
                font-weight: 700;
                line-height: 1.2;
                margin-bottom: 8px;
            }

            .panel-copy {
                color: var(--text-soft);
                font-size: 0.94rem;
                line-height: 1.7;
                margin-bottom: 20px;
            }

            .search-form .input-group-text,
            .search-form .form-control,
            .search-form .btn {
                min-height: 54px;
                border-radius: 12px;
            }

            .search-form .input-group-text {
                border-right: 0;
                background: #fff;
            }

            .search-form .form-control {
                border-left: 0;
                font-size: 1rem;
            }

            .search-form .btn {
                font-weight: 700;
                padding-inline: 22px;
            }

            .refresh-note {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                border-radius: 999px;
                background: var(--surface-muted);
                color: var(--text-soft);
                font-size: 0.85rem;
                font-weight: 600;
            }

            .live-dot {
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: var(--success);
                box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.36);
                animation: livePulse 1.8s infinite;
            }

            @keyframes livePulse {
                0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.36); }
                70% { box-shadow: 0 0 0 10px rgba(22, 163, 74, 0); }
                100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
            }

            .section-block {
                margin-top: 28px;
            }

            .section-header {
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 20px;
                margin-bottom: 18px;
            }

            .section-title {
                font-size: clamp(1.5rem, 3vw, 2.1rem);
                font-weight: 700;
                line-height: 1.15;
                margin: 0 0 8px;
            }

            .section-copy {
                color: var(--text-soft);
                margin: 0;
                line-height: 1.7;
            }

            .filter-shell {
                border: 1px solid var(--border);
                border-radius: 18px;
                background: var(--surface);
                box-shadow: var(--shadow-sm);
                padding: 18px;
                margin-bottom: 22px;
            }

            .filter-title {
                font-size: 0.95rem;
                font-weight: 700;
                margin-bottom: 12px;
            }

            .filter-bar {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .filter-chip {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                min-height: 40px;
                padding: 0 14px;
                border-radius: 999px;
                border: 1px solid var(--border);
                background: var(--surface-muted);
                color: var(--text-main);
                text-decoration: none;
                font-size: 0.9rem;
                font-weight: 600;
                transition: 0.18s ease;
            }

            .filter-chip:hover,
            .filter-chip.active {
                background: var(--primary);
                border-color: var(--primary);
                color: #fff;
            }


            .mobile-filter-panel {
                display: none;
            }

            .mobile-filter-grid {
                display: grid;
                gap: 12px;
            }

            .mobile-filter-field label {
                display: block;
                margin-bottom: 6px;
                color: var(--text-soft);
                font-size: 0.8rem;
                font-weight: 700;
            }

            .mobile-filter-field .form-select {
                min-height: 46px;
                border-radius: 12px;
                border-color: var(--border);
                font-size: 0.92rem;
                box-shadow: none;
            }

            .mobile-filter-field .form-select:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
            }

            .mobile-filter-actions {
                display: none;
            }

            .desktop-filter-bar {
                display: flex;
            }

            .station-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px;
            }

            .station-card {
                border: 1px solid var(--border);
                border-radius: 22px;
                background: var(--surface);
                box-shadow: var(--shadow-sm);
                overflow: hidden;
                transition: 0.18s ease;
            }

            .station-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .station-head {
                padding: 24px 24px 18px;
                border-bottom: 1px solid var(--border);
            }

            .station-body {
                padding: 20px 24px;
            }

            .station-foot {
                padding: 16px 24px 20px;
                border-top: 1px solid var(--border);
                background: #fcfcfd;
            }

            .station-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
            }

            .station-identity {
                display: flex;
                gap: 14px;
                min-width: 0;
            }

            .station-icon {
                width: 52px;
                height: 52px;
                border-radius: 16px;
                display: grid;
                place-items: center;
                flex-shrink: 0;
                background: #eff6ff;
                color: var(--primary);
                font-size: 1.15rem;
            }

            .station-name {
                margin: 0 0 6px;
                font-size: 1.35rem;
                font-weight: 700;
                line-height: 1.25;
            }

            .station-location {
                display: inline-flex;
                align-items: flex-start;
                gap: 8px;
                color: var(--text-soft);
                font-size: 0.95rem;
                line-height: 1.65;
            }

            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                min-height: 36px;
                padding: 0 12px;
                border-radius: 999px;
                font-size: 0.86rem;
                font-weight: 700;
                white-space: nowrap;
            }

            .status-available {
                background: var(--success-soft);
                color: var(--success);
            }

            .status-unavailable {
                background: var(--danger-soft);
                color: var(--danger);
            }

            .status-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
                margin-bottom: 16px;
            }

            .status-box {
                border: 1px solid var(--border);
                border-radius: 16px;
                background: var(--surface-muted);
                padding: 14px 16px;
            }

            .status-label {
                color: var(--text-soft);
                font-size: 0.84rem;
                font-weight: 600;
                margin-bottom: 8px;
            }

            .status-value {
                font-size: 1.15rem;
                font-weight: 700;
            }

            .status-value.available {
                color: var(--success);
            }

            .status-value.unavailable {
                color: var(--danger);
            }

            .detail-list {
                display: grid;
                gap: 10px;
            }

            .detail-row {
                display: grid;
                grid-template-columns: 40px minmax(0, 1fr);
                gap: 12px;
                align-items: flex-start;
                border: 1px solid var(--border);
                border-radius: 16px;
                background: #fff;
                padding: 14px 15px;
            }

            .detail-icon {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                display: grid;
                place-items: center;
                background: #eff6ff;
                color: var(--primary);
            }

            .detail-icon.warning {
                background: var(--warning-soft);
                color: var(--warning);
            }

            .detail-icon.success {
                background: var(--success-soft);
                color: var(--success);
            }

            .detail-title {
                color: var(--text-soft);
                font-size: 0.8rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                margin-bottom: 5px;
            }

            .detail-value {
                color: var(--text-main);
                font-size: 0.98rem;
                font-weight: 600;
                line-height: 1.6;
            }

            .detail-meta {
                margin-top: 6px;
                color: var(--text-faint);
                font-size: 0.83rem;
            }

            .station-meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
                color: var(--text-soft);
                font-size: 0.88rem;
            }

            .meta-list {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .meta-chip {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 10px;
                border-radius: 999px;
                background: var(--surface-muted);
                border: 1px solid var(--border);
            }

            .feedback-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-height: 40px;
                padding: 0 16px;
                border-radius: 12px;
                border: 1px solid var(--primary);
                background: var(--primary);
                color: #fff;
                font-size: 0.9rem;
                font-weight: 700;
                box-shadow: var(--shadow-sm);
            }

            .feedback-button:hover {
                background: var(--primary-deep);
                border-color: var(--primary-deep);
                color: #fff;
            }

            .footer-panel {
                border: 1px solid var(--border);
                border-radius: 22px;
                background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
                box-shadow: var(--shadow-sm);
                padding: 28px;
                text-align: center;
            }

            .info-strip {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 12px;
                margin: 22px 0;
            }

            .info-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                min-height: 40px;
                padding: 0 14px;
                border-radius: 999px;
                border: 1px solid var(--border);
                background: #fff;
                color: var(--text-main);
                font-size: 0.9rem;
                font-weight: 600;
            }

            .notice-box {
                max-width: 780px;
                margin: 0 auto;
                border: 1px solid var(--border);
                border-radius: 18px;
                background: #fff;
                padding: 20px;
                color: var(--text-soft);
                line-height: 1.8;
            }

            .empty-state {
                grid-column: 1 / -1;
                border: 1px dashed #cbd5e1;
                border-radius: 22px;
                background: #fff;
                padding: 48px 24px;
                text-align: center;
                color: var(--text-soft);
            }

            .crowd-modal-dialog {
                max-width: 560px;
            }

            .crowd-modal-option {
                transition: 0.18s ease;
            }

            .crowd-modal-option:hover {
                transform: translateY(-1px);
                box-shadow: var(--shadow-sm);
            }

            @media (max-width: 991.98px) {
                .hero-grid,
                .station-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 767.98px) {
                .container-shell {
                    width: min(100vw - 16px, 1200px);
                }

                .site-nav-inner {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 12px;
                    min-height: auto;
                    padding: 12px 0;
                }

                .brand-wrap {
                    width: 100%;
                    gap: 12px;
                }

                .brand-mark {
                    width: 42px;
                    height: 42px;
                    border-radius: 12px;
                    font-size: 1rem;
                }

                .brand-title {
                    font-size: 1.05rem;
                }

                .brand-subtitle {
                    font-size: 0.83rem;
                    line-height: 1.45;
                }

                .nav-actions {
                    width: 100%;
                    flex-wrap: nowrap;
                    gap: 8px;
                    overflow-x: auto;
                    padding-bottom: 2px;
                    scrollbar-width: none;
                }

                .nav-actions::-webkit-scrollbar {
                    display: none;
                }

                .nav-actions > * {
                    flex: 0 0 auto;
                    min-width: max-content;
                }

                .nav-action {
                    min-height: 40px;
                    padding: 0 14px;
                    font-size: 0.88rem;
                }

                .hero-shell {
                    padding: 16px 0 18px;
                }

                .hero-panel {
                    border-radius: 20px;
                }

                .hero-grid,
                .search-panel,
                .footer-panel {
                    padding: 16px;
                }

                .hero-grid {
                    gap: 16px;
                }

                .hero-kicker {
                    font-size: 0.78rem;
                    padding: 7px 10px;
                }

                .hero-title {
                    margin: 14px 0 10px;
                    font-size: 1.7rem;
                    line-height: 1.15;
                }

                .hero-copy {
                    font-size: 0.94rem;
                    line-height: 1.75;
                }

                .hero-stats {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 10px;
                    margin-top: 20px;
                }

                .stat-card {
                    border-radius: 16px;
                    padding: 14px;
                }

                .stat-card:last-child {
                    grid-column: 1 / -1;
                }

                .stat-label {
                    font-size: 0.8rem;
                    margin-bottom: 8px;
                }

                .stat-value {
                    font-size: 1.45rem;
                }

                .stat-meta {
                    font-size: 0.76rem;
                }

                .search-panel {
                    border-radius: 18px;
                }

                .panel-title {
                    font-size: 1.2rem;
                }

                .panel-copy {
                    font-size: 0.88rem;
                    line-height: 1.65;
                    margin-bottom: 16px;
                }

                .section-header,
                .station-top {
                    flex-direction: column;
                    align-items: stretch;
                }

                .section-block {
                    margin-top: 20px;
                }

                .section-title {
                    font-size: 1.35rem;
                    margin-bottom: 6px;
                }

                .section-copy {
                    font-size: 0.9rem;
                    line-height: 1.65;
                }

                .filter-shell {
                    border-radius: 18px;
                    padding: 14px;
                    margin-bottom: 16px;
                    background:
                        linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
                }

                .filter-title {
                    display: block;
                    font-size: 0.9rem;
                    margin-bottom: 12px;
                }

                .filter-title::after {
                    content: 'মোবাইলে ড্রপডাউন ফিল্টার';
                    display: block;
                    margin-top: 4px;
                    color: var(--text-faint);
                    font-size: 0.72rem;
                    font-weight: 600;
                }

                .mobile-filter-panel {
                    display: block;
                }

                .mobile-filter-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 10px;
                    margin-top: 12px;
                }

                .mobile-filter-actions .btn {
                    min-height: 44px;
                    border-radius: 12px;
                    font-size: 0.88rem;
                    font-weight: 700;
                }

                .desktop-filter-bar {
                    display: none;
                }

                .station-grid {
                    gap: 14px;
                }

                .station-card {
                    border-radius: 18px;
                }

                .status-badge {
                    align-self: flex-start;
                    min-height: 32px;
                    font-size: 0.8rem;
                }

                .station-head,
                .station-body,
                .station-foot {
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .station-head {
                    padding-top: 18px;
                    padding-bottom: 14px;
                }

                .station-body {
                    padding-top: 16px;
                    padding-bottom: 16px;
                }

                .station-foot {
                    padding-top: 14px;
                    padding-bottom: 16px;
                }

                .station-identity {
                    gap: 12px;
                }

                .station-icon {
                    width: 44px;
                    height: 44px;
                    border-radius: 14px;
                    font-size: 1rem;
                }

                .station-name {
                    font-size: 1.12rem;
                    line-height: 1.35;
                }

                .station-location {
                    font-size: 0.88rem;
                    line-height: 1.55;
                }

                .status-grid {
                    grid-template-columns: 1fr 1fr;
                    gap: 10px;
                    margin-bottom: 12px;
                }

                .status-box {
                    border-radius: 14px;
                    padding: 12px;
                }

                .status-label {
                    font-size: 0.78rem;
                    margin-bottom: 6px;
                }

                .status-value {
                    font-size: 1rem;
                }

                .detail-list {
                    gap: 8px;
                }

                .detail-row {
                    grid-template-columns: 34px minmax(0, 1fr);
                    gap: 10px;
                    padding: 12px;
                    border-radius: 14px;
                }

                .detail-icon {
                    width: 34px;
                    height: 34px;
                    border-radius: 10px;
                    font-size: 0.92rem;
                }

                .detail-title {
                    font-size: 0.74rem;
                    margin-bottom: 4px;
                }

                .detail-value {
                    font-size: 0.9rem;
                    line-height: 1.5;
                }

                .detail-meta {
                    font-size: 0.76rem;
                }

                .search-form .input-group {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }

                .search-form .input-group-text {
                    display: none;
                }

                .search-form .form-control {
                    width: 100%;
                    min-height: 48px;
                    border: 1px solid var(--border) !important;
                    border-radius: 12px !important;
                    padding: 0 14px;
                    font-size: 0.95rem;
                }

                .search-form .btn {
                    width: 100%;
                    border-radius: 12px !important;
                    min-height: 48px;
                }

                .station-meta {
                    align-items: stretch;
                    gap: 10px;
                }

                .meta-list {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 8px;
                    width: 100%;
                }

                .meta-chip {
                    justify-content: center;
                    font-size: 0.8rem;
                    padding: 8px;
                }

                .feedback-button {
                    width: 100%;
                    min-height: 42px;
                    font-size: 0.88rem;
                }

                .footer-panel {
                    border-radius: 18px;
                    text-align: left;
                }

                .info-strip {
                    justify-content: flex-start;
                    gap: 8px;
                }

                .info-chip {
                    width: 100%;
                    justify-content: flex-start;
                    font-size: 0.86rem;
                }

                .notice-box {
                    border-radius: 16px;
                    padding: 16px;
                    font-size: 0.88rem;
                    line-height: 1.7;
                }

                .empty-state {
                    padding: 36px 18px;
                    border-radius: 18px;
                }

                .crowd-modal-dialog {
                    max-width: calc(100vw - 12px);
                    margin: 6px auto;
                }
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
                        <div>
                            <div class="brand-title">হাটহাজারী ফুয়েল মনিটর</div>
                            <div class="brand-subtitle">হাটহাজারী উপজেলা, চট্টগ্রামের লাইভ স্টেশন মনিটরিং</div>
                        </div>
                    </a>

                    <div class="nav-actions">
                        <a href="{{ route('home') }}" class="nav-action nav-action-primary">
                            <i class="bi bi-broadcast-pin"></i>
                            লাইভ পেজ
                        </a>
                        <button type="button" class="nav-action" id="refreshDataButton">
                            <i class="bi bi-arrow-repeat"></i>
                            রিফ্রেশ
                        </button>
                        @auth
                            <a href="{{ route('dashboard') }}" class="nav-action">
                                <i class="bi bi-speedometer2"></i>
                                অ্যাডমিন
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav-action">
                                <i class="bi bi-box-arrow-in-right"></i>
                                লগইন
                            </a>
                        @endauth
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
                const refreshButton = document.getElementById('refreshDataButton');

                if (!refreshButton) {
                    return;
                }

                refreshButton.addEventListener('click', () => window.location.reload());
            })();
        </script>
        @stack('scripts')
    </body>
</html>
