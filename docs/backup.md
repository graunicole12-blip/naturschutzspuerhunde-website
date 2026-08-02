# Backup und Wiederherstellung der Datenbank

Betrifft die Datenbank `upuguval_naturschutzspuerhunde` bei Hostpoint (Tabellen `admin_users`, `content_blocks`, `news`, `dogs`, `projects`).

## Automatische Backups durch Hostpoint

Hostpoint sichert alle Daten automatisch 4x täglich; die letzten 2 Wochen sind vollständig verfügbar (je nach Produkt bis zu 180 Tage zurück). Das ist die erste Absicherung, erfordert aber den Kontakt mit Hostpoint zur Wiederherstellung eines älteren Snapshots.

## Eigenes Backup erstellen (empfohlen: vor grösseren Änderungen)

1. Hostpoint Control Panel → **Advanced** → **Backup Manager** → **Datenbank-Backup**
2. Die Datenbank `upuguval_naturschutzspuerhunde` auswählen
3. Auf **Herunterladen** klicken — das Backup wird direkt als Datei heruntergeladen

**Wichtig:** Die heruntergeladene Datei enthält die Passwort-Hashes aus `admin_users` (keine Klartext-Passwörter, aber trotzdem sensibel). Nicht ins Git-Repo committen, nicht öffentlich teilen — sicher ablegen (z.B. verschlüsselter Cloud-Speicher, nur für den Vorstand zugänglich).

**Wann ein eigenes Backup erstellen:** vor Schema-Änderungen (neue Tabellen/Spalten), vor grösseren Inhalts-Aktionen (z.B. Massenimport von News), oder in regelmässigen Abständen (z.B. monatlich) als zusätzliche Sicherheit neben den automatischen Hostpoint-Backups.

## Wiederherstellung

1. Hostpoint Control Panel → **Services** → **Datenbanken**
2. Bei `upuguval_naturschutzspuerhunde` auf **phpMyAdmin** klicken
3. **Alle bestehenden Tabellen markieren und löschen** — vorher unbedingt prüfen, dass es wirklich die richtige Datenbank ist, sonst droht Datenverlust
4. Oben im Menü auf **Importieren** klicken
5. Bei **Datei auswählen** die Backup-Datei auswählen und den Import starten

Nach dem Import sollten wieder alle fünf Tabellen mit den Daten aus dem Backup-Zeitpunkt vorhanden sein.
