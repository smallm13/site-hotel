<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hôtel Étoile du Sud | Saint-Louis du Sénégal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Leaflet CSS pour la carte -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #FFFCF8;
            color: #2C2B28;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        :root {
            --gold: #C7A252;
            --deep-navy: #1F3B4C;
            --sand: #EADBC6;
            --terracotta: #C67A3D;
            --warm-white: #FFF9F0;
            --shadow-md: 0 25px 35px -12px rgba(0, 0, 0, 0.1);
            --transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(255, 252, 245, 0.92);
            backdrop-filter: blur(12px);
            transition: var(--transition);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            flex-wrap: wrap;
        }

        .logo h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 600;
            color: var(--deep-navy);
            letter-spacing: -0.5px;
        }

        .logo p {
            font-size: 0.7rem;
            letter-spacing: 1px;
            color: var(--terracotta);
            font-weight: 500;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            font-weight: 500;
            color: #2C2B28;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--terracotta);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-admin {
            background: var(--deep-navy);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            border: 2px solid var(--deep-navy);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-admin:hover {
            background: var(--terracotta);
            border-color: var(--terracotta);
        }

        .hero {
            margin-top: 80px;
            position: relative;
            height: 90vh;
            min-height: 650px;
        }

        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0.2) 100%);
            z-index: 2;
        }

        .hero-content {
            position: absolute;
            bottom: 15%;
            left: 5%;
            z-index: 3;
            max-width: 650px;
            color: white;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
            animation: fadeUp 0.8s ease;
        }

        .hero-content h2 {
            font-size: 3.8rem;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin: 1rem 0 1.8rem;
        }

        .features {
            padding: 5rem 0;
            background: var(--warm-white);
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-family: 'Cormorant Garamond', serif;
            color: var(--deep-navy);
        }

        .section-header span {
            color: var(--terracotta);
        }

        .features-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .feature-card {
            background: white;
            padding: 2rem 1.5rem;
            border-radius: 32px;
            text-align: center;
            width: 260px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.03);
            transition: var(--transition);
            border: 1px solid #f0e5d8;
        }

        .feature-card i {
            font-size: 2.5rem;
            color: var(--gold);
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            margin-bottom: 0.5rem;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .local-experience {
            padding: 4rem 0;
            background: #F7F2EA;
        }

        .exp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .exp-item {
            border-radius: 28px;
            overflow: hidden;
            background: white;
        }

        .exp-img {
            height: 220px;
            background-size: cover;
            transition: 0.4s;
        }

        .exp-item:hover .exp-img {
            transform: scale(1.03);
        }

        .exp-text {
            padding: 1.5rem;
        }

        .rooms-section {
            padding: 5rem 0;
        }

        .rooms-container {
            display: flex;
            flex-wrap: nowrap;
            gap: 2.5rem;
            justify-content: center;
            overflow-x: auto;
            padding-bottom: 1rem;
        }

        .room-card-big {
            background: white;
            border-radius: 36px;
            width: 340px;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
        }

        .room-card-big:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 40px -15px rgba(0, 0, 0, 0.2);
        }

        .room-media {
            height: 260px;
            background-size: cover;
            position: relative;
        }

        .room-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gold);
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-weight: 600;
            color: #1F3B4C;
        }

        .room-detail {
            padding: 1.8rem;
        }

        .room-detail h3 {
            font-size: 1.8rem;
            font-family: 'Cormorant Garamond', serif;
        }

        .room-features {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
            color: #6b5b4e;
        }

        .booking-zone {
            background: linear-gradient(120deg, #1F3B4C 0%, #2b5c6f 100%);
            padding: 5rem 0;
            color: white;
        }

        .booking-card-enhanced {
            background: rgba(255, 255, 240, 0.95);
            border-radius: 48px;
            padding: 2rem;
            color: #1e2a2e;
            box-shadow: 0 30px 40px rgba(0, 0, 0, 0.2);
        }

        .booking-flex {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .input-modern {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .input-modern label {
            font-weight: 600;
        }

        .input-modern input,
        .input-modern select {
            padding: 14px 18px;
            border-radius: 60px;
            border: 1px solid #ddd2c0;
            background: white;
        }

        .price-big {
            font-size: 2rem;
            font-weight: 800;
            color: var(--terracotta);
        }

        .btn-reserve {
            background: var(--terracotta);
            border: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: 0.2s;
        }

        .map-section {
            padding: 5rem 0;
            background: #f4f2ef;
        }

        .map-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: stretch;
        }

        .map-wrapper {
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        #map {
            width: 100%;
            height: 500px;
            border-radius: 28px;
        }

        .map-info {
            background: white;
            padding: 2.5rem;
            border-radius: 28px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .map-info h3 {
            font-size: 1.8rem;
            color: var(--deep-navy);
            margin-bottom: 1.5rem;
            font-family: 'Cormorant Garamond', serif;
        }

        .map-info-item {
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .map-info-item i {
            color: var(--terracotta);
            font-size: 1.3rem;
            margin-top: 4px;
        }

        .map-info-item p {
            margin: 0;
            color: #555;
        }

        .map-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-direction {
            background: var(--deep-navy);
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-direction:hover {
            background: var(--terracotta);
            transform: translateY(-2px);
        }

        .btn-direction.secondary {
            background: white;
            color: var(--deep-navy);
            border: 2px solid var(--deep-navy);
        }

        .btn-direction.secondary:hover {
            background: var(--warm-white);
        }

        footer {
            background: #1A2C36;
            color: #d9cfbb;
            padding: 2rem 0;
            text-align: center;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 800px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }

            .nav-actions {
                width: 100%;
                justify-content: center;
            }

            .btn-admin {
                width: 100%;
                justify-content: center;
            }

            .hero-content h2 {
                font-size: 2.2rem;
            }

            .map-container {
                grid-template-columns: 1fr;
            }

            #map {
                height: 400px;
            }

            .map-info {
                order: 1;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <h1>⭐ Étoile du Sud</h1>
                    <p>Saint-Louis • Sénégal</p>
                </div>
                <ul class="nav-links">
                    <li><a href="#accueil" class="active">Accueil</a></li>
                    <li><a href="#chambres">Chambres</a></li>
                    <li><a href="#reserver">Réservation</a></li>
                    <li><a href="#map-section">Localisation</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <div class="nav-actions">
                    <a href="/admin/login" class="btn-admin">
                        <i class="fas fa-lock"></i> Connexion
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section id="accueil">
            <div class="hero">
                <div class="swiper heroSwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" style="background-image: url('/images/façade/façade_1.png');">
                        </div>

                    </div>
                </div>
                <div class="hero-overlay"></div>
                <div class="container hero-content">
                    <h2>L'âme sénégalaise, <br>le confort d'une étoile</h2>
                    <p>Entre lagune et océan, vivez une expérience unique à Saint-Louis du Sénégal.</p>
                    <a href="#reserver" class="btn-reserve"
                        style="display: inline-block; background: #C7A252; color:#1F3B4C;">Réservez maintenant →</a>
                </div>
            </div>

            <div class="local-experience">
                <div class="container">
                    <div class="section-header">
                        <h2>Vivez <span>Saint-Louis</span> autrement</h2>
                    </div>
                    <div class="exp-grid">
                        <div class="exp-item">
                            <div class="exp-img" style="background-image: url('/images/caleche.jpeg');">
                            </div>
                            <div class="exp-text">
                                <h3>Balade en calèche</h3>
                                <p>Découvrez les ruelles coloniales.</p>
                            </div>
                        </div>
                        <div class="exp-item">
                            <div class="exp-img" style="background-image: url('/images/marche.jpg');">
                            </div>
                            <div class="exp-text">
                                <h3>Marché artisanal</h3>
                                <p>Sculptures, tissus et rencontres.</p>
                            </div>
                        </div>
                        <div class="exp-item">
                            <div class="exp-img" style="background-image: url('/images/faune.jpg');">
                            </div>
                            <div class="exp-text">
                                <h3>Réserve de faune</h3>
                                <p>Observation des oiseaux et tortues.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="chambres" class="rooms-section">
            <div class="container">
                <div class="section-header">
                    <h2>Nos hébergements <span>d'exception</span></h2>
                    <p>Chaque chambre raconte une histoire entre modernité et tradition</p>
                </div>
                <div class="rooms-container">
                    <div class="room-card-big">
                        <div class="room-media" style="background-image: url('/images/rooms/chambre-single.jpeg');">
                            <div class="room-badge">Chambre Single</div>
                        </div>
                        <div class="room-detail">
                            <h3>Chambre Single</h3>
                            <div class="room-features"><span><i class="fas fa-bed"></i> Lit single</span><span><i
                                        class="fas fa-wind"></i> Climatisation</span></div>
                            <p>Chambre confortable avec petit déjeuner inclus.</p>
                            <div class="room-price" style="font-weight:bold; color:#C67A3D;">32 000 XOF/nuit</div>
                        </div>
                    </div>
                    <div class="room-card-big">
                        <div class="room-media" style="background-image: url('/images/rooms/chambre-double.jpeg');">
                            <div class="room-badge">Chambre Double</div>
                        </div>
                        <div class="room-detail">
                            <h3>Chambre Double</h3>
                            <div class="room-features"><span><i class="fas fa-bed"></i> Lit double</span><span><i
                                        class="fas fa-wind"></i> Climatisation </span></div>
                            <p>Spacieuse avec petit déjeuner inclus et terrasse.</p>
                            <div class="room-price" style="font-weight:bold; color:#C67A3D;">39 200 XOF/nuit</div>
                        </div>
                    </div>
                    <div class="room-card-big">
                        <div class="room-media" style="background-image: url('/images/rooms/chambre-twin.jpeg');">
                            <div class="room-badge">Chambre Twin</div>
                        </div>
                        <div class="room-detail">
                            <h3>Chambre Twin </h3>
                            <div class="room-features"><span><i class="fas fa-bed"></i> Deux lits</span><span><i
                                        class="fas fa-wind"></i> Climatisation</span></div>
                            <p>Chambre confortable avec petit déjeuner inclus.</p>
                            <div class="room-price" style="font-weight:bold; color:#C67A3D;">39 200 XOF/nuit</div>
                        </div>
                    </div>
                    <div class="room-card-big">
                        <div class="room-media" style="background-image: url('/images/rooms/chambre-triple.jpeg');">
                            <div class="room-badge">Chambre Triple </div>
                        </div>
                        <div class="room-detail">
                            <h3>Chambre Triple </h3>
                            <div class="room-features"><span><i class="fas fa-bed"></i> Trois lits</span><span><i
                                        class="fas fa-wind"></i> Climatisation</span></div>
                            <p>Chambre confortable avec petit déjeuner inclus.</p>
                            <div class="room-price" style="font-weight:bold; color:#C67A3D;">54 000 XOF/nuit</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="reserver" class="booking-zone">
            <div class="container">
                <form id="reservationForm" method="POST" action="/api/reservations">
                    @csrf
                    <div class="booking-card-enhanced">
                        <h2 style="font-size: 1.8rem; margin-bottom: 1rem;"><i class="fas fa-star-of-life"></i> Réservez
                            votre séjour</h2>
                        <div class="booking-flex">
                            <div class="input-modern"><label>Arrivée</label><input type="date" id="checkin2"
                                    name="checkin" required></div>
                            <div class="input-modern"><label>Départ</label><input type="date" id="checkout2"
                                    name="checkout" required></div>
                            <div class="input-modern"><label>Adultes</label><input type="number" id="adults2"
                                    name="adults" min="1" value="1" required></div>
                            <div class="input-modern"><label>Enfants</label><input type="number" id="children2"
                                    name="children" min="0" value="0" required></div>
                            <div class="input-modern"><label>Chambre</label><select id="roomType2" name="room_type"
                                    required>
                                    <option value="">-- Choisissez une chambre --</option>
                                    <option value="single">Chambre Single - 32 000 XOF</option>
                                    <option value="double">Chambre Double - 39 200 XOF</option>
                                    <option value="twin">Chambre Twin - 39 200 XOF</option>
                                    <option value="triple">Chambre Triple - 54 000 XOF</option>
                                </select></div>
                        </div>
                        <div class="booking-flex">
                            <div class="input-modern"><label>Prénom *</label><input type="text" id="firstName"
                                    name="first_name" placeholder="Votre prénom" required></div>
                            <div class="input-modern"><label>Nom *</label><input type="text" id="lastName"
                                    name="last_name" placeholder="Votre nom" required></div>
                            <div class="input-modern"><label>Email *</label><input type="email" id="email" name="email"
                                    placeholder="votre@email.com" required></div>
                            <div class="input-modern"><label>Téléphone *</label><input type="tel" id="phone"
                                    name="phone" placeholder="+221 77 XXX XX XX" required></div>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            <div><span class="price-big" id="totalFinal">0 XOF</span> <span
                                    style="font-size:0.8rem;">TTC
                                    (petit dej inclus)</span></div>
                            <button type="submit" id="confirmBookingBtn" class="btn-reserve"
                                style="background:#1F3B4C;"><i class="fas fa-check-circle"></i> Confirmer la
                                réservation</button>
                        </div>
                        <div id="bookingResultMsg"
                            style="margin-top: 1.5rem; background: #e9e0d3; border-radius: 40px; padding: 12px; font-weight: 500; text-align: center;">
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Section Carte -->
        <section id="map-section" class="map-section">
            <div class="container">
                <div class="section-header">
                    <h2>Localisation de l'<span>Hôtel</span></h2>
                </div>
                <div class="map-container">
                    <div class="map-wrapper">
                        <div id="map"></div>
                    </div>
                    <div class="map-info">
                        <h3><i class="fas fa-hotel"></i> Hôtel Étoile du Sud</h3>
                        <div class="map-info-item">
                            <i class="fas fa-map-pin"></i>
                            <p><strong>Adresse :</strong><br>Rue Maître Babacar Seye X Rue Cormier, Saint-Louis, Sénégal
                            </p>
                        </div>
                        <div class="map-info-item">
                            <i class="fas fa-phone"></i>
                            <p><strong>Téléphone :</strong><br>+221 77 656 47 52</p>
                        </div>
                        <div class="map-info-item">
                            <i class="fas fa-envelope"></i>
                            <p><strong>Email :</strong><br>hoteletoiledsud7@gmail.com</p>
                        </div>
                        <div class="map-buttons">
                            <a href="https://maps.google.com/?q=16.02239,-16.50486" target="_blank"
                                class="btn-direction secondary">
                                <i class="fas fa-external-link-alt"></i> Ouvrir dans Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="contact">
        <div class="container">
            <p><i class="fas fa-map-pin"></i> Rue Maître Babacar Seye X Rue Cormier, Saint-Louis, Sénégal | <i
                    class="fas fa-phone"></i> +221 33 961 56 31 & 77 656 47 52 | <i class="fas fa-envelope"></i>
                hoteletoiledusud7@gmail.com</p>
            <p style="margin-top: 12px;">⭐ Hôtel Étoile du Sud – L'élégance sénégalaise, entre tradition et modernité ⭐
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Leaflet JS pour la carte -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        const swiper = new Swiper('.heroSwiper', {
            loop: false,
            autoplay: { delay: 4500, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            effect: 'fade',
            fadeEffect: { crossFade: true }
        });

        const checkin = document.getElementById('checkin2');
        const checkout = document.getElementById('checkout2');
        const adultsEl = document.getElementById('adults2');
        const childrenEl = document.getElementById('children2');
        const roomSelect = document.getElementById('roomType2');
        const firstNameEl = document.getElementById('firstName');
        const lastNameEl = document.getElementById('lastName');
        const emailEl = document.getElementById('email');
        const phoneEl = document.getElementById('phone');
        const totalSpan = document.getElementById('totalFinal');
        const msgDiv = document.getElementById('bookingResultMsg');
        const reservationForm = document.getElementById('reservationForm');
        const submitBtn = document.getElementById('confirmBookingBtn');

        const ratesMap = { single: 32000, double: 39200, twin: 39200, triple: 54000 };
        const roomLabels = {
            single: 'Chambre Single',
            double: 'Chambre Double',
            twin: 'Chambre Twin',
            triple: 'Chambre Triple'
        };

        function toDateInputValue(date) {
            return date.toISOString().split('T')[0];
        }

        function parseDate(value) {
            if (!value) return null;
            const date = new Date(`${value}T00:00:00`);
            return Number.isNaN(date.getTime()) ? null : date;
        }

        function addDays(dateValue, days) {
            const date = parseDate(dateValue) || new Date();
            date.setDate(date.getDate() + days);
            return toDateInputValue(date);
        }

        function formatMoney(amount) {
            const safeAmount = Number(amount);
            if (!Number.isFinite(safeAmount) || safeAmount < 0) return '0 XOF';
            return `${safeAmount.toLocaleString('fr-FR')} XOF`;
        }

        function setMessage(type, html) {
            const styles = {
                success: { background: '#e2f0e6', color: '#1e2a2e' },
                error: { background: '#ffe6e6', color: '#9e3b2b' },
                info: { background: '#e9e0d3', color: '#1e2a2e' }
            };
            const style = styles[type] || styles.info;
            msgDiv.innerHTML = html;
            msgDiv.style.background = style.background;
            msgDiv.style.color = style.color;
            msgDiv.style.padding = '15px';
            msgDiv.style.borderRadius = '24px';
        }

        function clearMessage() {
            msgDiv.innerHTML = '';
            msgDiv.style.background = '#e9e0d3';
            msgDiv.style.color = '#1e2a2e';
        }

        function renderLaravelErrors(errors = {}) {
            const lines = Object.values(errors)
                .flat()
                .filter(Boolean)
                .map(error => `<li>${error}</li>`)
                .join('');

            return lines ? `<ul style="margin:10px 0 0 18px; text-align:left;">${lines}</ul>` : '';
        }

        function getNights(start, end) {
            const d1 = parseDate(start);
            const d2 = parseDate(end);
            if (!d1 || !d2) return 0;

            const diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
            return diff > 0 ? diff : 0;
        }

        function computeTotal() {
            const start = checkin?.value || '';
            const end = checkout?.value || '';
            const room = roomSelect?.value || '';
            const baseRate = Number(ratesMap[room]);
            const nights = getNights(start, end);

            const result = {
                valid: false,
                finalTotal: 0,
                nights,
                room,
                baseRate: Number.isFinite(baseRate) ? baseRate : 0,
                message: ''
            };

            if (!parseDate(start) || !parseDate(end)) {
                result.message = 'Veuillez choisir des dates valides.';
                totalSpan.innerText = formatMoney(0);
                return result;
            }

            if (nights <= 0) {
                result.message = 'La date de départ doit être après la date d’arrivée.';
                totalSpan.innerText = formatMoney(0);
                return result;
            }

            if (!room) {
                result.message = 'Veuillez choisir une chambre.';
                totalSpan.innerText = formatMoney(0);
                return result;
            }

            if (!Number.isFinite(baseRate) || baseRate <= 0) {
                result.message = 'Le tarif de cette chambre est indisponible.';
                totalSpan.innerText = formatMoney(0);
                return result;
            }

            const totalRooms = baseRate * nights;
            if (!Number.isFinite(totalRooms) || totalRooms <= 0) {
                result.message = 'Impossible de calculer le total.';
                totalSpan.innerText = formatMoney(0);
                return result;
            }

            result.valid = true;
            result.finalTotal = totalRooms;
            totalSpan.innerText = formatMoney(totalRooms);
            return result;
        }

        function updateCheckoutMinimum() {
            checkout.min = checkin.value ? addDays(checkin.value, 1) : '';
            if (checkout.value && checkin.value && checkout.value <= checkin.value) {
                checkout.value = addDays(checkin.value, 1);
            }
        }

        const today = new Date().toISOString().split('T')[0];
        checkin.value = today;
        checkin.min = today;
        checkout.value = addDays(today, 1);
        updateCheckoutMinimum();

        function updateTotal() {
            updateCheckoutMinimum();
            computeTotal();
        }

        checkin.addEventListener('change', updateTotal);
        checkout.addEventListener('change', updateTotal);
        adultsEl.addEventListener('input', updateTotal);
        childrenEl.addEventListener('input', updateTotal);
        roomSelect.addEventListener('change', updateTotal);
        updateTotal();

        reservationForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            clearMessage();
            updateCheckoutMinimum();
            const total = computeTotal();

            if (!total.valid) {
                setMessage('error', `<i class="fas fa-times-circle"></i> ${total.message}`);
                return;
            }

            const formData = new FormData(this);
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.dataset.originalText || submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';

            try {
                console.log('Reservation payload', Object.fromEntries(formData.entries()));

                const response = await fetch('/api/reservations', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });

                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json') ? await response.json() : {};

                console.log('Reservation response', response.status, data);

                if (response.status === 422) {
                    setMessage('error', `<i class="fas fa-times-circle"></i> Merci de corriger les informations du formulaire.${renderLaravelErrors(data.errors)}`);
                    return;
                }

                if (!response.ok || !data.success) {
                    setMessage('error', `<i class="fas fa-times-circle"></i> ${data.message || 'Impossible d’enregistrer la réservation.'}${renderLaravelErrors(data.errors)}`);
                    return;
                }

                const totalAmount = Number(data.data?.prix_total ?? total.finalTotal);
                const nights = Number(data.data?.nuits ?? total.nights);
                const perNight = nights > 0 ? totalAmount / nights : total.baseRate;

                setMessage('success', `<i class="fas fa-check-circle" style="color:#2a7f49;"></i> Réservation confirmée à l'Étoile du Sud<br>
                    <strong>${formData.get('first_name')} ${formData.get('last_name')}</strong><br>
                    ${formatMoney(perNight)}/nuit - ${roomLabels[formData.get('room_type')] || formData.get('room_type')} (${nights} nuit${nights > 1 ? 's' : ''})<br>
                    Total : ${formatMoney(totalAmount)}<br>
                    Confirmation envoyée à : ${formData.get('email')}<br>
                    Un conseiller vous contactera dans l'heure.`);

                this.reset();
                checkin.value = today;
                checkout.value = addDays(today, 1);
                updateTotal();
            } catch (error) {
                console.error('Reservation network error', error);
                setMessage('error', '<i class="fas fa-times-circle"></i> Erreur réseau. Vérifiez votre connexion puis réessayez.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.originalText;
            }
        });

        // Form submission is now handled by the form's POST action to /api/reservations
        // Client-side validation and feedback will be handled by the server response

        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.querySelectorAll('.nav-links a').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        window.addEventListener('scroll', function () {
            const sections = ['accueil', 'chambres', 'reserver', 'map-section', 'contact'];
            let current = '';
            const scrollPos = window.scrollY + 120;
            for (let s of sections) {
                const el = document.getElementById(s);
                if (el && el.offsetTop <= scrollPos && el.offsetTop + el.offsetHeight > scrollPos) {
                    current = s;
                    break;
                }
            }
            if (current === '') current = 'accueil';
            document.querySelectorAll('.nav-links a').forEach(a => {
                a.classList.remove('active');
                if (a.getAttribute('href') === '#' + current) a.classList.add('active');
            });
        });

        // --- CARTE LEAFLET ET GÉOLOCALISATION ---
        // Coordonnées de l'hôtel (Saint-Louis, Sénégal)
        // 16°01'20.6"N 16°30'17.5"W
        const hotelCoords = [16.02239, -16.50486];
        const hotelName = "Hôtel Étoile du Sud";

        // Initialiser la carte
        const map = L.map('map').setView(hotelCoords, 15);

        // Ajouter les tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Marker de l'hôtel
        const hotelMarker = L.marker(hotelCoords, {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map).bindPopup(`<strong>${hotelName}</strong><br>Saint-Louis, Sénégal`);
    </script>
</body>

</html>