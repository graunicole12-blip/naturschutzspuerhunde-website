# Projekt-Regeln: Naturschutzspürhunde-Website

## Corporate Design (CI/CD)

- Für Farben, Schriften und Logo-Vorgaben immer `docs/corporate-design.md` konsultieren und einhalten.
- Technische Umsetzung (Hex-Codes, Font-Stacks) liegt als CSS-Variablen in `assets/css/variables.css` – bei Layout-/Styling-Arbeiten immer von dort referenzieren statt Werte neu einzutippen.
- Quelle ist das Canva Brand Kit «Naturschützspürhunde»; bei Änderungen dort zuerst `docs/corporate-design.md`, danach die CSS-Variablen nachführen.

## Entwickler-Workflow

- Seit 2026-08-01: neue Arbeit (ab Epic #4 / Issue #5) läuft über Feature-Branches + Pull Request, nicht mehr direkt auf `main`.
- Branch-Namensschema: `issue-<Nummer>-kurzbeschreibung` (z.B. `issue-5-db-schema-login`).
- Pro GitHub-Issue ein eigener Branch; PR beschreibt, welches Issue er abschliesst (`Closes #<Nummer>`), damit das Issue beim Merge automatisch schliesst.
- Deploy (`deploy.yml`) läuft weiterhin nur bei Push auf `main` – Feature-Branches lösen keinen Live-Deploy aus, erst der Merge des PRs.
- Frühere Arbeit (Testseite, DB-Test, Epics-Setup) lief noch direkt auf `main` – das bleibt so, wird nicht nachträglich umgebaut.

## Sicherheit: Credentials

- Niemals Zugangsdaten (Passwörter, API-Keys, Tokens, Server-/FTP-Zugangsdaten) im Klartext im Repo speichern — weder in Code, Workflows, Kommentaren, Dokumentation noch in Konfigurationsdateien.
- Deploy-Zugangsdaten für Hostpoint liegen ausschliesslich als GitHub Secrets (`SFTP_SERVER`, `SFTP_USERNAME`, `SFTP_PASSWORD`) im Repo `graunicole12-blip/naturschutzspuerhunde-website` und werden in `.github/workflows/deploy.yml` nur über `${{ secrets.NAME }}` referenziert.
- Datenbank-Zugangsdaten (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`) liegen ebenfalls nur als GitHub Secrets. Der Workflow schreibt sie zur Deploy-Zeit in eine `config.json`, die mit hochgeladen, aber nie committet wird (`config.json` steht in `.gitignore`).
- Vor jedem Commit prüfen, dass keine `.env`-, `config.json`- oder sonstige Datei mit echten Zugangsdaten mitcommittet wird.
- Neue Secrets immer über GitHub Settings → Secrets and variables → Actions eintragen lassen, nie durch Claude direkt im Klartext verarbeiten.
