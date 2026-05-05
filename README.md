# Konfidoo WordPress Integration

![Konfidoo Logo](logo_konfidoo.svg)

WordPress-Plugin zur Einbettung von Konfidoo Forms über einen Gutenberg-Block.

## Kurzbeschreibung

Dieses Plugin fügt einen Gutenberg‑Block hinzu, mit dem Konfidoo Formulare in Beiträge und Seiten eingebettet werden
können. Über die Plugin‑Einstellungen lassen sich globale Standardwerte konfigurieren, die für alle Block‑Instanzen
gelten, solange kein block‑spezifischer Wert gesetzt ist.

## Funktionen

- Gutenberg‑Block zur einfachen Einbettung von Konfidoo Formularen
- Drei Anzeigetypen: Inline (`kfd-inline`), Modal (`kfd-modal`), Intro (`kfd-intro`)
- Block‑Optionen: Titel anzeigen, Seiten‑Layout anzeigen
- Admin‑Einstellungsseite mit globalen Standardwerten:
    - Globale Project‑ID als Fallback
    - Script‑URL (überschreibbar für Self‑Hosting)
    - Script‑Lademethode (`defer` / `async` / synchron)
- Block‑Instanz kann die globale Project‑ID überschreiben

## Installation (empfohlen: Release ZIP)

1. Aktuelle Version
   herunterladen: [kfd-wordpress.zip](https://github.com/konfidoo/kfd-wordpress/releases/latest/download/kfd-wordpress.zip)
2. WordPress Admin öffnen
3. `Plugins` → `Installieren` → `Plugin hochladen`
4. `kfd-wordpress.zip` auswählen und installieren
5. Nach der Installation: `Aktivieren`

## Erste Konfiguration

WordPress Admin → `Einstellungen` → `konfidoo`

| Einstellung        | Beschreibung                                                | Standard                                   |
|--------------------|-------------------------------------------------------------|--------------------------------------------|
| Project‑ID         | Globale Fallback‑ID für alle Block‑Instanzen ohne eigene ID | —                                          |
| Script‑URL         | URL des Konfidoo Front‑End‑Scripts                          | `https://konfidoo.de/elements/v01/main.js` |
| Script‑Lademethode | `defer` (empfohlen), `async` oder `synchron`                | `defer`                                    |

## Block‑Nutzung

1. Beitrag/Seite bearbeiten
2. Gutenberg‑Blockliste öffnen und den `Konfidoo`‑Block auswählen
3. Im Seitenleisten‑Panel **Configuration** einstellen:
    - **Project‑ID** — leer lassen, um die globale ID zu verwenden
    - **Configuration‑ID** — ID der gewünschten Formular‑Konfiguration
4. Im Panel **Darstellung** optional anpassen:
    - **Titel anzeigen** — blendet den Formular‑Titel ein (Standard: aus)
    - **Seiten‑Layout anzeigen** — wendet das Konfidoo‑Seiten‑Layout an (Standard: ein)
5. Im Panel **Layout** den Anzeigetyp wählen:
    - **Normal** (`kfd-inline`) — direkt in die Seite eingebettet
    - **Intro** (`kfd-intro`) — mit Einstiegs‑Teaser und Button
    - **Modal** (`kfd-modal`) — als Overlay/Popup
6. Beitrag/Seite speichern

## Fehlerbehebung

- **Kein Formular sichtbar:** Prüfe, ob eine Project‑ID gesetzt ist (Block oder globale Einstellung) und ob die
  Configuration‑ID korrekt ist.
- **Plugin lässt sich nicht aktivieren:** PHP‑Version, Dateiberechtigungen und Fehler im WordPress‑Error‑Log prüfen.
- **Block zeigt Validierungsfehler:** Tritt auf, wenn sich das gespeicherte HTML nach einem Plugin‑Update geändert hat.
  Block im Editor öffnen und erneut speichern.
- **Lokale Tests:** Browser DevTools (Konsole/Netzwerk) auf Fehler prüfen.

## Entwicklung

- Quellcode befindet sich im Repository (Ordner `plugin/` und `src/`).
- Build/Assets: `build/` enthält vorgefertigte Assets.
- Hinweise für Entwickler: siehe `docs/developer.md`.

## Support

- Issues öffnen: https://github.com/konfidoo/kfd-wordpress/issues
