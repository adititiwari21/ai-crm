<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI CRM Dashboard</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
        }

        /* ================= PERMANENT WHITE / LIGHT MODE ================= */

        body.light-mode, body {
            background: #f8fafc;
            color: #0f172a;
        }

        body.light-mode .header, body .header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        body.light-mode .sidebar, body .sidebar {
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
        }

        body.light-mode .logo, body .logo {
            color: #0f172a;
        }

        body.light-mode .menu a, body .menu a {
            color: #64748b;
        }

        body.light-mode .menu a:hover, body .menu a:hover,
        body.light-mode .menu li.active a, body .menu li.active a {
            color: #ffffff;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        body.light-mode .panel, body .panel,
        body.light-mode .stat-card, body .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        body.light-mode .search, body .search {
            background: #f1f5f9;
            border-color: #e2e8f0;
        }

        body.light-mode .search input {
            color: #172033;
        }

        body.light-mode .search input::placeholder {
            color: #8190aa;
        }

        body.light-mode .date-button {
            background: #ffffff;
            color: #172033;
            border-color: rgba(15,23,42,.08);
        }

        body.light-mode .stat-label,
        body.light-mode .page-heading p,
        body.light-mode .item-date,
        body.light-mode .item-info small {
            color: #64748b;
        }

        body.light-mode .stat-value,
        body.light-mode .panel-header h2,
        body.light-mode .item-info strong {
            color: #172033;
        }

        body.light-mode .company-card {
            background: rgba(15,23,42,.025);
            border-color: rgba(15,23,42,.07);
        }

        body.light-mode .company-name {
            color: #172033;
        }

        body.light-mode .company-description {
            color: #475569;
        }

        body.light-mode .sidebar-ai, body .sidebar-ai {
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 100%);
            border: 1px solid #e0e7ff;
            color: #0f172a;
        }

        body.light-mode .sidebar-ai h3, body .sidebar-ai h3 {
            color: #0f172a;
        }

        body.light-mode .sidebar-ai p, body .sidebar-ai p {
            color: #64748b;
        }

        body.light-mode .ai-button, body .ai-button {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        body.light-mode .ai-insight {
            background:
                radial-gradient(circle at 15% 15%, rgba(102,88,255,.12), transparent 30%),
                linear-gradient(145deg, #eef1ff, #e9efff);
        }

        body.light-mode footer {
            border-top-color: rgba(15,23,42,.08);
            color: #64748b;
        }

        /* ================= LAYOUT ================= */

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ================= SIDEBAR ================= */

        .sidebar {
            width: 263px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            padding: 20px 19px;
            background: linear-gradient(180deg, #071127 0%, #050d20 100%);
            border-right: 1px solid rgba(255,255,255,.06);
            z-index: 20;
        }

        .logo {
            height: 67px;
            display: flex;
            align-items: center;
            gap: 13px;
            padding-left: 12px;
            margin-bottom: 22px;
            font-size: 27px;
            font-weight: 700;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1764ff, #7b35ed);
            box-shadow: 0 8px 30px rgba(47, 87, 255, .28);
            font-size: 23px;
        }

        .menu {
            list-style: none;
        }

        .menu li {
            margin-bottom: 7px;
        }

        .menu a {
            height: 54px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 0 18px;
            border-radius: 10px;
            color: #a9b6d1;
            text-decoration: none;
            font-size: 15px;
            transition: .2s ease;
        }

        .menu a:hover,
        .menu li.active a {
            color: white;
            background: linear-gradient(100deg, #165cff, #4c16ca);
            box-shadow: 0 10px 28px rgba(55, 55, 220, .24);
        }

        .menu-icon {
            width: 24px;
            text-align: center;
            font-size: 19px;
        }

        /* ================= AI SIDEBAR ================= */

        .sidebar-ai {
            position: absolute;
            left: 19px;
            right: 19px;
            bottom: 25px;
            padding: 20px 17px 17px;
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
            background:
                radial-gradient(circle at 50% 20%, rgba(105,65,255,.40), transparent 42%),
                linear-gradient(145deg, #17136c, #112a85 55%, #15105e);
            border: 1px solid rgba(105,124,255,.20);
        }

        .ai-robot {
            width: 85px;
            height: 85px;
            margin: 0 auto 10px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 45px;
            background: radial-gradient(circle, #a896ff, #4930cc);
            box-shadow: 0 0 35px rgba(103,82,255,.5);
        }

        .sidebar-ai h3 {
            font-size: 17px;
            margin-bottom: 8px;
        }

        .sidebar-ai p {
            color: #ccd4ed;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 17px;
        }

        .ai-button {
            display: block;
            width: 100%;
            border: 0;
            padding: 11px;
            border-radius: 7px;
            color: white;
            font-size: 13px;
            cursor: pointer;
            background: linear-gradient(90deg, #2260ff, #4821db);
            text-decoration: none;
        }

        /* ================= MAIN ================= */

        .main {
            width: calc(100% - 263px);
            margin-left: 263px;
            min-height: 100vh;
        }

        /* ================= HEADER ================= */

        .header {
            height: 80px;
            display: flex;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid rgba(255,255,255,.035);
            background: rgba(4, 12, 29, .55);
        }

        .hamburger {
            font-size: 26px;
            color: #a9b6d1;
            margin-right: 31px;
            cursor: default;
        }

        .search {
            width: 356px;
            height: 43px;
            display: flex;
            align-items: center;
            padding: 0 15px;
            gap: 10px;
            border-radius: 11px;
            background: rgba(25, 38, 68, .70);
            border: 1px solid rgba(255,255,255,.06);
        }

        .search input {
            flex: 1;
            background: none;
            border: 0;
            outline: none;
            color: white;
            font-size: 13px;
        }

        .search input::placeholder {
            color: #66738e;
        }

        .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .header-button {
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d3dbec;
            font-size: 20px;
            background: transparent;
            cursor: pointer;
            transition: .2s ease;
            position: relative;
        }

        .header-button:hover {
            background: rgba(255,255,255,.06);
        }

        /* ================= NOTIFICATIONS ================= */

        .notification-wrapper {
            position: relative;
        }

        .notification-button span {
            position: absolute;
            top: 1px;
            right: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #7044e9;
            color: white;
            font-size: 10px;
        }

        .notification-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 280px;
            padding: 9px;
            border-radius: 14px;
            background: #101a31;
            border: 1px solid rgba(255,255,255,.08);
            box-shadow: 0 20px 50px rgba(0,0,0,.45);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: .2s ease;
            z-index: 100;
        }

        .notification-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-title {
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,.06);
            margin-bottom: 5px;
        }

        .notification-item {
            display: block;
            padding: 11px 12px;
            border-radius: 9px;
            color: #cbd5e1;
            font-size: 12px;
            line-height: 1.5;
        }

        .notification-item:hover {
            background: rgba(255,255,255,.05);
        }

        .notification-item strong {
            display: block;
            color: white;
            margin-bottom: 3px;
        }

        .notification-item small {
            color: #7f8ca6;
        }

        /* ================= PROFILE ================= */

        .profile-wrapper {
            position: relative;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 7px 9px;
            border-radius: 12px;
            cursor: pointer;
            transition: .2s ease;
            user-select: none;
        }

        .profile:hover {
            background: rgba(255,255,255,.055);
        }

        .avatar {
            width: 43px;
            height: 43px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #d6e0ff, #7485bc);
            color: #16204b;
            font-weight: 800;
            overflow: hidden;
        }

        .profile-info strong {
            display: block;
            font-size: 14px;
        }

        .profile-info small {
            color: #8490a9;
            font-size: 12px;
        }

        .profile-arrow {
            color: #9aa8c2;
            font-size: 15px;
            transition: .2s ease;
        }

        .profile.open .profile-arrow {
            transform: rotate(180deg);
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 220px;
            padding: 8px;
            border-radius: 14px;
            background: #101a31;
            border: 1px solid rgba(255,255,255,.08);
            box-shadow: 0 20px 50px rgba(0,0,0,.45);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: .2s ease;
            z-index: 100;
        }

        .profile-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-menu-header {
            padding: 12px 13px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            margin-bottom: 6px;
        }

        .profile-menu-header strong {
            display: block;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .profile-menu-header small {
            color: #7f8ca6;
            font-size: 11px;
        }

        .profile-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 13px;
            border-radius: 9px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
            transition: .2s ease;
        }

        .profile-menu a:hover {
            background: rgba(255,255,255,.06);
            color: white;
        }

        /* ================= PAGE ================= */

        .page {
            padding: 20px 26px 30px;
        }

        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .page-heading h1 {
            font-size: 29px;
            letter-spacing: -.7px;
            margin-bottom: 8px;
        }

        .page-heading p {
            color: #9aa8c2;
            font-size: 14px;
        }

        .heading-actions {
            display: flex;
            gap: 16px;
        }

        .date-button,
        .export-button {
            height: 45px;
            border-radius: 10px;
            padding: 0 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            border: 1px solid rgba(255,255,255,.06);
            background: rgba(25, 38, 68, .72);
            font-size: 13px;
        }

        .export-button {
            border: none;
            background: linear-gradient(100deg, #1760ff, #4d1bda);
            font-weight: 600;
            cursor: pointer;
        }

        /* ================= STATS ================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .stat-card {
            min-height: 151px;
            padding: 24px 22px;
            border-radius: 14px;
            background: linear-gradient(145deg, #111d36, #111b31);
            border: 1px solid rgba(255,255,255,.055);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            border-color: rgba(90,117,255,.28);
            transform: translateY(-2px);
            transition: .2s;
        }

        .stat-content {
            display: flex;
            align-items: center;
            gap: 17px;
        }

        .stat-icon {
            width: 59px;
            height: 59px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .stat-blue {
            background: linear-gradient(135deg, #2563eb, #2850cf);
        }

        .stat-green {
            background: linear-gradient(135deg, #16c779, #159a61);
        }

        .stat-purple {
            background: linear-gradient(135deg, #8750ee, #6d31d4);
        }

        .stat-orange {
            background: linear-gradient(135deg, #ff9b17, #fa431d);
        }

        .stat-label {
            color: #aeb9cf;
            font-size: 14px;
            margin-bottom: 7px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #f7f9ff;
        }

        .stat-bottom {
            margin-top: 19px;
            font-size: 12px;
            color: #d3d9e7;
        }

        .increase {
            color: #28e38b;
            font-weight: 600;
        }

        /* ================= PANELS ================= */

        .charts,
        .bottom-grid,
        .crm-grid {
            display: grid;
            gap: 14px;
            margin-bottom: 15px;
        }

        .charts {
            grid-template-columns: 1.55fr 1fr;
        }

        .bottom-grid {
            grid-template-columns: 1.2fr 1fr;
        }

        .crm-grid {
            grid-template-columns: 1fr 1fr;
        }

        .panel {
            border-radius: 14px;
            background: linear-gradient(145deg, #111d36, #111a30);
            border: 1px solid rgba(255,255,255,.055);
            padding: 17px;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .panel-header h2 {
            font-size: 16px;
            font-weight: 600;
        }

        .view-all {
            color: #b9c4db;
            text-decoration: none;
            font-size: 12px;
            padding: 8px 11px;
            border-radius: 7px;
            background: rgba(255,255,255,.035);
        }

        .view-all:hover {
            background: rgba(255,255,255,.08);
            color: white;
        }

        /* ================= CHART ================= */

        .chart-area {
            height: 205px;
            position: relative;
            padding: 7px 10px 25px 50px;
        }

        .grid-line {
            position: absolute;
            left: 50px;
            right: 10px;
            height: 1px;
            background: rgba(148,163,184,.10);
        }

        .g1 { top: 16px; }
        .g2 { top: 58px; }
        .g3 { top: 100px; }
        .g4 { top: 142px; }
        .g5 { top: 184px; }

        .y-labels {
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #8d99b0;
            font-size: 11px;
        }

        .revenue-svg {
            width: 100%;
            height: 195px;
            overflow: visible;
        }

        .x-labels {
            position: absolute;
            left: 50px;
            right: 8px;
            bottom: 0;
            display: flex;
            justify-content: space-between;
            color: #a0abc0;
            font-size: 11px;
        }

        /* ================= DONUT ================= */

        .category-content {
            height: 205px;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .donut {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: conic-gradient(
                #2862eb 0 31%,
                #1dbb6e 31% 58%,
                #ff861e 58% 80%,
                #914ee8 80% 100%
            );
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .donut::after, body.light-mode .donut::after, body .donut::after {
            content: "";
            width: 103px;
            height: 103px;
            border-radius: 50%;
            background: #ffffff;
            position: absolute;
        }

        .donut-center {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .donut-center strong, body.light-mode .donut-center strong, body .donut-center strong {
            display: block;
            font-size: 22px;
            color: #0f172a;
            font-weight: 800;
        }

        .donut-center span, body.light-mode .donut-center span, body .donut-center span {
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
        }

        .legend {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .legend-row, body.light-mode .legend-row, body .legend-row {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 185px;
            font-size: 13px;
            color: #0f172a;
            font-weight: 600;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .software { background: #2862eb; }
        .hardware { background: #1dbb6e; }
        .services { background: #ff861e; }
        .other { background: #914ee8; }

        .legend-row span:last-child, body.light-mode .legend-row span:last-child, body .legend-row span:last-child {
            margin-left: auto;
            color: #64748b;
            font-weight: 700;
        }

        /* ================= LIST ================= */

        .list {
            display: flex;
            flex-direction: column;
        }

        .list-item {
            min-height: 62px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,.045);
        }

        .list-item:last-child {
            border-bottom: 0;
        }

        .item-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .item-blue { background: #2365ee; }
        .item-green { background: #19b968; }
        .item-orange { background: #fb861d; }
        .item-purple { background: #8848e4; }

        .item-info {
            flex: 1;
            min-width: 0;
        }

        .item-info strong {
            display: block;
            color: #edf1fa;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .item-info small {
            color: #9ca8bf;
            font-size: 12px;
        }

        .item-amount {
            color: #20e68b;
            font-size: 13px;
            white-space: nowrap;
        }

        .item-date {
            color: #aeb8ca;
            font-size: 12px;
            white-space: nowrap;
        }

        .item-status {
            padding: 7px 10px;
            border-radius: 7px;
            font-size: 11px;
            white-space: nowrap;
        }

        .status-paid {
            color: #28e38b;
            background: rgba(20, 177, 105, .10);
        }

        .status-pending {
            color: #ff9a28;
            background: rgba(255, 135, 28, .10);
        }

        .empty {
            padding: 35px 15px;
            text-align: center;
            color: #7f8ca6;
            font-size: 13px;
        }

        /* ================= COMPANY ================= */

        .company-card {
            padding: 16px;
            border-radius: 12px;
            background: rgba(255,255,255,.025);
            border: 1px solid rgba(255,255,255,.05);
            margin-bottom: 10px;
        }

        .company-title {
            color: #a5b4fc;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .company-description {
            color: #cbd5e1;
            font-size: 12px;
            line-height: 1.55;
        }

        .company-name {
            margin-bottom: 5px;
            font-weight: 600;
            color: #f1f5f9;
        }

        /* ================= AI ================= */

        .ai-insight {
            padding: 18px;
            border-radius: 13px;
            background:
                radial-gradient(circle at 15% 15%, rgba(102,88,255,.22), transparent 30%),
                linear-gradient(145deg, #121b43, #101b35);
            border: 1px solid rgba(113,130,255,.14);
        }

        .ai-insight h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .ai-insight p {
            color: #b8c4df;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 14px;
        }

        .ai-insight a {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 8px;
            color: white;
            background: linear-gradient(100deg, #1760ff, #4d1bda);
            text-decoration: none;
            font-size: 12px;
        }

        /* ================= FOOTER ================= */

        footer {
            height: 49px;
            border-top: 1px solid rgba(255,255,255,.035);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #77849d;
            font-size: 12px;
            margin-top: 10px;
        }

        /* ================= RESPONSIVE ================= */

        @media(max-width: 1250px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts,
            .bottom-grid,
            .crm-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-ai {
                display: none;
            }
        }

        @media(max-width: 850px) {

            .sidebar {
                width: 75px;
                padding: 20px 10px;
            }

            .logo {
                justify-content: center;
                padding: 0;
                font-size: 0;
            }

            .menu a {
                justify-content: center;
                padding: 0;
                font-size: 0;
            }

            .main {
                width: calc(100% - 75px);
                margin-left: 75px;
            }

            .header {
                padding: 0 18px;
            }

            .search {
                width: 220px;
            }

            .page {
                padding: 20px 16px;
            }

            .heading-actions {
                display: none;
            }

            .profile-info {
                display: none;
            }
        }

        @media(max-width: 600px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .search {
                width: 160px;
            }

            .item-date {
                display: none;
            }
        }

    </style>
</head>

<body class="light-mode">

<div class="layout">

    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="logo">
            <div class="logo-icon">✦</div>
            AI CRM
        </div>

        <ul class="menu">

            <li class="active">
                <a href="{{ route('dashboard') }}">
                    <span class="menu-icon">⌂</span>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('clients.index') }}" target="_blank">
                    <span class="menu-icon">♟</span>
                    Clients
                </a>
            </li>

            <li>
                <a href="{{ route('sales.index') }}" target="_blank">
                    <span class="menu-icon">🛒</span>
                    Sales
                </a>
            </li>

            <li>
                <a href="{{ route('invoices.index') }}" target="_blank">
                    <span class="menu-icon">▤</span>
                    Invoices
                </a>
            </li>

            <li>
                <a href="{{ route('products.index') }}" target="_blank">
                    <span class="menu-icon">◆</span>
                    Products
                </a>
            </li>

            <li>
                <a href="{{ route('user-details.list') }}" target="_blank">
                    <span class="menu-icon">👥</span>
                    Users
                </a>
            </li>

            <li>
                <a href="{{ route('ai.index') }}" target="_blank">
                    <span class="menu-icon">🤖</span>
                    AI Assistant
                </a>
            </li>

        </ul>

        <div class="sidebar-ai">

            <div class="ai-robot">
                🤖
            </div>

            <h3>AI Assistant</h3>

            <p>
                Get smart insights about your business data instantly.
            </p>

            <a
                href="{{ route('ai.index') }}"
                target="_blank"
                class="ai-button"
            >
                Ask AI Assistant
            </a>

        </div>

    </aside>


    <!-- ================= MAIN ================= -->

    <main class="main">

        <!-- ================= HEADER ================= -->

        <header class="header">

            <div class="hamburger">
                ☰
            </div>

            <form action="/scrape-company" method="POST" class="search">
                @csrf
                <span>⌕</span>
                <input
                    type="text"
                    name="website"
                    placeholder="Search or scrape any website (e.g. https://lightmatter.co/ or stripe.com)..."
                    required
                >
            </form>

            <div class="header-right">




                <!-- NOTIFICATIONS -->

                <div class="notification-wrapper">

                    <button
                        type="button"
                        class="header-button notification-button"
                        id="notificationButton"
                        title="Notifications"
                    >
                        ♧

                        <span>5</span>

                    </button>


                    <div
                        class="notification-menu"
                        id="notificationMenu"
                    >

                        <div class="notification-title">
                            Notifications
                        </div>

                        <div class="notification-item">

                            <strong>
                                CRM is running
                            </strong>

                            <small>
                                Your dashboard is connected to your database.
                            </small>

                        </div>

                        <div class="notification-item">

                            <strong>
                                Company analysis available
                            </strong>

                            <small>
                                Review your latest analyzed companies.
                            </small>

                        </div>

                        <div class="notification-item">

                            <strong>
                                AI Assistant ready
                            </strong>

                            <small>
                                Ask questions about your CRM data.
                            </small>

                        </div>

                    </div>

                </div>


                <!-- PROFILE -->

                <div class="profile-wrapper">

                    <div
                        class="profile"
                        id="profileButton"
                    >

                        <div class="avatar">
                            A
                        </div>

                        <div class="profile-info">

                            <strong>
                                Admin User
                            </strong>

                            <small>
                                Administrator
                            </small>

                        </div>

                        <span class="profile-arrow">
                            ⌄
                        </span>

                    </div>


                    <div
                        class="profile-menu"
                        id="profileMenu"
                    >

                        <div class="profile-menu-header">

                            <strong>
                                Admin User
                            </strong>

                            <small>
                                Administrator
                            </small>

                        </div>


                        <a href="{{ route('user-details.list') }}">
                            👤
                            User Details
                        </a>


                        <a href="{{ route('ai.index') }}">
                            🤖
                            AI Assistant
                        </a>

                    </div>

                </div>

            </div>

        </header>


        <!-- ================= PAGE ================= -->

        <div class="page">

            <!-- PAGE HEADING -->

            <div class="page-heading">

                <div>

                    <h1>
                        Dashboard Overview
                    </h1>

                    <p>
                        Welcome back! Here's what's happening with your business today.
                    </p>

                </div>

                <div class="heading-actions">

                    <button
                        type="button"
                        class="export-button"
                        onclick="window.print()"
                    >
                        ↓ &nbsp; Export Report
                    </button>

                </div>

            </div>


            <!-- ================= PRIMARY STATS ================= -->

            <div class="stats">

                <div class="stat-card">

                    <div class="stat-content">

                        <div class="stat-icon stat-blue">
                            ₹
                        </div>

                        <div>

                            <div class="stat-label">
                                Total Revenue
                            </div>

                            <div class="stat-value">
                                ₹{{ number_format($totalRevenue, 2) }}
                            </div>

                        </div>

                    </div>

                    <div class="stat-bottom">
                        From recorded invoices
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-content">

                        <div class="stat-icon stat-green">
                            🛒
                        </div>

                        <div>

                            <div class="stat-label">
                                Total Sales
                            </div>

                            <div class="stat-value">
                                ₹{{ number_format($totalSales, 2) }}
                            </div>

                        </div>

                    </div>

                    <div class="stat-bottom">
                        From recorded sales
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-content">

                        <div class="stat-icon stat-purple">
                            👥
                        </div>

                        <div>

                            <div class="stat-label">
                                Active Clients
                            </div>

                            <div class="stat-value">
                                {{ $totalClients }}
                            </div>

                        </div>

                    </div>

                    <div class="stat-bottom">
                        Registered CRM clients
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-content">

                        <div class="stat-icon stat-orange">
                            ▤
                        </div>

                        <div>

                            <div class="stat-label">
                                Pending Invoices
                            </div>

                            <div class="stat-value">
                                {{ $pendingInvoices }}
                            </div>

                        </div>

                    </div>

                    <div class="stat-bottom">
                        Outstanding invoice count
                    </div>

                </div>

            </div>


            <!-- ================= SECONDARY STATS ================= -->

            <div class="stats">

                <div class="stat-card">

                    <div class="stat-content">

                        <div class="stat-icon stat-purple">
                            👤
                        </div>

                        <div>

                            <div class="stat-label">
                                User Records
                            </div>

                            <div class="stat-value">
                                {{ $totalUsers }}
                            </div>

                        </div>

                    </div>

                    <div class="stat-bottom">
                        <span class="increase">
                            CRM lead profiles
                        </span>
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-content">

                        <div class="stat-icon stat-blue">
                            🌐
                        </div>

                        <div>

                            <div class="stat-label">
                                Analyzed Companies
                            </div>

                            <div class="stat-value">
                                {{ $analyzedCompanies }}
                            </div>

                        </div>

                    </div>

                    <div class="stat-bottom">
                        Website intelligence records
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-content">

                        <div class="stat-icon stat-green">
                            📦
                        </div>

                        <div>

                            <div class="stat-label">
                                Products
                            </div>

                            <div class="stat-value">
                                {{ $totalProducts }}
                            </div>

                        </div>

                    </div>

                    <div class="stat-bottom">
                        Products in inventory
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-content">

                        <div class="stat-icon stat-orange">
                            🤖
                        </div>

                        <div>

                            <div class="stat-label">
                                AI Assistant
                            </div>

                            <div class="stat-value">
                                Ready
                            </div>

                        </div>

                    </div>

                    <div class="stat-bottom">
                        CRM intelligence available
                    </div>

                </div>

            </div>


            <!-- ================= CHARTS ================= -->

            <div class="charts">

                <div class="panel">

                    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">

                        <div>
                            <h2>
                                Revenue Overview & Monthly Forecasting
                            </h2>
                            <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                                Monthly Sales: <strong>₹{{ number_format($monthlyRevenue, 2) }}</strong> &nbsp;|&nbsp; 
                                30-Day Forecast: <strong style="color: #10b981;">₹{{ number_format($monthlySalesForecast, 2) }}</strong>
                            </div>
                        </div>

                        <div class="date-button">
                            Live Telemetry
                        </div>

                    </div>

                    <div class="chart-area">

                        <div class="grid-line g1"></div>
                        <div class="grid-line g2"></div>
                        <div class="grid-line g3"></div>
                        <div class="grid-line g4"></div>
                        <div class="grid-line g5"></div>

                        <div class="y-labels">
                            <span>₹80K</span>
                            <span>₹60K</span>
                            <span>₹40K</span>
                            <span>₹20K</span>
                            <span>₹0</span>
                        </div>

                        <svg
                            class="revenue-svg"
                            viewBox="0 0 700 190"
                            preserveAspectRatio="none"
                        >

                            <defs>

                                <linearGradient
                                    id="areaGradient"
                                    x1="0"
                                    x2="0"
                                    y1="0"
                                    y2="1"
                                >

                                    <stop
                                        offset="0%"
                                        stop-color="#3d6ff5"
                                        stop-opacity=".40"
                                    />

                                    <stop
                                        offset="100%"
                                        stop-color="#3d6ff5"
                                        stop-opacity=".02"
                                    />

                                </linearGradient>

                            </defs>

                            <path
                                d="M0 165
                                   L78 125
                                   L150 145
                                   L225 78
                                   L300 35
                                   L375 78
                                   L450 50
                                   L525 78
                                   L600 25
                                   L700 65
                                   L700 190
                                   L0 190 Z"
                                fill="url(#areaGradient)"
                            />

                            <polyline
                                points="
                                0,165
                                78,125
                                150,145
                                225,78
                                300,35
                                375,78
                                450,50
                                525,78
                                600,25
                                700,65"
                                fill="none"
                                stroke="#4b78ff"
                                stroke-width="3"
                            />

                        </svg>

                        <div class="x-labels">
                            <span>Week 1</span>
                            <span>Week 2</span>
                            <span>Week 3</span>
                            <span>Week 4</span>
                            <span>Current</span>
                        </div>

                    </div>

                </div>


                <div class="panel">

                    <div class="panel-header">

                        <h2>
                            Sales Overview
                        </h2>

                    </div>

                    <div class="category-content">

                        <div class="donut">

                            <div class="donut-center">

                                <strong>
                                    {{ $recentSales->count() }}
                                </strong>

                                <span>
                                    Recent Sales
                                </span>

                            </div>

                        </div>


                        <div class="legend">

                            <div class="legend-row">
                                <span class="legend-dot software"></span>
                                Sales
                                <span>{{ $recentSales->count() }}</span>
                            </div>

                            <div class="legend-row">
                                <span class="legend-dot hardware"></span>
                                Products
                                <span>{{ $totalProducts }}</span>
                            </div>

                            <div class="legend-row">
                                <span class="legend-dot services"></span>
                                Clients
                                <span>{{ $totalClients }}</span>
                            </div>

                            <div class="legend-row">
                                <span class="legend-dot other"></span>
                                Leads
                                <span>{{ $totalUsers }}</span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================= TRANSACTIONS + USERS ================= -->

            <div class="bottom-grid">

                <div class="panel">

                    <div class="panel-header">

                        <h2>
                            💸 Live Client Payments (Who Paid How Much)
                        </h2>

                        <a
                            href="{{ route('invoices.index') }}"
                            target="_blank"
                            class="view-all"
                        >
                            View All Invoices
                        </a>

                    </div>


                    <div class="list">

                        @forelse($recentPayments as $payment)

                            <div class="list-item">

                                <div class="item-icon item-blue" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    💰
                                </div>

                                <div class="item-info">

                                    <strong>
                                        {{ $payment->client->name ?? ($payment->client->company ?? 'Direct Client') }}
                                        @if(isset($payment->client->company) && $payment->client->company)
                                            <span style="font-size: 11px; color: #64748b; font-weight: 500;">({{ $payment->client->company }})</span>
                                        @endif
                                    </strong>

                                    <small>
                                        Invoice #{{ $payment->invoice_number }} • {{ $payment->due_date ? $payment->due_date->format('M d, Y') : ($payment->created_at ? $payment->created_at->format('M d, Y') : 'Payment Received') }}
                                    </small>

                                </div>

                                <div class="item-amount" style="color: #059669; font-weight: 800;">
                                    +${{ number_format($payment->amount, 2) }}
                                </div>

                                <div class="item-date">
                                    {{ $payment->created_at ? $payment->created_at->diffForHumans() : 'Recently' }}
                                </div>

                                <div class="item-status status-paid" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; font-size: 11px; font-weight: 700;">
                                    ✓ {{ $payment->status }}
                                </div>

                            </div>

                        @empty

                            @forelse($recentSales as $sale)

                                <div class="list-item">

                                    <div class="item-icon item-blue">
                                        🛒
                                    </div>

                                    <div class="item-info">

                                        <strong>
                                            @if($sale->client)
                                                {{ $sale->client->name }} ({{ $sale->client->company }})
                                            @else
                                                Direct Sales Transaction
                                            @endif
                                        </strong>

                                        <small>
                                            {{ $sale->description ?? 'Sales Revenue' }}
                                        </small>

                                    </div>

                                    <div class="item-amount" style="color: #059669; font-weight: 800;">
                                        +${{ number_format($sale->amount, 2) }}
                                    </div>

                                    <div class="item-date">
                                        {{ $sale->sale_date ?? 'Today' }}
                                    </div>

                                    <div class="item-status status-paid">
                                        ✓ Paid
                                    </div>

                                </div>

                            @empty

                                <div class="empty">
                                    No client payments recorded yet. Ingest an invoice or sync payments to see real-time transactions!
                                </div>

                            @endforelse

                        @endforelse

                    </div>

                </div>


                <div class="panel">

                    <div class="panel-header">

                        <h2>
                            Recent Leads / Users
                        </h2>

                        <a
                            href="{{ route('user-details.list') }}"
                            target="_blank"
                            class="view-all"
                        >
                            View All
                        </a>

                    </div>


                    <div class="list">

                        @forelse($recentUsers as $user)

                            <div class="list-item">

                                <div class="item-icon item-purple">
                                    👤
                                </div>

                                <div class="item-info">

                                    <strong>
                                        {{ $user->name }}
                                    </strong>

                                    <small>
                                        {{ $user->company ?? $user->email }}
                                    </small>

                                </div>

                                <div class="item-status status-paid">
                                    Lead
                                </div>

                            </div>

                        @empty

                            <div class="empty">
                                No user records available yet.
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            <!-- ================= COMPANY + AI ================= -->

            <div class="crm-grid">

                <div class="panel">

                    <div class="panel-header">

                        <h2>
                            Recent Company Analysis
                        </h2>

                        <a
                            href="{{ route('user-details.list') }}"
                            target="_blank"
                            class="view-all"
                        >
                            View All
                        </a>

                    </div>

                    <!-- DIRECT SCRAPE INPUT -->
                    <form action="/scrape-company" method="POST" style="display: flex; gap: 8px; margin-bottom: 14px;">
                        @csrf
                        <input
                            type="text"
                            name="website"
                            placeholder="Enter company website to scrape (e.g. https://lightmatter.co/)..."
                            required
                            style="flex: 1; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 12.5px; color: #0f172a; outline: none;"
                        >
                        <button
                            type="submit"
                            style="padding: 8px 14px; background: #2563eb; color: #ffffff; border: none; border-radius: 8px; font-weight: 700; font-size: 12px; cursor: pointer; white-space: nowrap;"
                        >
                            ⚡ Scrape & Enrich
                        </button>
                    </form>


                    @forelse($recentCompanyAnalyses as $company)

                        <div class="company-card">

                            <div class="company-name">
                                {{ $company->company ?? 'Company' }}
                            </div>

                            <div class="company-title">
                                {{ $company->website_title ?? 'Website Analysis' }}
                            </div>

                            <div class="company-description">

                                {{ $company->website_description ?? 'No description available.' }}

                            </div>

                        </div>

                    @empty

                        <div class="empty">
                            No company analysis available yet.
                        </div>

                    @endforelse

                </div>


                <div class="panel">

                    <div class="panel-header">

                        <h2>
                            AI CRM Insight
                        </h2>

                    </div>

                    <div class="ai-insight">

                        <h3>
                            Your CRM intelligence is ready 🤖
                        </h3>

                        <p>
                            Ask the AI Assistant about clients,
                            sales, revenue, pending invoices,
                            products, leads or analyzed companies.
                        </p>

                        <a href="{{ route('ai.index') }}" target="_blank">
                            Open AI Assistant
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <footer>
            © 2026 AI CRM. All rights reserved.
        </footer>

    </main>

</div>


<!-- ================= JAVASCRIPT ================= -->

<script>

    /* ================= PROFILE ================= */

    const profileButton =
        document.getElementById("profileButton");

    const profileMenu =
        document.getElementById("profileMenu");


    /* ================= NOTIFICATIONS ================= */

    const notificationButton =
        document.getElementById("notificationButton");

    const notificationMenu =
        document.getElementById("notificationMenu");


    /* ================= THEME ================= */

    const themeButton =
        document.getElementById("themeButton");


    /* ================= PROFILE DROPDOWN ================= */

    profileButton.addEventListener(
        "click",
        function(event) {

            event.stopPropagation();

            profileButton.classList.toggle("open");

            profileMenu.classList.toggle("show");

            notificationMenu.classList.remove("show");

        }
    );


    /* ================= NOTIFICATION DROPDOWN ================= */

    notificationButton.addEventListener(
        "click",
        function(event) {

            event.stopPropagation();

            notificationMenu.classList.toggle("show");

            profileButton.classList.remove("open");

            profileMenu.classList.remove("show");

        }
    );


    /* ================= CLOSE DROPDOWNS ================= */

    document.addEventListener(
        "click",
        function() {

            profileButton.classList.remove("open");

            profileMenu.classList.remove("show");

            notificationMenu.classList.remove("show");

        }
    );


    profileMenu.addEventListener(
        "click",
        function(event) {

            event.stopPropagation();

        }
    );


    notificationMenu.addEventListener(
        "click",
        function(event) {

            event.stopPropagation();

        }
    );


    /* ================= PERMANENT WHITE THEME ================= */

    function applySavedTheme() {
        document.body.classList.add("light-mode");
        localStorage.setItem("ai-crm-theme", "light");
    }

    applySavedTheme();

</script>

</body>

</html>