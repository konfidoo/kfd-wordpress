# Konfidoo Wordpress Integration

WordPress Plugin für konfidoo Formulare

1. Fügt einen Gutenberg\-Block hinzu, um konfidoo Formulare in Beiträge/Seiten einzubetten.
1. Globale Project ID in den Plugin\-Einstellungen als Fallback konfigurierbar.

Funktionen

1. Gutenberg Block für konfidoo Formulare
1. Admin\-Seite: globale Project ID
1. Automatischer Fallback auf globale Project ID, wenn Block keine ID hat

Automatischer Download (empfohlen)

1. Gehe zur GitHub Actions Seite des Projekts:
   `https://github.com/konfidoo/kfd-wordpress/actions/workflows/build-plugin.yml?query=branch%3Amain`
1. Öffne den neuesten erfolgreichen Build (grünes Häkchen)
1. Im Abschnitt "Artifacts" die Datei `kfd-wordpress.zip` herunterladen
1. Die Datei bleibt das Release\-Artifact; herunterladen und weiter mit Installation

Installation über WordPress Admin (ZIP)

1. WordPress Admin öffnen
1. `Plugins` → `Installieren` → `Plugin hochladen`
1. `kfd-wordpress.zip` auswählen und auf `Installieren` klicken
1. Nach der Installation `Aktivieren`

Erste Konfiguration

1. WordPress Admin → `Einstellungen` → `konfidoo`
1. Trage die globale Project ID ein (wird als Fallback verwendet)
1. Beim Einfügen des Blocks kann für jede Instanz eine spezifische Project ID gesetzt werden

Block Nutzung

1. Beitrag/Seite bearbeiten
1. Gutenberg Block Liste öffnen und `konfidoo` Block auswählen
1. Project ID eingeben (optional) und Block speichern

Fehlerbehebung

1. Kein Formular sichtbar: Prüfe, ob Project ID gesetzt ist (Block oder global)
1. Plugin nicht aktivierbar: PHP Version und Berechtigungen prüfen
1. Lokale Tests: Browser DevTools auf Fehler prüfen

Support

1. Issues im GitHub Repository öffnen: `https://github.com/konfidoo/kfd-wordpress/issues`

