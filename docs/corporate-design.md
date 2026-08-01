# Corporate Design (CI/CD) – Naturschutzspürhunde Schweiz

Quelle: Canva Brand Kit «Naturschützspürhunde». Diese Datei ist die verbindliche Referenz für alle Layout- und Design-Arbeiten an der Webseite. Technische Umsetzung als CSS-Variablen: [`assets/css/variables.css`](../assets/css/variables.css).

## Logo

- Rundes Logo mit Illustration (zwei Hunde beim Spurenlesen vor Schweizer Alpenkulisse, Bach, Tannen, Schweizer Fahne), darunter der Schriftzug «NATURSCHUTZSPÜRHUNDE» und in Rot «Schweiz», darunter eine Pfotenabdruck-Grafik zwischen zwei stilisierten Bergsilhouetten.
- Datei liegt unter `assets/img/logo.png`.

## Farbpalette

Alle Werte 1:1 aus dem Canva Brand Kit übernommen. Die Rollenbezeichnungen (Primär/Sekundär/Akzent/Neutral) sind ein Vorschlag anhand der Logo-Verwendung und sollten vor dem Einsatz auf der Webseite mit dem Vorstand bestätigt werden.

| Rolle (Vorschlag)         | Hex       | Verwendung im Logo / Vorschlag Webseite                     |
|---------------------------|-----------|---------------------------------------------------------------|
| Primär – Dunkelolive       | `#373a20` | Schriftzug «NATURSCHUTZSPÜRHUNDE», Haupttextfarbe            |
| Primär dunkel – Fast-Schwarzoliv | `#2e2f1a` | Logo-Kontur/Rahmen, dunkle Flächen, Fussbereich            |
| Akzent – Rot               | `#b82020` | Schriftzug «Schweiz», Call-to-Action-Buttons (z.B. Spenden)  |
| Akzent – Rostrot/Terrakotta| `#a33122` | Sekundärer Akzent, Hover-Zustände                            |
| Sekundär – Olivgold        | `#665418` | Icons, Rahmenlinien, dezente Flächen                         |
| Sekundär – Olivkhaki       | `#928259` | Sekundärtext, Trennlinien                                     |
| Neutral hell – Creme       | `#e3d7ce` | Heller Hintergrund, Kartenflächen                             |
| Neutral – Beige/Tan        | `#d7bf9e` | Hintergrundflächen, Bild-Hinterlegungen                       |
| Neutral – Blaugrau hell    | `#bcc2cc` | Hintergrund, dezente Trennflächen                              |
| Neutral – Blaugrau         | `#abaeb9` | Sekundäre Rahmen/Icons                                         |
| Akzent – Mauve dunkel      | `#5e4150` | Dekorative Akzente, evtl. Footer                              |
| Akzent – Mauve/Beere       | `#8a5771` | Dekorative Akzente, Hover auf Mauve-Elementen                  |

## Typografie

Beide Schriftfamilien sind Google Fonts (kostenlos über Google Fonts einbindbar, keine Lizenzkosten).

| Textrolle              | Schriftart          | Schnitt        | Grösse |
|-------------------------|---------------------|----------------|--------|
| Titel                   | Roboto Condensed    | Fett (Bold)    | 42     |
| Untertitel              | Roboto Condensed    | Regular        | 32     |
| Überschrift             | Glacial Indifference| Regular        | 32     |
| Zwischenüberschrift     | Glacial Indifference| Regular (Annahme, im Brand Kit nicht individuell gesetzt) | – |
| Kopfzeile für Abschnitt | Glacial Indifference| Regular (Annahme, im Brand Kit nicht individuell gesetzt) | – |
| Fliesstext              | Glacial Indifference| Regular        | 14     |
| Zitat                   | Glacial Indifference| Kursiv (Italic)| 16     |

**Hinweis:** Für «Zwischenüberschrift» und «Kopfzeile für Abschnitt» wurden im Brand Kit keine individuellen Grössen/Schnitte hinterlegt (Platzhalter grau). Vorschlag: Zwischenüberschrift 24px, Kopfzeile für Abschnitt 18px – bitte vor Umsetzung bestätigen.

## Pflege

- Bei Änderungen am Canva Brand Kit: diese Datei und `assets/css/variables.css` von Hand nachführen (kein automatischer Sync über die Canva-Anbindung möglich).
- Neue Farben/Schriften immer zuerst hier dokumentieren, danach in den CSS-Variablen ergänzen.
