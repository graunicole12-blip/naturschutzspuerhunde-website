# Projekt-Regeln: Naturschutzspürhunde-Website

## Sicherheit: Credentials

- Niemals Zugangsdaten (Passwörter, API-Keys, Tokens, Server-/FTP-Zugangsdaten) im Klartext im Repo speichern — weder in Code, Workflows, Kommentaren, Dokumentation noch in Konfigurationsdateien.
- Deploy-Zugangsdaten für Hostpoint liegen ausschliesslich als GitHub Secrets (`SFTP_SERVER`, `SFTP_USERNAME`, `SFTP_PASSWORD`) im Repo `graunicole12-blip/naturschutzspuerhunde-website` und werden in `.github/workflows/deploy.yml` nur über `${{ secrets.NAME }}` referenziert.
- Vor jedem Commit prüfen, dass keine `.env`-Datei oder sonstige Datei mit echten Zugangsdaten mitcommittet wird (`.gitignore` deckt `.env` bereits ab).
- Neue Secrets immer über GitHub Settings → Secrets and variables → Actions eintragen lassen, nie durch Claude direkt im Klartext verarbeiten.
