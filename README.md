# Konfidoo WordPress Integration

![Konfidoo Logo](logo_konfidoo.svg)

WordPress-Plugin zur Einbettung von konfidoo-Formularen über einen Gutenberg-Block.

## Kurzbeschreibung

Dieses Plugin fügt einen Gutenberg‑Block hinzu, mit dem konfidoo‑Formulare in Beiträge und Seiten eingebettet werden
können. Zusätzlich lässt sich eine globale Project‑ID in den Plugin‑Einstellungen als Fallback konfigurieren.

## Funktionen

- Gutenberg‑Block zur einfachen Einbettung von konfidoo‑Formularen
- Admin‑Einstellungsseite: globale Project‑ID als Fallback
- Block‑instanz kann eine spezifische Project‑ID überschreiben

## Installation (empfohlen: Release ZIP)

1. Automatischer Download (empfohlen):
    - https://github.com/konfidoo/kfd-wordpress/releases/latest/download/kfd-wordpress.zip
    - Öffne das neueste erfolgreiche Build (grünes Häkchen) und lade das Artifact `kfd-wordpress.zip` herunter.
    - Im WordPress Admin: `Plugins` → `Installieren` → `Plugin hochladen` → `kfd-wordpress.zip` auswählen und
      installieren.
2. Manuell über WordPress Admin (ZIP):
    - WordPress Admin öffnen
    - `Plugins` → `Installieren` → `Plugin hochladen`
    - `kfd-wordpress.zip` auswählen und installieren
    - Nach der Installation: `Aktivieren`

## Erste Konfiguration

- WordPress Admin → `Einstellungen` → `konfidoo`
- Trage hier eine globale Project‑ID ein (wird als Fallback verwendet, falls ein Block keine ID besitzt)
- Beim Einfügen des Blocks kann für jede Block‑Instanz eine eigene Project‑ID angegeben werden

## Block‑Nutzung

1. Beitrag/Seite bearbeiten
2. Gutenberg Block Liste öffnen und den `konfidoo`‑Block auswählen
3. Optional: Project‑ID im Block eintragen (wenn leer, wird die globale Project‑ID verwendet)
4. Block speichern und Beitrag/Seite aktualisieren

## Fehlerbehebung

- Kein Formular sichtbar: Prüfe, ob eine Project‑ID gesetzt ist (Block‑Einstellung oder globale Einstellung)
- Plugin lässt sich nicht aktivieren: PHP‑Version, Dateiberechtigungen und Fehler im WordPress‑Error‑Log prüfen
- Lokale Tests: Browser DevTools (Konsole/Netzwerk) auf Fehler prüfen

## Entwicklung

- Quellcode befindet sich im Repository (Ordner `plugin/` und `src/`).
- Build/Assets: `build/` enthält vorgefertigte Assets.
- Hinweise für Entwickler: siehe `docs/developer.md`.

## Support

- Issues öffnen: https://github.com/konfidoo/kfd-wordpress/issues

## Lizenz

- Siehe das Repository für Lizenzinformationen.
