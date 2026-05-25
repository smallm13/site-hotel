# Hôtel Étoile du Sud

Site web vitrine créé pour l'Hôtel Étoile du Sud à Saint-Louis, Sénégal.

## Structure du projet

- `frontend/` : HTML/CSS/JS vanilla pour le site.
- `backend/` : API Laravel 11 pour la gestion des chambres, réservations et contact.

## Installation frontend

1. Ouvrez `frontend/index.html` dans un navigateur.
2. Les fichiers JavaScript sont en vanilla et utilisent Leaflet pour la carte.

## Installation backend

1. Positionnez-vous dans `backend/`.
2. Installez Laravel 11 et le package Firebase :

```bash
composer install
composer require kreait/laravel-firebase
```

3. Copiez `.env.example` en `.env` et complétez les variables Firebase.

4. Lancez le serveur Laravel :

```bash
php artisan serve
```

## Notes

- Le frontend est conçu comme une page unique avec sections animées et interactions.
- Le backend contient des contrôleurs et services prêts pour intégrer Firestore et l'envoi d'emails.
- Les images sont des placeholders SVG et doivent être remplacées par des visuels réels.
