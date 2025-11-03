# Konfidoo WordPress Integration

![Konfidoo Logo](logo_konfidoo.svg)

WordPress-Plugin zur Einbettung von Konfidoo Forms über einen Gutenberg-Block.

## Kurzbeschreibung

Dieses Plugin fügt einen Gutenberg‑Block hinzu, mit dem Konfidoo Formulare in Beiträge und Seiten eingebettet werden
können. Zusätzlich lässt sich eine globale Project‑ID in den Plugin‑Einstellungen als Fallback konfigurieren.

## Funktionen

- Gutenberg‑Block zur einfachen Einbettung von Konfidoo Formularen
- Admin‑Einstellungsseite: globale Project‑ID als Fallback
- Block‑instanz kann eine spezifische Project‑ID überschreiben

## Installation (empfohlen: Release ZIP)

1. Aktuelle Version
   herunterladen: [kfd-wordpress.zip](https://github.com/konfidoo/kfd-wordpress/releases/latest/download/kfd-wordpress.zip)
2. WordPress Admin öffnen
3. `Plugins` → `Installieren` → `Plugin hochladen`
4. `kfd-wordpress.zip` auswählen und installieren
5. Nach der Installation: `Aktivieren`

## Erste Konfiguration

- WordPress Admin → `Einstellungen` → `konfidoo`
- Trage hier eine globale Project‑ID ein, die für alle Formulare verwendet werden soll.
- Beim Einfügen des Blocks kann für jede Block‑Instanz eine eigene Project‑ID angegeben werden, welche die globale
  Project-ID überschreibt.

## Block‑Nutzung

1. Beitrag/Seite bearbeiten
2. Gutenberg Block Liste öffnen und den `Konfidoo`‑Block auswählen
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