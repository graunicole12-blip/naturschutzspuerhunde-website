# Wireframes: Admin-Backend (Epic #4)

Grobe Layout-Referenz für die MVP-Umsetzung ([#5](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/5), [#6](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/6)). Keine finalen Entwürfe, sondern Diskussionsgrundlage. Farben/Schriften gemäss [docs/corporate-design.md](corporate-design.md) bzw. [assets/css/variables.css](../assets/css/variables.css).

## 1. Login

- Zentrierte Karte auf hellem Creme-Hintergrund (`--color-neutral-cream`)
- Vereinslogo (`assets/img/logo.png`) rund zugeschnitten, oberhalb des Titels
- Titel «Admin-Login» in Roboto Condensed Bold, Untertitel «Naturschutzspürhunde Schweiz» klein in Olivkhaki
- Felder Benutzername/Passwort, CTA-Button «Anmelden» in Rot (`--color-accent-red`)

## 2. Dashboard

- Zweispaltiges Layout: linke Sidebar (Dunkelolive, `--color-primary`) mit Navigation zu allen 8 Inhaltsbereichen (Startseite, Über uns, Naturschutzspürhunde, Projekte, Unsere Hunde, Ausbildung, Unterstützen, News & Kontakt) plus Einstellungen
- Aktiver Menüpunkt farblich hervorgehoben (Olivgold, `--color-secondary-gold`)
- Hauptbereich auf Creme-Hintergrund: Kennzahlen-Kacheln (Anzahl Seiten, News-Beiträge, Hundeprofile) und eine Liste «Letzte Änderungen»

## 3. Text-Editor

- Dropdown zur Auswahl der zu bearbeitenden Seite/des Textblocks
- Einfache Formatierungsleiste (fett, kursiv, Link, Liste, Bild einfügen)
- Textfeld für den Inhalt
- Buttons «Vorschau» (neutral) und «Speichern» (Rot, CTA-Farbe)

## Offene Punkte

- Genaue Navigation-Reihenfolge/-Gruppierung noch nicht mit Vorstand abgestimmt
- Rich-Text-Umfang (welche Formatierungen wirklich nötig sind) noch offen
- Kennzahlen auf dem Dashboard sind Platzhalter-Vorschläge, keine bestätigte Anforderung
