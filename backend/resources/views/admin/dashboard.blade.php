<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | Hôtel Étoile du Sud</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Chart.js pour graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f2ef;
            color: #1e2a2e;
        }

        :root {
            --gold: #c7a252;
            --deep-navy: #1f3b4c;
            --terracotta: #c67a3d;
            --light-bg: #fefaf5;
            --sidebar-width: 280px;
            --shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        /* Layout Dashboard */
        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--deep-navy);
            color: #e9e2d4;
            transition: all 0.3s;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-header {
            padding: 1.8rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,240,0.1);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gold);
        }

        .sidebar-header p {
            font-size: 0.7rem;
            opacity: 0.7;
        }

        .sidebar-menu {
            padding: 2rem 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            margin: 4px 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.2s;
            color: #ddd6cc;
        }

        .menu-item i {
            width: 24px;
            font-size: 1.2rem;
        }

        .menu-item.active, .menu-item:hover {
            background: rgba(199, 162, 82, 0.2);
            color: white;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,240,0.1);
        }

        .btn-logout {
            width: 100%;
            padding: 10px;
            background: #c95a4a;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.2s;
        }

        .btn-logout:hover {
            background: #b84a3a;
        }

        /* Main content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 1.5rem 2rem;
        }

        /* Top bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 24px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--deep-navy);
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
        }

        /* Stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 28px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: 0.2s;
            border-left: 6px solid var(--gold);
        }

        .stat-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--deep-navy);
            margin: 0.5rem 0;
        }

        /* Sections */
        .section-card {
            background: white;
            border-radius: 28px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 12px 8px;
            border-bottom: 1px solid #f0e2d4;
        }

        th {
            color: var(--terracotta);
            font-weight: 600;
        }

        .status-badge {
            background: #e3f2e8;
            color: #2b6e3c;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: #fff3e0;
            color: #c97e2a;
        }

        .status-cancelled {
            background: #ffe6e6;
            color: #9e3b2b;
        }

        .status-select {
            min-width: 130px;
            padding: 7px 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background: white;
            color: var(--deep-navy);
            font: inherit;
            cursor: pointer;
        }

        .status-select:disabled {
            cursor: wait;
            opacity: 0.65;
        }

        .btn-action {
            background: none;
            border: none;
            color: var(--gold);
            cursor: pointer;
            margin: 0 4px;
            font-size: 1.1rem;
        }

        .btn-danger {
            color: #c95a4a;
        }

        /* Graphiques flex */
        .charts-row {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .chart-box {
            flex: 1;
            min-width: 250px;
        }

        canvas {
            max-height: 280px;
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1000;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .menu-toggle {
                display: block;
                background: var(--deep-navy);
                color: white;
                border: none;
                padding: 8px 12px;
                border-radius: 12px;
            }
        }

        .menu-toggle {
            display: none;
        }

        .add-room-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
            align-items: flex-end;
        }

        .add-room-form input, .add-room-form select {
            padding: 10px 16px;
            border-radius: 40px;
            border: 1px solid #ddd;
            flex: 1;
        }

        .search-box {
            position: relative;
            width: min(360px, 100%);
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8a8178;
        }

        .search-box input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 24px;
            font: inherit;
            outline: none;
        }

        .search-box input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(199, 162, 82, 0.18);
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 28, 36, 0.58);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 2000;
        }

        .modal-backdrop.open {
            display: flex;
        }

        .confirm-modal {
            width: min(430px, 100%);
            background: white;
            border-radius: 18px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.25);
            padding: 1.4rem;
            transform: translateY(8px) scale(0.98);
            animation: modalIn 0.18s ease-out forwards;
        }

        @keyframes modalIn {
            to {
                transform: translateY(0) scale(1);
            }
        }

        .confirm-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #fbe7e4;
            color: #c95a4a;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .confirm-modal h3 {
            color: var(--deep-navy);
            font-size: 1.25rem;
            margin-bottom: 0.45rem;
        }

        .confirm-modal p {
            color: #657176;
            line-height: 1.5;
            margin-bottom: 1.3rem;
        }

        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-modal {
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            cursor: pointer;
            font: inherit;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modal-secondary {
            background: #f2eee8;
            color: var(--deep-navy);
        }

        .btn-modal-danger {
            background: #c95a4a;
            color: white;
        }

        .btn-modal-danger:hover,
        .btn-logout:hover {
            background: #b84a3a;
        }

        footer {
            text-align: center;
            margin-top: 2rem;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
<div class="dashboard">
    <!-- Sidebar Admin -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-star"></i> Étoile du Sud</h2>
            <p>Dashboard Administration</p>
        </div>
        <div class="sidebar-menu">
            <div class="menu-item active" data-view="dashboard">
                <i class="fas fa-tachometer-alt"></i> <span>Tableau de bord</span>
            </div>
            <div class="menu-item" data-view="reservations">
                <i class="fas fa-calendar-check"></i> <span>Réservations</span>
            </div>
            <div class="menu-item" data-view="rooms">
                <i class="fas fa-bed"></i> <span>Gestion des chambres</span>
            </div>
            <div class="menu-item" data-view="stats">
                <i class="fas fa-chart-line"></i> <span>Statistiques avancées</span>
            </div>
        </div>
        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST" id="logoutForm" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <div class="top-bar">
            <button class="menu-toggle" id="menuToggleBtn"><i class="fas fa-bars"></i> Menu</button>
            <div class="page-title" id="mainTitle">Tableau de bord</div>
            <div class="admin-info">
                <i class="fas fa-user-shield"></i> 
                <span>{{ session('admin_name', 'Admin') }}</span>
            </div>
        </div>

        <!-- Contenu dynamique -->
        <div id="dynamicContent"></div>
        <footer>© 2025 Hôtel Étoile du Sud - Saint-Louis du Sénégal</footer>

        <!-- Pass réservations from Laravel to JavaScript -->
        <script>
            const reservationsFromServer = @json($reservations);
            // Map room types to display names
            const roomTypesMap = {
                'single': 'Simple',
                'double': 'Double',
                'twin': 'Double lits jumeaux',
                'triple': 'Triple',
                'suite': 'Suite',
                'familiale': 'Familiale'
            };
            function getRoomDisplayName(type) {
                return roomTypesMap[type] || type;
            }
        </script>
    </div>
</div>

<div class="modal-backdrop" id="logoutConfirmModal" aria-hidden="true">
    <div class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle">
        <div class="confirm-icon"><i class="fas fa-sign-out-alt"></i></div>
        <h3 id="logoutConfirmTitle">Confirmer la déconnexion</h3>
        <p>Vous allez quitter l’espace d’administration et retourner à la page d’accueil.</p>
        <div class="confirm-actions">
            <button type="button" class="btn-modal btn-modal-secondary" id="cancelLogoutBtn">
                <i class="fas fa-times"></i> Annuler
            </button>
            <button type="button" class="btn-modal btn-modal-danger" id="confirmLogoutBtn">
                <i class="fas fa-check"></i> Se déconnecter
            </button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="deleteConfirmModal" aria-hidden="true">
    <div class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmTitle">
        <div class="confirm-icon"><i class="fas fa-trash-alt"></i></div>
        <h3 id="deleteConfirmTitle">Confirmer la suppression</h3>
        <p id="deleteConfirmText">Cette action est définitive.</p>
        <div class="confirm-actions">
            <button type="button" class="btn-modal btn-modal-secondary" id="cancelDeleteBtn">
                <i class="fas fa-times"></i> Annuler
            </button>
            <button type="button" class="btn-modal btn-modal-danger" id="confirmDeleteBtn">
                <i class="fas fa-check"></i> Supprimer
            </button>
        </div>
    </div>
</div>

<script>
    const hasServerReservations = Array.isArray(reservationsFromServer);
    let reservations = hasServerReservations ? reservationsFromServer : [];
    let rooms = [
        { id: 1, name: "Standard Sérène", type: "standard", price: 85, available: true },
        { id: 2, name: "Deluxe Ndar", type: "deluxe", price: 135, available: true },
        { id: 3, name: "Suite Étoile", type: "suite", price: 220, available: true },
        { id: 4, name: "Chambre Familiale", type: "standard", price: 120, available: true }
    ];

    // Fonctions utilitaires
    function formatCurrency(amount) {
        const value = Number(amount);
        if (!Number.isFinite(value)) return '0 XOF';
        return `${Math.round(value).toLocaleString('fr-FR')} XOF`;
    }

    function formatDate(dateValue) {
        if (!dateValue) return '';
        const parts = String(dateValue).split('-');
        if (parts.length !== 3) return dateValue;

        const [year, month, day] = parts;
        if (!year || !month || !day) return dateValue;
        return `${day.padStart(2, '0')}-${month.padStart(2, '0')}-${year}`;
    }

    function normalizeStatus(status) {
        const statuses = {
            'en_attente': 'en attente',
            'confirmee': 'confirmée',
            'annulee': 'annulée',
            'pending': 'en attente',
            'confirmed': 'confirmée',
            'cancelled': 'annulée'
        };
        return statuses[status] || status || 'en attente';
    }

    function statusToDbValue(status) {
        const statuses = {
            'en attente': 'en_attente',
            'confirmée': 'confirmee',
            'annulée': 'annulee'
        };
        return statuses[normalizeStatus(status)] || 'en_attente';
    }

    function getStatusClass(status) {
        const normalized = normalizeStatus(status);
        if (normalized === 'en attente') return 'status-pending';
        if (normalized === 'annulée') return 'status-cancelled';
        return '';
    }

    let reservationSearchTerm = '';

    function normalizeText(value) {
        return String(value ?? '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase();
    }

    function getFilteredReservations() {
        const term = normalizeText(reservationSearchTerm.trim());
        if (!term) return reservations;

        return reservations.filter(r => {
            return [
                r.id,
                r.client,
                r.checkin,
                formatDate(r.checkin),
                r.checkout,
                formatDate(r.checkout),
                r.room,
                normalizeStatus(r.status),
                r.adults,
                r.children,
                r.total
            ].some(value => normalizeText(value).includes(term));
        });
    }

    function saveToLocal() {
        localStorage.setItem("hotel_reservations", JSON.stringify(reservations));
        localStorage.setItem("hotel_rooms", JSON.stringify(rooms));
    }

    function loadFromLocal() {
        const storedRes = localStorage.getItem("hotel_reservations");
        const storedRooms = localStorage.getItem("hotel_rooms");
        if(!hasServerReservations && storedRes) reservations = JSON.parse(storedRes);
        if(storedRooms) rooms = JSON.parse(storedRooms);
    }
    loadFromLocal();

    // Stats calculs
    function getStats() {
        const totalReservations = reservations.length;
        const confirmed = reservations.filter(r => normalizeStatus(r.status) === "confirmée").length;
        const pending = reservations.filter(r => normalizeStatus(r.status) === "en attente").length;
        let totalRevenue = reservations.reduce((sum, r) => sum + Number(r.total || 0), 0);
        const availableRooms = rooms.filter(r => r.available).length;
        return { totalReservations, confirmed, pending, totalRevenue, availableRooms };
    }

    // Rendu du Dashboard principal
    function renderDashboard() {
        const stats = getStats();
        return `
            <div class="stats-grid">
                <div class="stat-card"><div class="stat-title">Réservations totales</div><div class="stat-value">${stats.totalReservations}</div></div>
                <div class="stat-card"><div class="stat-title">Confirmées</div><div class="stat-value">${stats.confirmed}</div></div>
                <div class="stat-card"><div class="stat-title">En attente</div><div class="stat-value">${stats.pending}</div></div>
                <div class="stat-card"><div class="stat-title">Chiffre d'affaires</div><div class="stat-value">${formatCurrency(stats.totalRevenue)}</div></div>
                <div class="stat-card"><div class="stat-title">Chambres disponibles</div><div class="stat-value">${stats.availableRooms} / ${rooms.length}</div></div>
            </div>
            <div class="section-card">
                <div class="section-header"><h3><i class="fas fa-chart-simple"></i> Aperçu des revenus (dernières réservations)</h3></div>
                <canvas id="miniChart" style="max-height: 200px;"></canvas>
            </div>
            <div class="section-card">
                <div class="section-header"><h3>Dernières réservations</h3><button class="btn-action" id="viewAllResBtn"><i class="fas fa-arrow-right"></i> Voir tout</button></div>
                <table><thead><tr><th>Client</th><th>Dates</th><th>Chambre</th><th>Montant</th><th>Statut</th></tr></thead><tbody>
                    ${reservations.slice(0, 3).map(r => `
                        <tr>
                            <td>${r.client}</td><td>${formatDate(r.checkin)} → ${formatDate(r.checkout)}</td><td>${r.room}</td><td>${formatCurrency(r.total)}</td>
                            <td><span class="status-badge ${getStatusClass(r.status)}">${normalizeStatus(r.status)}</span></td>
                        </tr>
                    `).join('')}
                    ${reservations.length === 0 ? '<tr><td colspan="5">Aucune réservation</td></tr>' : ''}
                </tbody></table>
            </div>
        `;
    }

    // Rendu gestion réservations
    function renderReservations() {
        const filteredReservations = getFilteredReservations();
        return `
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-list-ul"></i> Toutes les réservations</h3>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="search" id="reservationSearch" placeholder="Rechercher une réservation" value="${reservationSearchTerm}">
                    </div>
                </div>
                <div style="overflow-x:auto;"><table>
                    <thead><tr><th>ID</th><th>Client</th><th>Arrivée</th><th>Départ</th><th>Chambre</th><th>Adultes/Enf</th><th>Total</th><th>Statut</th><th>Actions</th></tr></thead>
                    <tbody>
                        ${filteredReservations.map(r => `
                            <tr>
                                <td>${r.id}</td><td>${r.client}</td><td>${formatDate(r.checkin)}</td><td>${formatDate(r.checkout)}</td><td>${r.room}</td>
                                <td>${r.adults}/${r.children}</td><td>${formatCurrency(r.total)}</td>
                                <td>
                                    <select class="status-select statusSelect" data-id="${r.id}">
                                        <option value="en_attente" ${statusToDbValue(r.status) === 'en_attente' ? 'selected' : ''}>En attente</option>
                                        <option value="confirmee" ${statusToDbValue(r.status) === 'confirmee' ? 'selected' : ''}>Confirmée</option>
                                        <option value="annulee" ${statusToDbValue(r.status) === 'annulee' ? 'selected' : ''}>Annulée</option>
                                    </select>
                                </td>
                                <td><button class="btn-action btn-danger deleteResBtn" data-id="${r.id}" title="Supprimer"><i class="fas fa-trash-alt"></i></button></td>
                            </tr>
                        `).join('')}
                        ${filteredReservations.length === 0 ? '<tr><td colspan="9">Aucune réservation trouvée</td></tr>' : ''}
                    </tbody>
                </table></div>
            </div>
        `;
    }

    // Rendu gestion chambres
    function renderRooms() {
        return `
            <div class="section-card">
                <div class="section-header"><h3><i class="fas fa-door-open"></i> Liste des chambres</h3></div>
                <table><thead><tr><th>Nom</th><th>Type</th><th>Prix/nuit</th><th>Disponibilité</th><th>Actions</th></tr></thead>
                <tbody>
                    ${rooms.map(r => `
                        <tr>
                            <td>${r.name}</td><td>${r.type}</td><td>${formatCurrency(r.price)}</td>
                            <td>${r.available ? '<span style="color:green">Disponible</span>' : '<span style="color:#b5651e">Indisponible</span>'}</td>
                            <td><button class="btn-action toggleRoomBtn" data-id="${r.id}"><i class="fas fa-toggle-${r.available ? 'on' : 'off'}"></i> ${r.available ? 'Désactiver' : 'Activer'}</button></td>
                        </tr>
                    `).join('')}
                </tbody></table>
                <div class="add-room-form" style="margin-top:2rem;">
                    <input type="text" id="newRoomName" placeholder="Nom chambre (ex: Suite Prestige)">
                    <select id="newRoomType"><option value="standard">Standard</option><option value="deluxe">Deluxe</option><option value="suite">Suite</option></select>
                    <input type="number" id="newRoomPrice" placeholder="Prix / nuit">
                    <button id="addRoomBtn" class="btn-action" style="background: var(--deep-navy); color:white; padding:10px 20px; border-radius:40px;">Ajouter chambre</button>
                </div>
            </div>
        `;
    }

    // Rendu statistiques avancées (graphique)
    function renderAdvancedStats() {
        const monthlyMap = {};
        reservations.forEach(r => {
            let month = r.checkin.substring(0,7);
            monthlyMap[month] = (monthlyMap[month] || 0) + Number(r.total || 0);
        });
        const months = Object.keys(monthlyMap).sort();
        const revenues = months.map(m => monthlyMap[m]);
        return `
            <div class="section-card">
                <div class="section-header"><h3><i class="fas fa-chart-column"></i> Revenus mensuels (XOF)</h3></div>
                <canvas id="monthlyRevenueChart" style="max-height: 300px;"></canvas>
            </div>
            <div class="section-card">
                <div class="section-header"><h3>Indicateurs clés</h3></div>
                <div class="stats-grid">
                    <div class="stat-card"><div class="stat-title">Taux d'occupation estimé</div><div class="stat-value">${Math.round((reservations.length / (rooms.length * 4)) * 100)}%</div></div>
                    <div class="stat-card"><div class="stat-title">Prix moyen par nuit</div><div class="stat-value">${formatCurrency(reservations.reduce((a,b)=>a+Number(b.total || 0),0)/(reservations.length || 1))}</div></div>
                </div>
            </div>
        `;
    }

    // Recharger le graphique de dashboard
    let miniChart = null;
    function initMiniChart() {
        const ctx = document.getElementById('miniChart');
        if(!ctx) return;
        const revenues = reservations.slice(0,5).map(r=>Number(r.total || 0));
        if(miniChart) miniChart.destroy();
        miniChart = new Chart(ctx, {
            type: 'bar',
            data: { labels: reservations.slice(0,5).map(r=>r.client.split(' ')[0]), datasets: [{ label: 'Montant (XOF)', data: revenues, backgroundColor: '#c7a25280', borderColor: '#c7a252', borderWidth: 1 }] },
            options: { responsive: true, maintainAspectRatio: true }
        });
    }

    let monthlyChart = null;
    function initMonthlyChart() {
        const canvas = document.getElementById('monthlyRevenueChart');
        if(!canvas) return;
        const monthlyMap = {};
        reservations.forEach(r => { let m = r.checkin.substring(0,7); monthlyMap[m] = (monthlyMap[m] || 0) + Number(r.total || 0); });
        const months = Object.keys(monthlyMap).sort();
        const revenues = months.map(m => monthlyMap[m]);
        if(monthlyChart) monthlyChart.destroy();
        if(months.length){
            monthlyChart = new Chart(canvas, { type: 'line', data: { labels: months, datasets: [{ label: 'Revenus (XOF)', data: revenues, borderColor: '#c67a3d', tension: 0.3, fill: true }] } });
        } else {
            canvas.getContext('2d').fillText("Aucune donnée", 50,50);
        }
    }

    // Gestion des actions
    function attachGlobalEvents() {
        const searchInput = document.getElementById('reservationSearch');
        if(searchInput){
            searchInput.addEventListener('input', () => {
                reservationSearchTerm = searchInput.value;
                refreshCurrentView();
                const nextInput = document.getElementById('reservationSearch');
                if(nextInput) {
                    nextInput.focus();
                    nextInput.setSelectionRange(nextInput.value.length, nextInput.value.length);
                }
            });
        }

        document.querySelectorAll('.statusSelect').forEach(select => {
            select.addEventListener('change', async () => {
                const id = parseInt(select.dataset.id);
                const res = reservations.find(r => r.id === id);
                const previousStatus = res ? statusToDbValue(res.status) : select.dataset.previousStatus;

                select.disabled = true;
                try {
                    const response = await fetch(`/admin/reservations/${id}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ status: select.value })
                    });
                    const data = await response.json().catch(() => ({}));

                    if(!response.ok || data.success === false) {
                        alert(data.message || "Impossible de modifier le statut.");
                        select.value = previousStatus;
                        return;
                    }

                    if(res) res.status = data.status || normalizeStatus(select.value);
                    saveToLocal();
                    refreshCurrentView();
                } catch (error) {
                    console.error('Erreur changement statut:', error);
                    alert("Erreur réseau pendant le changement de statut.");
                    select.value = previousStatus;
                } finally {
                    select.disabled = false;
                }
            });
        });
        document.querySelectorAll('.deleteResBtn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = parseInt(btn.dataset.id);
                const res = reservations.find(r => r.id === id);
                openDeleteModal({
                    id,
                    client: res?.client || ''
                });
            });
        });
        document.querySelectorAll('.toggleRoomBtn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(btn.dataset.id);
                const room = rooms.find(r => r.id === id);
                if(room) { room.available = !room.available; saveToLocal(); refreshCurrentView(); }
            });
        });
        const addRoom = document.getElementById('addRoomBtn');
        if(addRoom){
            addRoom.addEventListener('click', () => {
                const name = document.getElementById('newRoomName')?.value;
                const type = document.getElementById('newRoomType')?.value;
                const price = parseFloat(document.getElementById('newRoomPrice')?.value);
                if(name && price>0){
                    const newId = Date.now();
                    rooms.push({ id: newId, name, type, price, available: true });
                    saveToLocal();
                    refreshCurrentView();
                } else alert("Veuillez remplir nom et prix valide");
            });
        }
        const viewAll = document.getElementById('viewAllResBtn');
        if(viewAll){
            viewAll.addEventListener('click', () => {
                document.querySelector('.menu-item[data-view="reservations"]').click();
            });
        }
    }

    let currentView = "dashboard";
    function refreshCurrentView() {
        const contentDiv = document.getElementById('dynamicContent');
        const mainTitle = document.getElementById('mainTitle');
        if(currentView === "dashboard") {
            contentDiv.innerHTML = renderDashboard();
            mainTitle.innerText = "Tableau de bord";
            setTimeout(() => { initMiniChart(); attachGlobalEvents(); }, 50);
        } else if(currentView === "reservations") {
            contentDiv.innerHTML = renderReservations();
            mainTitle.innerText = "Gestion des réservations";
            attachGlobalEvents();
        } else if(currentView === "rooms") {
            contentDiv.innerHTML = renderRooms();
            mainTitle.innerText = "Gestion des chambres";
            attachGlobalEvents();
        } else if(currentView === "stats") {
            contentDiv.innerHTML = renderAdvancedStats();
            mainTitle.innerText = "Statistiques avancées";
            setTimeout(() => { initMonthlyChart(); attachGlobalEvents(); }, 50);
        }
        // réattacher les event pour les boutons dynamiques
        attachGlobalEvents();
        // pour les changements de vue dans les boutons secondaires
        document.querySelectorAll('.menu-item').forEach(item => {
            item.classList.remove('active');
            if(item.getAttribute('data-view') === currentView) item.classList.add('active');
        });
    }

    const logoutForm = document.getElementById('logoutForm');
    const logoutModal = document.getElementById('logoutConfirmModal');
    const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');
    const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
    let logoutConfirmed = false;

    function closeLogoutModal() {
        logoutModal?.classList.remove('open');
        logoutModal?.setAttribute('aria-hidden', 'true');
    }

    function openLogoutModal() {
        logoutModal?.classList.add('open');
        logoutModal?.setAttribute('aria-hidden', 'false');
        confirmLogoutBtn?.focus();
    }

    if(logoutForm){
        logoutForm.addEventListener('submit', (event) => {
            if(!logoutConfirmed) {
                event.preventDefault();
                openLogoutModal();
            }
        });
    }

    cancelLogoutBtn?.addEventListener('click', closeLogoutModal);
    logoutModal?.addEventListener('click', (event) => {
        if(event.target === logoutModal) closeLogoutModal();
    });
    document.addEventListener('keydown', (event) => {
        if(event.key === 'Escape') {
            closeLogoutModal();
            closeDeleteModal();
        }
    });
    confirmLogoutBtn?.addEventListener('click', () => {
        logoutConfirmed = true;
        confirmLogoutBtn.disabled = true;
        confirmLogoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Déconnexion...';
        logoutForm?.submit();
    });

    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteConfirmText = document.getElementById('deleteConfirmText');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let pendingDelete = { id: null, client: '', trigger: null };

    function closeDeleteModal() {
        deleteModal?.classList.remove('open');
        deleteModal?.setAttribute('aria-hidden', 'true');
        if (pendingDelete?.trigger instanceof HTMLElement) pendingDelete.trigger.focus();
        pendingDelete = { id: null, client: '', trigger: null };
        if (confirmDeleteBtn) {
            confirmDeleteBtn.disabled = false;
            confirmDeleteBtn.innerHTML = '<i class="fas fa-check"></i> Supprimer';
        }
    }

    function openDeleteModal(payload) {
        pendingDelete = { id: payload?.id ?? null, client: payload?.client ?? '', trigger: document.activeElement };
        const clientSuffix = pendingDelete.client ? ` (${pendingDelete.client})` : '';
        if (deleteConfirmText) {
            deleteConfirmText.textContent = `Supprimer cette réservation${clientSuffix} ? Cette action est définitive.`;
        }
        deleteModal?.classList.add('open');
        deleteModal?.setAttribute('aria-hidden', 'false');
        confirmDeleteBtn?.focus();
    }

    cancelDeleteBtn?.addEventListener('click', closeDeleteModal);
    deleteModal?.addEventListener('click', (event) => {
        if(event.target === deleteModal) closeDeleteModal();
    });

    confirmDeleteBtn?.addEventListener('click', async () => {
        if (!pendingDelete?.id) return;

        confirmDeleteBtn.disabled = true;
        confirmDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

        try {
            const response = await fetch(`/admin/reservations/${pendingDelete.id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            const data = await response.json().catch(() => ({}));

            if(!response.ok || data.success === false) {
                alert(data.message || "Impossible de supprimer cette réservation.");
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.innerHTML = '<i class="fas fa-check"></i> Supprimer';
                return;
            }

            reservations = reservations.filter(r => r.id !== pendingDelete.id);
            saveToLocal();
            closeDeleteModal();
            refreshCurrentView();
        } catch (error) {
            console.error('Erreur suppression réservation:', error);
            alert("Erreur réseau pendant la suppression.");
            confirmDeleteBtn.disabled = false;
            confirmDeleteBtn.innerHTML = '<i class="fas fa-check"></i> Supprimer';
        }
    });

    // menu navigation
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', () => {
            const view = item.getAttribute('data-view');
            currentView = view;
            refreshCurrentView();
            // sur mobile fermer sidebar
            if(window.innerWidth <= 768){
                document.getElementById('sidebar').classList.remove('open');
            }
        });
    });

    // Menu toggle responsive
    const toggleBtn = document.getElementById('menuToggleBtn');
    if(toggleBtn){
        toggleBtn.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('open');
        });
    }

    // initialisation
    refreshCurrentView();
    // Si resize, fermer sidebar auto sur large
    window.addEventListener('resize', () => {
        if(window.innerWidth > 768) document.getElementById('sidebar').classList.remove('open');
    });
</script>
</body>
</html>




