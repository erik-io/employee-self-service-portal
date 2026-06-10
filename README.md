# HR Self Service Portal

<p>
  <a href="https://github.com/erik-io/hr-self-service/actions"><img src="https://github.com/erik-io/hr-self-service/workflows/tests/badge.svg" alt="Build Status"></a>
</p>

Ein internes Web-Portal zur Verwaltung von Spesenabrechnungen und Urlaubsanträgen. Mitarbeiter stellen Anträge, Vorgesetzte prüfen und genehmigen sie.

## Funktionsumfang

**Mitarbeiter**
- Spesenabrechnung erstellen und eigene Übersicht einsehen
- Urlaubsantrag stellen mit Anzeige der verbleibenden Urlaubstage
- Überschneidungsprüfung beim Einreichen (eigene Abwesenheiten, Teamauslastung)
- Antragshistorie mit Statusverlauf und Ablehnungsgrund

**Vorgesetzte**
- Offene Spesen- und Urlaubsanträge einsehen und bearbeiten (genehmigen / ablehnen)
- Historienübersicht aller Anträge mit Filterung nach Status
- Teamauslastung beim Prüfen eines Urlaubsantrags im Blick

## Technik

- PHP 8.2, Laravel 12, Blade, Tailwind CSS
- Rollen & Berechtigungen via `spatie/laravel-permission`
- Feiertage (Deutschland) via `spatie/holidays`
- Mehrsprachigkeit: Deutsch / Englisch

## Lokale Einrichtung

Voraussetzung: MySQL-Datenbank bereitstellen und Zugangsdaten in `.env` eintragen (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
```

Entwicklungsserver starten:

```bash
composer run dev
```

Tests ausführen:

```bash
composer test
```
