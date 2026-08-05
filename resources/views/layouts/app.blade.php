<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FF6A00">
    <title>@yield('title', 'POS') — {{ $store->store_name ?? config('app.name') }}</title>
    <link rel="icon" type="image/png"
        href="{{ $store->logo_path ? asset('storage/' . $store->logo_path) : asset('Logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;450;500;550;600;650;700;750;800;850&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        :root {
            --accent: #FF6A00;
            --accent-subtle: rgba(255, 106, 0, .08);
            --accent-glow: rgba(255, 106, 0, .25);
            --accent-gradient: linear-gradient(135deg, #FF6A00, #FF8C42);
            --surface: #FFF;
            --surface-secondary: #FAFBFC;
            --surface-tertiary: #F3F4F6;
            --border: #E8EAED;
            --border-light: #F0F1F3;
            --text-primary: #0D0D0D;
            --text-secondary: #64748B;
            --text-tertiary: #94A3B8;
            --text-inverse: #FFF;
            --sidebar-bg: #0A0A0B;
            --sidebar-hover: rgba(255, 255, 255, .06);
            --sidebar-active: rgba(255, 106, 0, .15);
            --shadow-xs: 0 0 0 1px rgba(0, 0, 0, .03), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow-sm: 0 0 0 1px rgba(0, 0, 0, .03), 0 1px 3px rgba(0, 0, 0, .06), 0 2px 8px rgba(0, 0, 0, .04);
            --shadow-md: 0 0 0 1px rgba(0, 0, 0, .03), 0 2px 6px rgba(0, 0, 0, .06), 0 4px 16px rgba(0, 0, 0, .06);
            --shadow-lg: 0 0 0 1px rgba(0, 0, 0, .04), 0 4px 12px rgba(0, 0, 0, .08), 0 8px 32px rgba(0, 0, 0, .08);
            --shadow-xl: 0 0 0 1px rgba(0, 0, 0, .05), 0 8px 24px rgba(0, 0, 0, .1), 0 16px 48px rgba(0, 0, 0, .1);
            --radius-xs: 6px;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-2xl: 24px;
            --font: Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --mono: 'SF Mono', SFMono-Regular, Consolas, 'Liberation Mono', monospace;
            --ease: cubic-bezier(.16, 1, .3, 1);
            --ease-out: cubic-bezier(0, .55, .45, 1);
            --nav-height: 56px;
            --sidebar-w: 72px;
        }

        @media(prefers-reduced-motion:reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important
            }
        }

        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility
        }

        body {
            font-family: var(--font);
            background: var(--surface-secondary);
            color: var(--text-primary);
            overflow-x: hidden;
            font-size: .875rem;
            line-height: 1.5
        }

        img {
            max-width: 100%;
            height: auto;
            display: block
        }

        a {
            text-decoration: none;
            color: inherit
        }

        ::selection {
            background: var(--accent);
            color: var(--text-inverse)
        }

        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
            border-radius: var(--radius-xs)
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px
        }

        ::-webkit-scrollbar-track {
            background: transparent
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 8px
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-tertiary)
        }

        /* ==================== LAYOUT ==================== */
        .app {
            display: flex;
            min-height: 100vh
        }

        .app-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            transform: translateX(-100%);
            transition: transform .45s var(--ease);
            will-change: transform
        }

        .sidebar.open {
            transform: translateX(0)
        }

        .sidebar-logo {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
            overflow: hidden;
            position: relative
        }

        .sidebar-logo::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .06)
        }

        .sidebar-logo img {
            width: 40px;
            height: 40px;
            object-fit: cover
        }

        .sidebar-div {
            width: 20px;
            height: 1px;
            background: rgba(255, 255, 255, .08);
            margin: 10px 0 14px;
            flex-shrink: 0
        }

        .sidebar-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            width: 100%;
            padding: 0 14px
        }

        .sidebar-link {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
            color: rgba(255, 255, 255, .7);
            transition: all .2s var(--ease);
            position: relative;
            flex-shrink: 0
        }

        .sidebar-link:hover {
            color: rgba(255, 255, 255, .7);
            background: var(--sidebar-hover)
        }

        .sidebar-link.active {
            color: var(--accent);
            background: var(--sidebar-active)
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: -14px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--accent);
            border-radius: 0 3px 3px 0
        }

        .sidebar-link svg {
            width: 20px;
            height: 20px;
            transition: transform .2s var(--ease)
        }

        .sidebar-link:hover svg {
            transform: scale(1.1)
        }

        .sidebar-bot {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            margin-top: auto;
            width: 100%;
            padding: 0 14px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, .06)
        }

        .tip {
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--sidebar-bg);
            color: var(--text-inverse);
            padding: 5px 10px;
            border-radius: var(--radius-xs);
            font-size: .75rem;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all .2s var(--ease);
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, .08)
        }

        .sidebar-link:hover .tip {
            opacity: 1;
            transform: translateY(-50%) translateX(2px)
        }

        /* ==================== OVERLAY ==================== */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
            z-index: 99;
            opacity: 0;
            pointer-events: none;
            transition: opacity .35s var(--ease)
        }

        .overlay.show {
            opacity: 1;
            pointer-events: auto
        }

        /* ==================== MOBILE HEADER ==================== */
        .mob-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 16px;
            height: var(--nav-height);
            background: var(--surface);
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid var(--border-light);
            -webkit-backdrop-filter: blur(20px);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, .85)
        }

        .mob-head .ham {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: var(--surface-tertiary);
            border-radius: var(--radius-sm);
            cursor: pointer;
            color: var(--text-primary);
            transition: all .15s var(--ease);
            flex-shrink: 0
        }

        .mob-head .ham:hover {
            background: var(--border)
        }

        .mob-head .ham svg {
            width: 18px;
            height: 18px
        }

        .mob-head .mob-title {
            flex: 1;
            font-size: .9rem;
            font-weight: 650;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .mob-head .mob-logo {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-xs);
            overflow: hidden;
            flex-shrink: 0
        }

        .mob-head .mob-logo img {
            width: 28px;
            height: 28px;
            object-fit: cover
        }

        @supports(-webkit-backdrop-filter:blur(20px)) {
            .mob-head {
                background: rgba(255, 255, 255, .75)
            }
        }

        /* ==================== MAIN ==================== */
        .main {
            padding: clamp(12px, 2.5vw, 20px);
            flex: 1;
            animation: fadeIn .4s var(--ease)
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ==================== PAGE HEADER ==================== */
        .pg-h {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: clamp(16px, 3vw, 24px)
        }

        .pg-h h1 {
            font-size: clamp(1.15rem, 3vw, 1.5rem);
            font-weight: 700;
            letter-spacing: -.03em;
            color: var(--text-primary)
        }

        .pg-h p {
            font-size: .875rem;
            color: var(--text-secondary);
            margin-top: 2px;
            font-weight: 400
        }

        /* ==================== ALERT ==================== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: .8125rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            animation: slideDown .35s var(--ease)
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .alert-success {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0
        }

        .alert-danger {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA
        }

        /* ==================== CARD ==================== */
        .card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
            transition: box-shadow .25s var(--ease)
        }

        .card:hover {
            box-shadow: var(--shadow-sm)
        }

        .card-h {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px
        }

        .card-h h6 {
            font-size: .8125rem;
            font-weight: 650;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-secondary);
            margin: 0
        }

        .card-b {
            padding: clamp(16px, 2.5vw, 24px)
        }

        .card-b-sm {
            padding: clamp(12px, 2vw, 20px)
        }

        /* ==================== STAT CARD ==================== */
        .stat-c {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: clamp(14px, 2vw, 20px);
            box-shadow: var(--shadow-xs);
            transition: all .25s var(--ease);
            position: relative;
            overflow: hidden
        }

        .stat-c::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-gradient);
            opacity: 0;
            transition: opacity .25s var(--ease)
        }

        .stat-c:hover::before {
            opacity: 1
        }

        .stat-c:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px)
        }

        .stat-l {
            font-size: .75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: .03em
        }

        .stat-v {
            font-size: clamp(1.1rem, 3vw, 1.4rem);
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -.02em;
            margin-top: 6px
        }

        .stat-ic {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative
        }

        .stat-ic svg {
            width: 18px;
            height: 18px
        }

        .stat-ic::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .04)
        }

        /* ==================== BUTTONS ==================== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: var(--radius-sm);
            font-weight: 550;
            font-size: .8125rem;
            font-family: inherit;
            cursor: pointer;
            transition: all .2s var(--ease);
            white-space: nowrap;
            border: none;
            text-decoration: none;
            line-height: 1.3;
            position: relative
        }

        .btn:active {
            transform: scale(.97)
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: var(--text-inverse);
            box-shadow: 0 1px 2px rgba(255, 106, 0, .25)
        }

        .btn-primary:hover {
            box-shadow: 0 2px 8px rgba(255, 106, 0, .35);
            transform: translateY(-1px)
        }

        .btn-outline {
            background: #FFF;
            border: 1px solid var(--border)
        }

        .btn-outline:hover {
            border-color: var(--text-tertiary);
            color: var(--text-primary);
            background: var(--surface-secondary)
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-secondary)
        }

        .btn-ghost:hover {
            background: var(--surface-tertiary);
            color: var(--text-primary)
        }

        .btn-danger {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA
        }

        .btn-danger:hover {
            background: #FEE2E2;
            color: #B91C1C
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: .75rem;
            border-radius: var(--radius-xs)
        }

        .btn-xs {
            padding: 3px 8px;
            font-size: .6875rem;
            border-radius: 4px
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            transition: all .15s var(--ease);
            flex-shrink: 0;
            border: none;
            background: transparent;
            cursor: pointer
        }

        .btn-icon:hover {
            background: var(--surface-tertiary);
            color: var(--text-primary)
        }

        /* ==================== FORMS ==================== */
        .form-l {
            display: block;
            font-size: .8125rem;
            font-weight: 550;
            color: var(--text-primary);
            margin-bottom: 6px
        }

        .form-i,
        .form-s {
            display: block;
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: .8125rem;
            font-family: inherit;
            color: var(--text-primary);
            background: var(--surface);
            outline: none;
            transition: all .2s var(--ease);
            -webkit-appearance: none;
            appearance: none
        }

        .form-i:hover,
        .form-s:hover {
            border-color: var(--text-tertiary)
        }

        .form-i:focus,
        .form-s:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-subtle)
        }

        .form-i[readonly] {
            background: var(--surface-tertiary)
        }

        .form-s {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 32px
        }

        /* ==================== TOGGLE ==================== */
        .tog {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none
        }

        .tog input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0
        }

        .tog-track {
            position: relative;
            width: 40px;
            height: 22px;
            background: var(--border);
            border-radius: 11px;
            transition: background .25s var(--ease);
            flex-shrink: 0
        }

        .tog-track::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 18px;
            height: 18px;
            background: var(--surface);
            border-radius: 50%;
            transition: transform .25s var(--ease);
            box-shadow: 0 1px 3px rgba(0, 0, 0, .15)
        }

        .tog input:checked+.tog-track {
            background: var(--accent)
        }

        .tog input:checked+.tog-track::before {
            transform: translateX(18px)
        }

        /* ==================== TABLE ==================== */
        .t-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%
        }

        .tbl {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0
        }

        .tbl thead {
            position: sticky;
            top: 0;
            z-index: 2
        }

        .tbl thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: .6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-tertiary);
            background: var(--surface-secondary);
            border-bottom: 1px solid var(--border-light)
        }

        .tbl tbody td {
            padding: 12px 14px;
            font-size: .8125rem;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
            color: var(--text-primary)
        }

        .tbl tbody tr:last-child td {
            border-bottom: none
        }

        .tbl tbody tr {
            transition: background .15s var(--ease)
        }

        .tbl tbody tr:hover {
            background: var(--accent-subtle)
        }

        .tbl-nb td {
            border: none !important
        }

        .tbl .fw-bold {
            font-weight: 600
        }

        /* ==================== BADGE ==================== */
        .b {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: .6875rem;
            font-weight: 550;
            line-height: 1.3
        }

        .b-o {
            background: var(--accent-subtle);
            color: var(--accent)
        }

        .b-g {
            background: #ECFDF5;
            color: #059669
        }

        .b-r {
            background: #FEF2F2;
            color: #DC2626
        }

        .b-b {
            background: #EFF6FF;
            color: #2563EB
        }

        .b-p {
            background: #F5F3FF;
            color: #7C3AED
        }

        /* ==================== SEARCH ==================== */
        .srch {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px
        }

        .srch-w {
            flex: 1;
            position: relative
        }

        .srch-w svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--text-tertiary);
            pointer-events: none
        }

        .srch-i {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: .8125rem;
            font-family: inherit;
            background: var(--surface);
            color: var(--text-primary);
            transition: all .2s var(--ease);
            outline: none
        }

        .srch-i:hover {
            border-color: var(--text-tertiary)
        }

        .srch-i:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-subtle)
        }

        /* ==================== CATEGORY TABS ==================== */
        .cat-t {
            display: flex;
            gap: 6px;
            margin-bottom: 16px;
            overflow-x: auto;
            padding-bottom: 4px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none
        }

        .cat-t::-webkit-scrollbar {
            display: none
        }

        .cat-tb {
            padding: 7px 16px;
            border-radius: 20px;
            font-size: .8125rem;
            font-weight: 500;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            white-space: nowrap;
            transition: all .2s var(--ease);
            font-family: inherit;
            flex-shrink: 0
        }

        .cat-tb:hover {
            color: var(--text-primary);
            background: var(--surface)
        }

        .cat-tb.active {
            color: var(--accent);
            background: var(--accent-subtle);
            font-weight: 600
        }

        /* ==================== PRODUCT GRID ==================== */
        .prod-g {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px
        }

        .prod-c {
            background: var(--surface);
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
            transition: all .25s var(--ease);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-xs)
        }

        .prod-c:hover {
            border-color: var(--accent);
            box-shadow: var(--shadow-md);
            transform: translateY(-3px)
        }

        .prod-c:active {
            transform: scale(.97)
        }

        .prod-c-img {
            width: 100%;
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #FFE0B2, #FFCC80);
            position: relative;
            overflow: hidden
        }

        .prod-c-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s var(--ease)
        }

        .prod-c:hover .prod-c-img img {
            transform: scale(1.05)
        }

        .prod-c-b {
            padding: 12px 14px 14px
        }

        .prod-c-n {
            font-weight: 600;
            font-size: .8125rem;
            margin-bottom: 2px;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .prod-c-d {
            font-size: .75rem;
            color: var(--text-tertiary);
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .prod-c-f {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px
        }

        .prod-c-p {
            font-weight: 650;
            font-size: .875rem;
            color: var(--accent);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .prod-c-a {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            background: var(--accent-gradient);
            color: var(--text-inverse);
            cursor: pointer;
            transition: all .2s var(--ease);
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(255, 106, 0, .3)
        }

        .prod-c-a:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(255, 106, 0, .4)
        }

        .prod-c-a svg {
            width: 14px;
            height: 14px
        }

        /* ==================== CART ==================== */
        .cart {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden
        }

        .cart-h {
            padding: 18px 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .cart-h h2 {
            font-size: .95rem;
            font-weight: 650;
            color: var(--text-primary);
            margin: 0;
            letter-spacing: -.02em
        }

        .cart-empty {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-tertiary)
        }

        .cart-empty svg {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
            opacity: .2
        }

        .cart-empty p {
            font-size: .8125rem;
            font-weight: 500
        }

        /* ==================== CUSTOMER CARD ==================== */
        .cust-c {
            margin: 14px 16px;
            padding: 14px;
            background: var(--surface-secondary);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-light)
        }

        .cust-l {
            font-size: .6875rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-tertiary);
            font-weight: 600;
            margin-bottom: 4px
        }

        .cust-m {
            display: flex;
            gap: 20px;
            flex-wrap: wrap
        }

        .cust-m-item small {
            display: block;
            font-size: .625rem;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600
        }

        .cust-m-item span {
            font-weight: 550;
            font-size: .8125rem;
            color: var(--text-primary)
        }

        /* ==================== CART ITEMS ==================== */
        .cart-items {
            padding: 0 16px;
            max-height: 280px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch
        }

        .cart-items::-webkit-scrollbar {
            width: 3px
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px
        }

        .cart-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-light);
            align-items: center
        }

        .cart-item:last-child {
            border-bottom: none
        }

        .cart-item-img {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            background: linear-gradient(135deg, #FFE0B2, #FFCC80);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden
        }

        .cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .cart-item-info {
            flex: 1;
            min-width: 0
        }

        .cart-item-n {
            font-weight: 550;
            font-size: .8125rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .cart-item-d {
            font-size: .75rem;
            color: var(--text-tertiary)
        }

        .cart-item-q {
            font-size: .6875rem;
            color: var(--text-tertiary);
            margin-top: 1px
        }

        .cart-item-p {
            font-weight: 600;
            font-size: .8125rem;
            color: var(--accent);
            white-space: nowrap
        }

        .cart-item-r {
            text-align: right;
            flex-shrink: 0
        }

        .cart-item-rm {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            border-radius: 50%;
            cursor: pointer;
            color: var(--text-tertiary);
            font-size: .75rem;
            transition: all .15s var(--ease);
            flex-shrink: 0;
            padding: 0;
            line-height: 1
        }

        .cart-item-rm:hover {
            background: #FEE2E2;
            color: #EF4444
        }

        /* ==================== CART SUMMARY ==================== */
        .cart-sum {
            padding: 14px 18px;
            border-top: 1px solid var(--border-light)
        }

        .cart-sum-r {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            font-size: .8125rem
        }

        .cart-sum-r .l {
            color: var(--text-secondary);
            font-weight: 450
        }

        .cart-sum-r .v {
            font-weight: 550;
            color: var(--text-primary)
        }

        .cart-sum-r.ttl {
            border-top: 1px dashed var(--border);
            margin-top: 8px;
            padding-top: 12px
        }

        .cart-sum-r.ttl .l {
            color: var(--text-primary);
            font-weight: 650;
            font-size: .875rem
        }

        .cart-sum-r.ttl .v {
            color: var(--accent);
            font-weight: 700;
            font-size: 1rem
        }

        /* ==================== CART FOOTER ==================== */
        .cart-ft {
            padding: 14px 16px 18px
        }

        .btn-chk {
            width: 100%;
            padding: 13px 24px;
            border: none;
            border-radius: var(--radius-sm);
            background: var(--accent-gradient);
            color: var(--text-inverse);
            font-size: .875rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all .25s var(--ease);
            position: relative;
            overflow: hidden
        }

        .btn-chk:hover:not(:disabled) {
            box-shadow: 0 4px 16px rgba(255, 106, 0, .35);
            transform: translateY(-1px)
        }

        .btn-chk:active:not(:disabled) {
            transform: scale(.98)
        }

        .btn-chk:disabled {
            opacity: .4;
            cursor: not-allowed
        }

        .btn-chk::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 40%, rgba(255, 255, 255, .15) 50%, transparent 60%);
            background-size: 200% 100%;
            animation: shimmer 3s infinite;
            pointer-events: none
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0
            }

            100% {
                background-position: -200% 0
            }
        }

        /* ==================== UTILITY ==================== */
        .fx {
            display: flex
        }

        .fx-c {
            display: flex;
            flex-direction: column
        }

        .fx-w {
            flex-wrap: wrap
        }

        .fx-1 {
            flex: 1
        }

        .fx-s {
            flex-shrink: 0
        }

        .ai-c {
            align-items: center
        }

        .ai-s {
            align-items: flex-start
        }

        .jc-b {
            justify-content: space-between
        }

        .jc-c {
            justify-content: center
        }

        .jc-e {
            justify-content: flex-end
        }

        .g-0 {
            gap: 0
        }

        .g-1 {
            gap: 4px
        }

        .g-2 {
            gap: 8px
        }

        .g-3 {
            gap: 12px
        }

        .g-4 {
            gap: 16px
        }

        .g-5 {
            gap: 20px
        }

        .g-6 {
            gap: 24px
        }

        .mt-1 {
            margin-top: 4px
        }

        .mt-2 {
            margin-top: 8px
        }

        .mt-3 {
            margin-top: 12px
        }

        .mt-4 {
            margin-top: 16px
        }

        .mt-5 {
            margin-top: 20px
        }

        .mt-6 {
            margin-top: 24px
        }

        .mb-1 {
            margin-bottom: 4px
        }

        .mb-2 {
            margin-bottom: 8px
        }

        .mb-3 {
            margin-bottom: 12px
        }

        .mb-4 {
            margin-bottom: 16px
        }

        .mb-5 {
            margin-bottom: 20px
        }

        .ml-1 {
            margin-left: 4px
        }

        .ml-2 {
            margin-left: 8px
        }

        .mr-1 {
            margin-right: 4px
        }

        .mr-2 {
            margin-right: 8px
        }

        .p-3 {
            padding: 12px
        }

        .p-4 {
            padding: 16px
        }

        .p-5 {
            padding: 20px
        }

        .p-6 {
            padding: 24px
        }

        .w-full {
            width: 100%
        }

        .h-full {
            height: 100%
        }

        .ta-c {
            text-align: center
        }

        .ta-r {
            text-align: right
        }

        .ta-l {
            text-align: left
        }

        .fw-5 {
            font-weight: 500
        }

        .fw-6 {
            font-weight: 600
        }

        .fw-7 {
            font-weight: 700
        }

        .c-muted {
            color: var(--text-secondary)
        }

        .c-danger {
            color: #DC2626
        }

        .c-success {
            color: #059669
        }

        .c-accent {
            color: var(--accent)
        }

        .d-none {
            display: none !important
        }

        .d-inline {
            display: inline
        }

        .text-end {
            text-align: right
        }

        .text-center {
            text-align: center
        }

        .gap-1 {
            gap: 4px
        }

        .gap-2 {
            gap: 8px
        }

        .flex-wrap {
            flex-wrap: wrap
        }

        .align-items-center {
            align-items: center
        }

        .justify-content-end {
            justify-content: flex-end
        }

        .justify-content-between {
            justify-content: space-between
        }

        .d-flex {
            display: flex
        }

        .me-3 {
            margin-right: 12px
        }

        /* ==================== DISCOUNT ==================== */
        .disc-i {
            width: clamp(50px, 12vw, 80px);
            padding: 3px 8px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-xs);
            font-size: .75rem;
            text-align: right;
            font-family: inherit;
            outline: none;
            transition: border-color .2s
        }

        .disc-i:focus {
            border-color: var(--accent)
        }

        /* ==================== PAYMENT ==================== */
        .pay-s {
            display: none
        }

        .pay-s.active {
            display: block
        }

        .change-d {
            font-size: .875rem;
            font-weight: 600;
            color: #059669
        }

        .disc-mode {
            display: flex;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-xs);
            overflow: hidden;
            font-size: .6875rem;
            font-weight: 550
        }

        .disc-mode span {
            padding: 3px 8px;
            cursor: pointer;
            transition: all .15s var(--ease)
        }

        /* ==================== GRID ==================== */
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 24px
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px
        }

        .g2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px
        }

        .g15 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px
        }

        .g-main {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            align-items: start
        }

        /* ==================== AUTH ==================== */
        .auth-w {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--surface-secondary), var(--surface-tertiary))
        }

        .auth-c {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
            animation: fadeIn .5s var(--ease)
        }

        .auth-c-h {
            padding: clamp(20px, 3vw, 28px) clamp(20px, 3vw, 28px) 0;
            font-size: clamp(1rem, 3vw, 1.2rem);
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -.02em
        }
.auth-c-b .field {
            margin-bottom: 18px
        }

        .auth-c-b .field:last-of-type {
            margin-bottom: 0
        }

        .auth-c-b .chk {
            margin-top: 4px
        }

        .auth-c-b {
            padding: clamp(16px, 3vw, 20px) clamp(20px, 3vw, 28px) 
clamp(20px, 3vw, 28px)
        }

        .auth-c .btn-p {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 11px 20px;
            border-radius: var(--radius-sm);
            background: var(--accent-gradient);
            color: var(--text-inverse);
            border: none;
            font-weight: 600;
            font-size: .875rem;
            font-family: inherit;
            cursor: pointer;
            transition: all .2s var(--ease);
            width: 100%;
            text-decoration: none
        }

        .auth-c .btn-p:hover {
            box-shadow: 0 4px 16px rgba(255, 106, 0, .35);
            transform: translateY(-1px)
        }

        .auth-c .btn-p:active {
            transform: scale(.98)
        }

        .auth-c .btn-l {
            background: none;
            border: none;
            color: var(--accent);
            font-weight: 550;
            font-size: .8125rem;
            cursor: pointer;
            padding: 8px 0;
            text-decoration: none;
            font-family: inherit;
            display: inline-block
        }

        .auth-c .btn-l:hover {
            color: var(--accent);
            text-decoration: underline
        }

        .auth-c .chk {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: .8125rem;
            color: var(--text-secondary)
        }

        .auth-c .chk-i {
            accent-color: var(--accent);
            width: 16px;
            height: 16px;
            cursor: pointer;
            flex-shrink: 0;
            border-radius: 4px
        }

        .auth-c .inv {
            color: #DC2626;
            font-size: .75rem;
            font-weight: 500;
            margin-top: -12px;
            margin-bottom: 12px;
            display: block
        }

        .auth-c .al-s {
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            font-size: .8125rem;
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
            margin-bottom: 16px
        }

        .auth-c p {
            font-size: .875rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 16px
        }

        /* ==================== TOAST ==================== */
        .toast-c {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none
        }

        .toast {
            background: var(--surface);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 360px;
            pointer-events: auto;
            animation: slideIn .35s var(--ease);
            border-left: 3px solid
        }

        .toast.success {
            border-left-color: #10B981
        }

        .toast.error {
            border-left-color: #EF4444
        }

        .toast.warning {
            border-left-color: #F59E0B
        }

        .toast.info {
            border-left-color: var(--accent)
        }

        .toast-ic {
            width: 20px;
            height: 20px;
            flex-shrink: 0
        }

        .toast.success .toast-ic {
            color: #10B981
        }

        .toast.error .toast-ic {
            color: #EF4444
        }

        .toast.warning .toast-ic {
            color: #F59E0B
        }

        .toast.info .toast-ic {
            color: var(--accent)
        }

        .toast-cnt {
            flex: 1;
            min-width: 0
        }

        .toast-t {
            font-weight: 600;
            font-size: .8125rem;
            color: var(--text-primary);
            margin-bottom: 2px
        }

        .toast-m {
            font-size: .75rem;
            color: var(--text-secondary);
            line-height: 1.4
        }

        .toast-x {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-tertiary);
            padding: 0;
            line-height: 1;
            flex-shrink: 0
        }

        .toast-x:hover {
            color: var(--text-secondary)
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(40px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        @keyframes slideOut {
            to {
                opacity: 0;
                transform: translateX(40px)
            }
        }

        .toast.rm {
            animation: slideOut .25s var(--ease) forwards
        }

        /* ==================== CODE ==================== */
        .code {
            font-family: var(--mono);
            background: var(--surface-tertiary);
            padding: 2px 8px;
            border-radius: var(--radius-xs);
            font-size: .75rem;
            font-weight: 500
        }

        .code-o {
            background: var(--accent-subtle);
            color: var(--accent)
        }

        /* ==================== PAGINATION ==================== */
        .page-p {
            padding: 12px 20px;
            border-top: 1px solid var(--border-light)
        }

        .page-p nav {
            display: flex;
            gap: 4px;
            align-items: center
        }

        .page-p nav span,
        .page-p nav a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border-radius: var(--radius-xs);
            font-size: .75rem;
            font-weight: 500;
            transition: all .15s var(--ease)
        }

        .page-p nav a:hover {
            background: var(--surface-tertiary)
        }

        .page-p nav span[aria-current] {
            background: var(--accent-subtle);
            color: var(--accent);
            font-weight: 600
        }

        /* ==================== NOTE ==================== */
        .note-txt {
            font-size: .6875rem;
            color: var(--accent);
            margin-top: 2px;
            font-style: italic
        }

        .disc-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #EF4444;
            color: var(--text-inverse);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: .625rem;
            font-weight: 700;
            z-index: 2
        }

        .receipt {
            max-width: 100%;
            font-size: clamp(10px, 2.5vw, 12px)
        }

        /* ==================== TABLET 768+ ==================== */
        @media(min-width:768px) {
            .main {
                padding: clamp(20px, 3vw, 28px)
            }

            .pg-h {
                margin-bottom: 24px
            }

            .pg-h h1 {
                font-size: clamp(1.35rem, 2.8vw, 1.6rem)
            }

            .srch {
                gap: 10px;
                margin-bottom: 20px
            }

            .srch-i {
                font-size: .8125rem;
                padding: 11px 16px 11px 42px
            }

            .cat-t {
                gap: 8px;
                margin-bottom: 20px
            }

            .cat-tb {
                padding: 8px 18px;
                font-size: .8125rem
            }

            .prod-g {
                grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
                gap: 14px
            }

            .prod-c-b {
                padding: 14px 16px 16px
            }

            .prod-c-n {
                font-size: .875rem
            }

            .prod-c-p {
                font-size: .875rem
            }

            .prod-c-a {
                width: 30px;
                height: 30px
            }

            .cart-h {
                padding: 20px 24px 0
            }

            .cart-h h2 {
                font-size: 1rem
            }

            .cust-c {
                margin: 16px 18px;
                padding: 16px
            }

            .cart-items {
                padding: 0 18px;
                max-height: 320px
            }

            .cart-sum {
                padding: 16px 20px
            }

            .cart-ft {
                padding: 16px 18px 20px
            }

            .btn-chk {
                padding: 14px 24px;
                font-size: .875rem
            }

            .g-main {
                gap: 20px
            }

            .grid-4 {
                grid-template-columns: repeat(3, 1fr);
                gap: 16px
            }

            .grid-3 {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px
            }

            .grid-2 {
                grid-template-columns: 1fr 1fr;
                gap: 16px
            }

            .g2 {
                grid-template-columns: 1fr 1fr;
                gap: 16px
            }

            .g15 {
                grid-template-columns: 1fr 1.5fr;
                gap: 16px
            }

            .stat-c {
                padding: clamp(16px, 2vw, 20px)
            }

            .stat-v {
                font-size: clamp(1.3rem, 2.5vw, 1.55rem)
            }

            .stat-ic {
                width: 40px;
                height: 40px
            }

            .stat-ic svg {
                width: 20px;
                height: 20px
            }

            .tbl thead th {
                padding: 12px 16px;
                font-size: .6875rem
            }

            .tbl tbody td {
                padding: 14px 16px;
                font-size: .8125rem
            }

            .card {
                border-radius: var(--radius-xl)
            }

            .btn {
                padding: 10px 18px;
                font-size: .8125rem
            }

            .form-i,
            .form-s {
                font-size: .8125rem;
                padding: 10px 14px
            }

            .form-l {
                font-size: .8125rem
            }

            .cust-m {
                gap: 24px
            }

            .auth-c {
                max-width: 400px
            }

            .auth-c-h {
                font-size: clamp(1.15rem, 2.5vw, 1.3rem)
            }
        }

        /* ==================== DESKTOP 1024+ ==================== */
        @media(min-width:1024px) {
            .sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                transform: translateX(0)
            }

            @supports(height:100dvh) {
                .sidebar {
                    height: 100dvh
                }
            }

            .overlay {
                display: none
            }

            .mob-head {
                display: none
            }

            .main {
                padding: 28px 32px
            }

            .pg-h {
                margin-bottom: 28px
            }

            .pg-h h1 {
                font-size: 1.65rem
            }

            .srch {
                gap: 12px;
                margin-bottom: 24px
            }

            .srch-i {
                font-size: .8125rem;
                padding: 12px 16px 12px 44px
            }

            .srch-w svg {
                left: 14px
            }

            .cat-t {
                margin-bottom: 24px;
                gap: 8px
            }

            .cat-tb {
                padding: 8px 20px;
                font-size: .8125rem
            }

            .prod-g {
                grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
                gap: 16px
            }

            .prod-c-img {
                aspect-ratio: 1/1
            }

            .prod-c-b {
                padding: 14px 18px 18px
            }

            .prod-c-n {
                font-size: .875rem
            }

            .prod-c-p {
                font-size: .9rem
            }

            .prod-c-a {
                width: 32px;
                height: 32px
            }

            .prod-c-a svg {
                width: 15px;
                height: 15px
            }

            .cart {
                position: sticky;
                top: 28px
            }

            .cart-h {
                padding: 22px 28px 0
            }

            .cart-h h2 {
                font-size: 1.05rem
            }

            .cust-c {
                margin: 18px 20px;
                padding: 16px
            }

            .cart-items {
                padding: 0 20px;
                max-height: 340px
            }

            .cart-item {
                gap: 14px;
                padding: 14px 0
            }

            .cart-item-img {
                width: 48px;
                height: 48px
            }

            .cart-item-n {
                font-size: .875rem
            }

            .cart-item-p {
                font-size: .875rem
            }

            .cart-sum {
                padding: 18px 24px
            }

            .cart-sum-r {
                font-size: .8125rem;
                padding: 6px 0
            }

            .cart-sum-r.ttl .l {
                font-size: .875rem
            }

            .cart-sum-r.ttl .v {
                font-size: 1.05rem
            }

            .cart-ft {
                padding: 16px 20px 22px
            }

            .btn-chk {
                padding: 15px 24px;
                font-size: .875rem
            }

            .g-main {
                grid-template-columns: 1fr 380px;
                gap: 28px
            }

            @auth
            .auth-w {
                margin-left: var(--sidebar-w)
            }
            @endauth

            .grid-4 {
                grid-template-columns: repeat(4, 1fr);
                gap: 18px
            }

            .grid-3 {
                grid-template-columns: repeat(3, 1fr);
                gap: 24px
            }

            .grid-2 {
                gap: 24px
            }

            .g2 {
                gap: 24px
            }

            .g15 {
                gap: 24px
            }

            .stat-c {
                padding: 20px 24px
            }

            .stat-v {
                font-size: 1.55rem
            }

            .stat-l {
                font-size: .75rem
            }

            .stat-ic {
                width: 44px;
                height: 44px
            }

            .stat-ic svg {
                width: 22px;
                height: 22px
            }

            .tbl thead th {
                padding: 12px 18px;
                font-size: .6875rem
            }

            .tbl tbody td {
                padding: 14px 18px;
                font-size: .8125rem
            }

            .btn {
                padding: 10px 20px;
                font-size: .8125rem
            }

            .form-i,
            .form-s {
                font-size: .8125rem;
                padding: 10px 14px
            }

            .form-l {
                font-size: .8125rem
            }

            .cust-m {
                gap: 28px
            }

            .disc-i {
                width: 80px;
                font-size: .75rem;
                padding: 3px 8px
            }
        }
    </style>
</head>

<body>
    <div class="app">
        @auth
        <div class="overlay" id="overlay"></div>
        <aside class="sidebar" id="sidebar" role="navigation" aria-label="Sidebar navigation">
            <div class="sidebar-logo">
                <img src="{{ $store->logo_path ? asset('storage/' . $store->logo_path) : asset('Logo.png') }}"
                    alt="{{ config('app.name') }}">
            </div>
            <div class="sidebar-div"></div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard"
                    aria-label="Dashboard">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="tip">Dashboard</span>
                </a>
                @can('do pos')
                    <a href="{{ route('pos.index') }}"
                        class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" title="POS"
                        aria-label="POS">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        <span class="tip">POS</span>
                    </a>
                @endcan
                @can('manage products')
                    <a href="{{ route('products.index') }}"
                        class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}" title="Produk"
                        aria-label="Produk">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="tip">Produk</span>
                    </a>
                @endcan
                @can('manage categories')
                    <a href="{{ route('categories.index') }}"
                        class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" title="Kategori"
                        aria-label="Kategori">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span class="tip">Kategori</span>
                    </a>
                @endcan
                @can('manage stock')
                    <a href="{{ route('stock.index') }}"
                        class="sidebar-link {{ request()->routeIs('stock.*') ? 'active' : '' }}" title="Stok"
                        aria-label="Stok">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span class="tip">Stok</span>
                    </a>
                @endcan
                <a href="{{ route('transactions.index') }}"
                    class="sidebar-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" title="Transaksi"
                    aria-label="Transaksi">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="tip">Transaksi</span>
                </a>
                @can('view reports')
                    <a href="{{ route('reports.index') }}"
                        class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" title="Laporan"
                        aria-label="Laporan">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="tip">Laporan</span>
                    </a>
                @endcan
            </nav>
            <div class="sidebar-bot">
                @can('manage settings')
                    <a href="{{ route('settings.index') }}"
                        class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" title="Pengaturan"
                        aria-label="Pengaturan">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="tip">Pengaturan</span>
                    </a>
                @endcan
                @can('manage users')
                    <a href="{{ route('users.index') }}"
                        class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}" title="Pengguna"
                        aria-label="Pengguna">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="tip">Pengguna</span>
                    </a>
                @endcan
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                <a href="#" class="sidebar-link" title="Keluar" aria-label="Keluar"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="tip">Keluar</span>
                </a>
            </div>
        </aside>
        @endauth

        <div class="app-main">
            @auth
            <div class="mob-head">
                <button class="ham" id="ham" aria-label="Toggle navigation menu" type="button">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="mob-logo">
                    <img src="{{ $store->logo_path ? asset('storage/' . $store->logo_path) : asset('Logo.png') }}"
                        alt="Logo">
                </div>
                <div class="mob-title">{{ $store->store_name ?? config('app.name') }}</div>
            </div>
            @endauth

            <main class="main" id="main">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <script>
        (function() {
            var s = document.querySelector('.sidebar'),
                o = document.getElementById('overlay'),
                h = document.getElementById('ham');
            if (!s || !o || !h) return;

            function c() {
                s.classList.remove('open');
                o.classList.remove('show');
                document.body.style.overflow = ''
            }

            function t() {
                s.classList.toggle('open');
                o.classList.toggle('show');
                document.body.style.overflow = s.classList.contains('open') ? 'hidden' : ''
            }
            h.addEventListener('click', t);
            o.addEventListener('click', c);
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) c()
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && s.classList.contains('open')) c()
            });
        })();
    </script>
    <div id="global-loading"
        style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.85);z-index:99999;justify-content:center;align-items:center;flex-direction:column;gap:16px;">
        <div
            style="width:40px;height:40px;border:4px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .8s linear infinite">
        </div>
        <div id="global-loading-msg" style="font-size:.85rem;color:var(--text-secondary);font-weight:500">Memproses...
        </div>
    </div>
    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }
    </style>
    <script>
        function showLoading(msg) {
            var el = document.getElementById('global-loading');
            if (el) {
                el.style.display = 'flex';
                if (msg) document.getElementById('global-loading-msg').textContent = msg;
            }
        }

        function hideLoading() {
            var el = document.getElementById('global-loading');
            if (el) el.style.display = 'none';
        }

        // Auto-show loading on all form submissions
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (form.tagName !== 'FORM') return;
            if (form.hasAttribute('data-no-loading')) return;

            var action = form.getAttribute('action') || '';
            var method = (form.getAttribute('method') || 'GET').toUpperCase();

            if (method === 'DELETE') {
                showLoading('Menghapus...');
            } else if (action.includes('login')) {
                showLoading('Masuk...');
            } else if (action.includes('register')) {
                showLoading('Mendaftar...');
            } else if (action.includes('password')) {
                showLoading('Memproses...');
            } else if (action.includes('toggle-status')) {
                showLoading('Memperbarui status...');
            } else if (action.includes('delete-image')) {
                showLoading('Menghapus gambar...');
            } else if (action.includes('void')) {
                showLoading('Memvoid transaksi...');
            } else if (method === 'POST' || method === 'PUT') {
                showLoading('Menyimpan...');
            }
        });

        // Show loading on navigation links (optional: only for buttons with data-loading)
        document.addEventListener('click', function(e) {
            var el = e.target.closest('[data-loading]');
            if (el) {
                showLoading(el.dataset.loading || 'Memuat...');
            }
        });
    </script>
</body>

</html>
