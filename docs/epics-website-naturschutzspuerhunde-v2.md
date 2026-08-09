# Epics: Webseite Naturschutzspürhunde

> **Änderungshinweis (2026-08-09):** Aktualisierte Version der ursprünglichen Anforderungsübersicht. Epic 5 «Unsere Hunde» und Epic 8 «News & Kontakt» sind seit dieser Version umgesetzt und abgeschlossen (siehe Statusvermerke in den jeweiligen Abschnitten sowie GitHub Epics [#52](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/52) und [#55](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/55)). Quelle der Wahrheit für den laufenden Stand sind seit 2026-08-02 primär GitHub Issues/PRs; dieses Dokument dient als konsolidierte Übersicht und wird bei wesentlichen Änderungen als neue Version nachgeführt.
>
> **Nachtrag (2026-08-09, später):** Epics 3, 6, 7 und 9 sind inzwischen ebenfalls (technisch) abgeschlossen, siehe aktualisierte Statusvermerke unten. Zwei neue, über den ursprünglichen Fahrplan hinausgehende Epics sind dazugekommen: [Epic 10: Block-Editor für Admin-Textfelder](#epic-10-block-editor-für-admin-textfelder-gutenberg-prinzip) (GitHub [#123](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/123)) und [Epic 11: Startseite vollständig pflegbar](#epic-11-startseite-vollständig-im-admin-backend-pflegbar-machen) (GitHub [#137](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/137)).

Übersicht: 9 Epics, 8 davon je eines pro Hauptnavigationsbereich, dazu Epic 9 für das Admin-Backend zur Inhaltspflege. Jedes Epic kann 1:1 als GitHub Issue (Vorlage `epic.yml`) angelegt werden. Vorschlag Label: `epic` + themenspezifisches Label (z.B. `startseite`, `content`, `ueber-uns`, `admin`, ...). Milestone-Vorschlag: „Website Launch v1".

**Offene Punkte, die alle Epics betreffen** (siehe auch Antworten aus der Klärungsrunde):
- Technischer Rahmen: Mit Epic 9 wurde entschieden, Inhalte perspektivisch in einer Datenbank zu verwalten statt in rein statischen PHP-Seiten. Die Teilaufgaben zu Layout/Template in den Epics 1–8 gehen aktuell noch von der Annahme „einfache PHP-Seiten mit gemeinsamem Header/Footer" aus; sobald Epic 9 umgesetzt ist, müssen diese Epics um Teilaufgaben ergänzt werden, welche die statischen Inhalte in die Datenbank überführen (siehe Epic 9, Teilaufgabe „Bestehende Seiten umbauen").
- Texte, Bilder und weitere Inhalte müssen für die meisten Bereiche noch erarbeitet werden. Content-Erstellung ist deshalb in jedem Epic als eigene Teilaufgabe enthalten, nicht nur die technische Umsetzung.

---

# Epic 1: Startseite

## Ziel
Besuchende der Webseite erfassen auf einen Blick, wer der Verein ist, was Naturschutzspürhunde sind, welche Projekte laufen, und finden einen direkten Einstieg zum Unterstützen und zu aktuellen News.

## Kontext / Hintergrund
Die Webseite wird von Grund auf neu aufgebaut (aktuell existiert nur eine Test-PHP-Seite mit funktionierender Deploy-Pipeline). Die Startseite ist der zentrale Einstiegspunkt und muss Vision, Kernbotschaft und Handlungsaufforderung (Crowdfunding/Spenden) transportieren. **Annahme:** einfache PHP-Seite mit gemeinsamem Header/Footer-Template, bis eine allfällige CMS-Entscheidung getroffen ist.

## User Stories
- Als Besucherin der Webseite möchte ich beim Aufruf der Startseite sofort ein aussagekräftiges Bild sehen, damit ich einen emotionalen Eindruck vom Verein erhalte.
- Als Besucherin möchte ich in wenigen Sätzen die Vision des Vereins lesen, damit ich schnell verstehe, wofür sich der Verein einsetzt.
- Als Besucherin möchte ich kurz erklärt bekommen, was Naturschutzspürhunde sind, damit ich das Kernthema versteh, ohne auf eine Unterseite wechseln zu müssen.
- Als Besucherin möchte ich die aktuellen Projekte auf der Startseite sehen, damit ich einen Überblick über die Vereinsaktivitäten erhalte.
- Als potenzielle Spenderin möchte ich direkt von der Startseite aus zum Crowdfunding/Unterstützen gelangen, damit ich unkompliziert spenden oder Mitglied werden kann.
- Als Besucherin möchte ich aktuelle News auf der Startseite sehen, damit ich merke, dass der Verein aktiv ist.

## Teilaufgaben
- [ ] Bildmaterial für Hero-Bereich auswählen/erstellen (Logo oder Einsatzbild)
- [ ] Kurztext «Vision» verfassen (max. 3–4 Sätze)
- [ ] Kurztext «Was sind Naturschutzspürhunde?» verfassen mit Verlinkung zur Detailseite
- [ ] Teaser-Bereich «Aktuelle Projekte» mit Verlinkung zu Projektseiten erstellen
- [ ] Crowdfunding/Unterstützen-Sektion mit Call-to-Action-Button einbauen
- [ ] «Aktuelles»-Bereich mit den letzten News-Einträgen einbauen
- [ ] Navigation/Header/Footer als wiederverwendbares Template umsetzen
- [ ] Responsives Layout für Mobile/Tablet/Desktop umsetzen
- [ ] Inhalte dieser Seite aus der Datenbank statt statisch aus dem Code lesen (Anbindung an Admin-Backend, siehe Epic 9)

## Abnahmekriterien
- [ ] Die Startseite zeigt alle sechs Inhaltsblöcke (Bild, Vision, Was sind NSH, Projekte, Crowdfunding, Aktuelles) in dieser Reihenfolge.
- [ ] Jeder Teaser-Block verlinkt korrekt auf die zugehörige Unterseite.
- [ ] Die Seite lädt auf Mobilgeräten in unter 3 Sekunden.
- [ ] Die Startseite ist auf Bildschirmbreiten von 360px bis 1920px ohne horizontales Scrollen darstellbar.

## Out of Scope
- Detaillierte Inhalte der Unterseiten (Über uns, Projekte usw.) – werden in den jeweiligen Epics behandelt.
- Mehrsprachigkeit (nur Deutsch in diesem Epic).
- Content-Management-System-Anbindung – wird in [Epic 9: Admin-Backend für Inhaltspflege](#epic-9-admin-backend-für-inhaltspflege) behandelt. **Update:** Der technische Rahmen ist mit Epic 9 nicht mehr offen, sondern als DB-basiertes Admin-Backend entschieden (siehe Hinweis oben).

---

# Epic 2: Über uns

## Ziel
Interessierte, potenzielle Mitglieder und Partner erhalten einen transparenten Überblick über den Verein, seine Vision, den Vorstand und die Partnerorganisationen.

## Kontext / Hintergrund
Vereinstexte (Statuten) sind teilweise bereits vorhanden (Ordner «Statuten» im Projekt). Vorstands- und Partnerinformationen müssen noch zusammengetragen werden. Der Bereich schafft Vertrauen und Transparenz gegenüber Spenderinnen und Mitgliedern.

## User Stories
- Als Besucherin möchte ich erfahren, was der Verein ist und wofür er steht, damit ich Vertrauen in die Organisation aufbauen kann.
- Als Besucherin möchte ich Vision und Mission des Vereins nachlesen, damit ich die langfristigen Ziele verstehe.
- Als potenzielles Mitglied möchte ich die Vorstandsmitglieder mit Foto und Funktion sehen, damit ich weiss, wer hinter dem Verein steht.
- Als Partnerorganisation möchte ich die bestehenden Partner des Vereins sehen, damit ich den Kontext einer möglichen Zusammenarbeit einschätzen kann.

## Teilaufgaben
- [ ] Text «Der Verein» verfassen (Gründung, Zweck, Rechtsform gemäss Statuten)
- [ ] Text «Vision & Mission» verfassen
- [ ] Vorstandsmitglieder erfassen (Name, Funktion, Foto, Kurzbeschrieb)
- [ ] Partnerliste erfassen (Logo, Name, Link, Kurzbeschrieb)
- [ ] Statuten-Dokument prüfen und bei Bedarf zum Download verlinken
- [ ] Seite «Über uns» mit den vier Unterbereichen umsetzen
- [ ] Inhalte dieser Seite aus der Datenbank statt statisch aus dem Code lesen (Anbindung an Admin-Backend, siehe Epic 9)

## Abnahmekriterien
- [ ] Alle vier Unterbereiche (Verein, Vision & Mission, Vorstand, Partner) sind auf der Seite vorhanden und erreichbar.
- [ ] Jedes Vorstandsmitglied ist mit Name und Funktion aufgeführt.
- [ ] Die Statuten sind als Dokument abrufbar, falls der Vorstand dies wünscht.
- [ ] Texte enthalten zum Zeitpunkt des Abschlusses keine Platzhalter mehr.

## Out of Scope
- Login-Bereich/Intranet für Vorstandsmitglieder.
- Historische Vereinschronik/Zeitstrahl.

---

# Epic 3: Naturschutzspürhunde

## Ziel
Besuchende verstehen das Konzept «Naturschutzspürhund», die Arbeitsweise, mögliche Einsatzfelder und warum gerade Hunde für diese Aufgabe eingesetzt werden.

## Kontext / Hintergrund
Dies ist der fachliche Kernbereich der Vereinsarbeit und die Grundlage für das Verständnis der Projekte und Hundeprofile. Inhalte müssen fachlich korrekt und für Laien verständlich sein.

## User Stories
- Als Besucherin möchte ich eine verständliche Definition von «Naturschutzspürhund» lesen, damit ich das Grundkonzept versteh.
- Als Besucherin möchte ich wissen, wie Naturschutzspürhunde arbeiten (Trainingsmethode, Einsatzablauf), damit ich die fachliche Vorgehensweise nachvollziehen kann.
- Als potenzieller Auftraggeber (z.B. Naturschutzorganisation) möchte ich die Einsatzmöglichkeiten der Hunde sehen, damit ich einschätzen kann, ob ein Einsatz für mein Projekt sinnvoll ist.
- Als Besucherin möchte ich erfahren, warum gerade Hunde für Naturschutzarbeit eingesetzt werden, damit ich den Mehrwert gegenüber anderen Methoden versteh.

## Teilaufgaben
- [ ] Text «Was sind Naturschutzspürhunde?» verfassen (fachlich, aber laienverständlich)
- [ ] Text «Wie arbeiten sie?» verfassen, wenn möglich mit Bild-/Videomaterial vom Training
- [ ] Übersicht «Einsatzmöglichkeiten» erstellen (Liste/Kategorien mit Beispielen)
- [ ] Text «Warum Hunde?» mit Vorteilen gegenüber anderen Nachweismethoden verfassen
- [ ] Bildmaterial/Grafiken für die vier Unterbereiche zusammenstellen
- [ ] Inhalte dieser Seite aus der Datenbank statt statisch aus dem Code lesen (Anbindung an Admin-Backend, siehe Epic 9)

## Abnahmekriterien
- [ ] Alle vier Unterbereiche sind inhaltlich befüllt und für Laien ohne Erklärungslücken verständlich.
- [ ] Mindestens ein Bild oder eine Grafik pro Unterbereich ist eingebunden.
- [ ] Die Seite verlinkt auf die Projekte-Seite, wo die Einsatzmöglichkeiten konkret gezeigt werden.

## Out of Scope
- Wissenschaftliche Studien/Publikationen im Detail (siehe Epic «Projekte» → Forschung).
- Buchbare Einsatz-Anfrage-Formulare (nur Verweis auf Kontaktseite).

**Status:** Technisches Grundgerüst abgeschlossen (2026-08-09): Admin-Editor (`admin/naturschutzspuerhunde.php`), öffentliche Seite (`naturschutzspuerhunde.php`), Navigation und Startseiten-Verlinkung sind live. Entspricht GitHub Epic [#50](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/50) (geschlossen), umgesetzt in Issues [#118](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/118)–[#121](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/121), PR [#122](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/122). Die vier Fachtexte sind noch Platzhalter («Text folgt in Kürze.») und müssen vom Vorstand über den Admin-Editor nachgetragen werden.

---

# Epic 4: Projekte

## Ziel
Besuchende erhalten einen Überblick über die konkreten Projekte des Vereins (Igel, invasive Arten, weitere Projekte) sowie über die begleitende Forschung.

## Kontext / Hintergrund
Projekte sind ein zentrales Element der Vereinsarbeit und für die Spendenwerbung relevant, da sie konkrete Wirkung zeigen. Anzahl und Inhalt der «weiteren Projekte» ist noch offen und wird laufend wachsen.

## User Stories
- Als Besucherin möchte ich das Igel-Projekt mit Zielsetzung und Vorgehen sehen, damit ich verstehe, wie die Hunde hier eingesetzt werden.
- Als Besucherin möchte ich das Projekt zu invasiven Arten sehen, damit ich den Nutzen für den Naturschutz erkenne.
- Als Besucherin möchte ich weitere/künftige Projekte sehen, damit ich einen Eindruck von der Bandbreite der Vereinsarbeit erhalte.
- Als fachlich interessierte Besucherin möchte ich Zugang zu Forschungsergebnissen/-berichten erhalten, damit ich die wissenschaftliche Fundierung der Arbeit nachvollziehen kann.

## Teilaufgaben
- [ ] Projektseite «Igel» erstellen (Ziel, Vorgehen, Status, Bilder)
- [ ] Projektseite «Invasive Arten» erstellen (Ziel, Vorgehen, Status, Bilder)
- [ ] Übersichtsseite «Weitere Projekte» mit Kurzbeschrieben erstellen
- [ ] Bereich «Forschung» mit Publikationen/Berichten erstellen (Liste mit Downloads oder Links)
- [ ] Projektübersichtsseite mit Verlinkung zu allen Unterbereichen erstellen
- [ ] Inhalte dieser Seite aus der Datenbank statt statisch aus dem Code lesen (Anbindung an Admin-Backend, siehe Epic 9)

## Abnahmekriterien
- [ ] Jedes der genannten Projekte (Igel, Invasive Arten) hat eine eigene Unterseite mit mindestens Ziel, Vorgehen und einem Bild.
- [ ] Die Übersichtsseite «Projekte» verlinkt korrekt auf alle vier Unterbereiche.
- [ ] Der Forschungsbereich enthält mindestens einen abrufbaren Beitrag oder einen klaren Hinweis «in Vorbereitung», falls noch kein Material vorliegt.

## Out of Scope
- Interaktive Projektkarten mit GPS-Daten – bei Bedarf als eigenes Epic später.
- Spenden-Zuweisung pro Einzelprojekt (siehe Epic «Unterstützen»).

**Status:** Abgeschlossen. Entspricht GitHub Epic [#51](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/51) (geschlossen).

---

# Epic 5: Unsere Hunde

## Ziel
Besuchende lernen die aktiven Naturschutzspürhunde des Vereins sowie die Wegbereiterin Malou persönlich kennen und bauen eine emotionale Verbindung zum Verein auf.

## Kontext / Hintergrund
Hundeprofile sind ein zentrales, emotional wirksames Element für die Aussenwirkung und Spendenbereitschaft. **Annahme:** Malou ist nicht mehr aktiv im Einsatz und wird historisch/ehrend als Wegbereiterin dargestellt statt als aktiver Diensthund. Die Datenhaltung (Tabelle `dogs` inkl. `is_active`-Flag) und der Admin-CRUD bestanden bereits vor der Umsetzung dieses Epics; es fehlte nur die öffentliche Seite.

## User Stories
- Als Besucherin möchte ich das Profil von Oro sehen (Bild, Charakter, Einsatzgebiet), damit ich eine persönliche Verbindung zum Hund aufbauen kann.
- Als Besucherin möchte ich das Profil von Moana sehen, damit ich sie als Teil des Teams kennenlerne.
- Als Besucherin möchte ich das Profil von Pepino sehen, damit ich ihn als Teil des Teams kennenlerne.
- Als Besucherin möchte ich die Geschichte von Malou als Wegbereiterin lesen, damit ich versteh, wie der Verein entstanden ist.

## Teilaufgaben
- [x] Hundeprofile (Oro, Moana, Pepino, Malou) im Admin-Backend erfasst (Bild, Steckbrief, Einsatzgebiet, Charakter)
- [x] Malous Profil als Wegbereiterin gekennzeichnet (`is_active = 0`)
- [x] Übersichtsseite «Unsere Hunde» (`unsere-hunde.php`) mit Verlinkung zu allen Profilen erstellt, aktive Hunde und Wegbereiterinnen optisch getrennt
- [x] Navigation im Header von Platzhalter auf die neue Seite umgestellt
- [x] Teaser-Verlinkung auf der Startseite ergänzt
- [x] Inhalte werden aus der Datenbank gelesen (kein statischer Code mehr nötig)

## Abnahmekriterien
- [x] Alle Profile (Oro, Moana, Pepino, Malou) sind über die Übersichtsseite erreichbar.
- [x] Jedes Profil enthält mindestens ein Bild und einen Steckbrief-Text.
- [x] Malous Profil ist optisch/inhaltlich klar als «Wegbereiterin» (nicht aktiver Einsatzhund) gekennzeichnet.

## Out of Scope
- Trainingsvideos oder Live-Tracking der Hunde-Einsätze.
- Separate Detail-URL pro Hund (Übersichtsseite genügt für den Start; bei Bedarf eigenes Epic).
- Weitere Hunde, die künftig zum Team stossen (werden bei Bedarf als neue Teilaufgabe ergänzt).

**Status:** Abgeschlossen (2026-08-09). Entspricht GitHub Epic [#52](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/52) (geschlossen), umgesetzt in Issues [#95](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/95), [#96](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/96), [#97](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/97), PR [#98](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/98). Live getestet unter `/unsere-hunde.php`.

---

# Epic 6: Ausbildung

## Ziel
Besuchende, insbesondere Fachpublikum, Partnerorganisationen und potenzielle Hundeführerinnen, verstehen die Qualitätsstandards, internationale Zusammenarbeit, Assessments und Weiterbildungsangebote der Vereins-Ausbildung.

## Kontext / Hintergrund
Dieser Bereich richtet sich stärker an ein Fachpublikum und ist wichtig für die Glaubwürdigkeit und Professionalität des Vereins gegenüber Auftraggebern und Partnerorganisationen.

## User Stories
- Als Auftraggeberin (Naturschutzorganisation) möchte ich die Qualitätsstandards der Ausbildung nachlesen, damit ich die Professionalität der Hundeteams einschätzen kann.
- Als Fachperson möchte ich Informationen zur internationalen Zusammenarbeit sehen, damit ich den fachlichen Austausch und Standard des Vereins einordnen kann.
- Als interessierte Hundeführerin möchte ich wissen, wie Assessments ablaufen, damit ich weiss, welche Anforderungen ich erfüllen muss.
- Als aktive Hundeführerin möchte ich das Weiterbildungsangebot sehen, damit ich mich laufend weiterentwickeln kann.

## Teilaufgaben
- [ ] Text «Qualitätsstandards» verfassen (Kriterien, Zertifizierung falls vorhanden)
- [ ] Text «Internationale Zusammenarbeit» verfassen (Partnerorganisationen, Netzwerke im Ausland)
- [ ] Text «Assessments» verfassen (Ablauf, Kriterien, Häufigkeit)
- [ ] Text «Weiterbildung» verfassen (Angebot, Zielgruppe, Anmeldeprozess)
- [ ] Übersichtsseite «Ausbildung» mit Verlinkung zu allen vier Unterbereichen erstellen
- [ ] Inhalte dieser Seite aus der Datenbank statt statisch aus dem Code lesen (Anbindung an Admin-Backend, siehe Epic 9)

## Abnahmekriterien
- [ ] Alle vier Unterbereiche sind auf der Ausbildungsseite vorhanden und inhaltlich befüllt.
- [ ] Der Assessment-Ablauf ist als nachvollziehbare Schritt-für-Schritt-Beschreibung dargestellt.
- [ ] Falls eine Anmeldung zu Weiterbildungen vorgesehen ist, ist mindestens eine Kontaktmöglichkeit (Link/E-Mail) angegeben.

## Out of Scope
- Online-Anmeldeformular/Buchungssystem für Weiterbildungen (nur Verweis auf Kontakt).
- Interner Mitgliederbereich mit geschütztem Ausbildungsmaterial.

**Status:** Technisches Grundgerüst abgeschlossen (2026-08-09): Admin-Editor (`admin/ausbildung.php`), öffentliche Seite (`ausbildung.php`), Navigation und Startseiten-Teaser sind live. Entspricht GitHub Epic [#53](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/53) (geschlossen und erfolgreich getestet), umgesetzt in Issues [#108](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/108)–[#111](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/111), PR [#112](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/112). Die vier Fachtexte sind noch Platzhalter und müssen vom Vorstand über den Admin-Editor nachgetragen werden.

---

# Epic 7: Unterstützen

## Ziel
Besuchende finden alle Möglichkeiten, den Verein finanziell oder ideell zu unterstützen (Spenden, Mitgliedschaft, Sponsoring, Crowdfunding), und können den für sie passenden Weg unkompliziert einschlagen.

## Kontext / Hintergrund
Dieser Bereich ist zentral für die Finanzierung des Vereins. **Annahme:** Die Zahlungsabwicklung erfolgt zunächst über externe Anbieter (z.B. Twint, Banküberweisung, bestehende Crowdfunding-Plattform) und nicht über eine eigene Zahlungsintegration auf der Webseite. Diese Annahme ist mit dem Vorstand zu bestätigen.

## User Stories
- Als Spenderin möchte ich einfach und sicher spenden können, damit ich den Verein unkompliziert unterstützen kann.
- Als Interessentin möchte ich Mitglied werden können, damit ich den Verein langfristig und aktiv unterstütze.
- Als Unternehmen möchte ich Informationen zum Sponsoring finden, damit ich eine Partnerschaft prüfen kann.
- Als Besucherin möchte ich zur aktuellen Crowdfunding-Kampagne gelangen, damit ich gezielt ein laufendes Projekt unterstützen kann.

## Teilaufgaben
- [ ] Text und Ablauf «Spenden» erstellen (Zahlungsmöglichkeiten, Verwendungszweck)
- [ ] Text und Prozess «Mitglied werden» erstellen (Mitgliedschaftsarten, Kosten, Anmeldeweg)
- [ ] Text «Sponsoring» mit Sponsoring-Paketen/Kontaktmöglichkeit erstellen
- [ ] Verlinkung/Einbettung der Crowdfunding-Kampagne erstellen
- [ ] Zahlungsabwicklung klären und umsetzen (Annahme: Verlinkung auf externen Anbieter) — mit Vorstand abstimmen
- [ ] Inhalte dieser Seite aus der Datenbank statt statisch aus dem Code lesen (Anbindung an Admin-Backend, siehe Epic 9)

## Abnahmekriterien
- [ ] Alle vier Unterstützungswege (Spenden, Mitglied werden, Sponsoring, Crowdfunding) sind über die Seite erreichbar.
- [ ] Der Spendenprozess ist ab der Unterstützen-Seite in maximal zwei Klicks erreichbar.
- [ ] Für Mitgliedschaft und Sponsoring ist jeweils ein Kontaktweg (E-Mail/Formular) hinterlegt.

## Out of Scope
- Eigene Zahlungsabwicklung/Payment-Integration auf der Webseite (rechtliche/technische Klärung nötig, bei Bedarf separates Epic).
- Automatisierte Mitgliederverwaltung/Datenbank-Anbindung.

**Status:** Technisches Grundgerüst abgeschlossen (2026-08-09): Admin-Editor (`admin/unterstuetzen.php`), öffentliche Seite (`unterstuetzen.php`), Navigation und Startseiten-CTA-Verweis sind live. Spenden und Crowdfunding verlinken direkt auf die Lokalhelden-Kampagne (https://www.lokalhelden.ch/naturschutzhunde). Entspricht GitHub Epic [#54](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/54) (geschlossen), umgesetzt in Issues [#113](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/113)–[#116](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/116), PR [#117](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/117). Texte zu Mitgliedschaft/Sponsoring sind noch Platzhalter und müssen vom Vorstand über den Admin-Editor nachgetragen werden.

---

# Epic 8: News & Kontakt

## Ziel
Besuchende bleiben über aktuelle Einsätze, Veranstaltungen und Medienberichte informiert und finden eine klare Möglichkeit, mit dem Verein Kontakt aufzunehmen.

## Kontext / Hintergrund
Der News-Bereich hält die Seite lebendig und aktuell, die Kontaktseite ist zentraler Anlaufpunkt für alle Anfragen (Mitgliedschaft, Sponsoring, Presse, Einsatzanfragen). Die Datenhaltung für News (Tabelle `news` inkl. Kategorien, Admin-CRUD) bestand bereits vor der Umsetzung dieses Epics; es fehlten die öffentlichen Seiten sowie die Kontaktseite komplett.

## User Stories
- Als Besucherin möchte ich aktuelle Einsätze der Hundeteams lesen, damit ich die praktische Vereinsarbeit mitverfolgen kann.
- Als Besucherin möchte ich anstehende Veranstaltungen sehen, damit ich allenfalls daran teilnehmen kann.
- Als Journalistin möchte ich bisherige Medienberichte über den Verein finden, damit ich mir ein Bild für die eigene Berichterstattung machen kann.
- Als Interessentin möchte ich den Verein über ein Kontaktformular oder direkte Angaben erreichen, damit meine Anfrage die richtige Ansprechperson erreicht.

## Teilaufgaben
- [x] News-Übersichtsseite (`news.php`) erstellt, zeigt alle Kategorien («Einsätze», «Veranstaltungen», «Medienberichte»), neueste zuerst
- [x] News-Detailseite (`news-beitrag.php?id=`) mit eigener URL erstellt
- [x] Startseiten-Teaser verlinkt auf die News-Detailseite
- [x] Kontaktseite (`kontakt.php`) mit Kontaktformular (Name, E-Mail, Anliegen) erstellt
- [x] Formularversand serverseitig umgesetzt: Validierung (Pflichtfelder, E-Mail-Format), Honeypot-Feld als Spam-Schutz, Versand per PHP `mail()`
- [x] E-Mail-Adresse auf der Kontaktseite per JS verschleiert (Schutz vor einfachem Scraping durch Spam-Bots)
- [x] Navigation im Header von Platzhalter «News & Kontakt» auf zwei eigene Nav-Punkte «News» und «Kontakt» umgestellt
- [x] Design des Kontaktformulars an Vorstands-Mockup angepasst (Name/E-Mail nebeneinander, freies Anliegen-Feld statt Kategorie-Dropdown)

**Entscheid:** Auf eine differenzierte Zuständigkeit/Ansprechperson pro Anfrageart (Presse, Sponsoring, Mitgliedschaft) wurde bewusst verzichtet — alle Anfragen laufen zentral über `info@naturschutzspürhunde.ch`. Kann bei Bedarf später ergänzt werden.

## Abnahmekriterien
- [x] Die News-Seite zeigt Beiträge in den drei Kategorien, neueste zuerst.
- [x] Jeder News-Beitrag hat ein Datum und ist einzeln aufrufbar (eigene URL).
- [x] Die Kontaktseite enthält eine funktionierende Kontaktmöglichkeit (Formular und E-Mail-Adresse).
- [x] Anfragen über das Kontaktformular werden serverseitig zugestellt (Testversand am 2026-08-09 durchgeführt, `mail()` lieferte Erfolg; tatsächlicher Eingang im Postfach `info@naturschutzspürhunde.ch` noch durch den Vorstand zu bestätigen).

## Out of Scope
- Automatisierter Newsletter-Versand.
- Kommentarfunktion unter News-Beiträgen.
- Mehrsprachiger Kontakt-Support.
- Differenzierte Ansprechpersonen pro Anfrageart (siehe Entscheid oben).

**Status:** Abgeschlossen (2026-08-09). Entspricht GitHub Epic [#55](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/55) (geschlossen), umgesetzt in Issues [#99](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/99)–[#103](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/103), PRs [#104](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/104), [#105](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/105), [#106](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/106), [#107](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/107). Design-Mockup dokumentiert als Kommentar am Epic sowie unter `docs/mockups/kontaktformular-mockup.png` (Branch `docs-kontakt-mockup`).

---

# Epic 9: Admin-Backend für Inhaltspflege

## Ziel
Der Vorstand kann Texte, Bilder, News-Beiträge, Hundeprofile und Projekte auf der gesamten Webseite selbständig über ein geschütztes Backend bearbeiten, ohne dafür Code ändern oder eine Entwicklerin beiziehen zu müssen.

## Kontext / Hintergrund
Die Epics 1–8 gehen bisher von statischen PHP-Seiten aus, bei denen Inhalte im Code hinterlegt sind. Für die laufende Pflege (v.a. News, Hundeprofile, Projekte, aber grundsätzlich alle Texte und Bilder) soll ein Admin-Backend entstehen. Die Datenbank-Zugangsdaten (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`) sind gemäss CLAUDE.md bereits als GitHub Secrets vorbereitet und werden zur Deploy-Zeit in eine nicht committete `config.json` geschrieben – die technische Grundlage für ein DB-basiertes Backend existiert also schon. **Entscheidung dieses Epics:** Inhalte werden künftig in einer Datenbank gespeichert und von den öffentlichen Seiten dynamisch ausgelesen, statt fest im PHP-Code zu stehen. Das hebt die bisherige Annahme «einfache PHP-Seiten ohne CMS» aus Epic 1 auf; Epics 1–8 müssen entsprechend um eine Teilaufgabe «Inhalte aus Datenbank statt statisch lesen» ergänzt werden, sobald dieses Epic umgesetzt ist. **Annahme (bestätigt):** Es gibt vorerst nur einen einzelnen Admin-Zugang, kein Rollenkonzept mit unterschiedlichen Berechtigungsstufen.

## User Stories
- Als Vorstandsmitglied (Admin) möchte ich mich über einen geschützten Login-Bereich anmelden, damit nur berechtigte Personen Inhalte ändern können.
- Als Admin möchte ich Texte auf allen acht Inhaltsbereichen der Webseite bearbeiten, damit ich Inhalte aktuell halten kann, ohne Code anzupassen.
- Als Admin möchte ich Bilder hochladen, ersetzen oder löschen, damit ich visuelles Material selbständig aktualisieren kann.
- Als Admin möchte ich News-Beiträge erfassen, bearbeiten und löschen, damit die News-Seite laufend aktuell bleibt.
- Als Admin möchte ich Hundeprofile (bestehende und künftige) erfassen und bearbeiten, damit neue Teammitglieder ohne Entwicklerin ergänzt werden können.
- Als Admin möchte ich Projekte erfassen und bearbeiten, damit neue oder abgeschlossene Projekte selbständig nachgeführt werden können.
- Als Admin möchte ich Änderungen vor der Veröffentlichung in einer Vorschau prüfen, damit keine fehlerhaften Inhalte live geschaltet werden.

## Teilaufgaben
- [x] Datenbankschema für Inhalte definiert (Seiten/Textblöcke, News, Hundeprofile, Projekte, Bilder)
- [x] Login-Bereich mit sicherer Authentifizierung umgesetzt
- [x] Admin-Dashboard mit Navigation zu den bearbeitbaren Bereichen erstellt
- [x] Editier-Formulare für Texte pro Seite/Bereich erstellt (inkl. WYSIWYG-Editor)
- [x] Bild-Upload inkl. Validierung und Speicherung/Verlinkung umgesetzt (inkl. Bild-Löschfunktion in allen Admin-Bereichen)
- [x] Verwaltung News-Beiträge umgesetzt
- [x] Verwaltung Hundeprofile umgesetzt
- [x] Verwaltung Projekte umgesetzt
- [x] Vorschau-Funktion für Änderungen vor Veröffentlichung (Issue [#11](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/11))
- [x] Bestehende Seiten (Epics 1–8) vollständig auf Datenbank umgestellt (Issue [#12](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/12), geprüft: alle acht Inhaltsbereiche lesen aus der Datenbank)
- [x] Backup-/Wiederherstellungsprozess für die Datenbank dokumentiert (Issue [#13](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/13))

## Abnahmekriterien
- [x] Der Admin-Bereich ist nur nach erfolgreichem Login erreichbar.
- [x] Für jeden der acht Inhaltsbereiche mindestens ein Text änderbar (technisch für alle acht gegeben; bei Epics 3/6/7 sind die Felder vorhanden, aber inhaltlich noch mit Platzhaltern befüllt).
- [x] Bild-Upload funktioniert und wird korrekt angezeigt.
- [x] News-Beiträge, Hundeprofile und Projekte vollständig verwaltbar.
- [x] Passwörter werden nicht im Klartext gespeichert.
- [x] Zugangsdaten zur Datenbank sind zu keinem Zeitpunkt im Repository sichtbar.

## Out of Scope
- Mehrere Admin-Rollen mit unterschiedlichen Rechten.
- Mehrsprachige Inhaltsverwaltung.
- Versionsverlauf/Undo-Funktion für Inhaltsänderungen.
- Automatisierte Freigabe-Workflows (z.B. Vier-Augen-Prinzip vor Veröffentlichung).

**Status:** Abgeschlossen (2026-08-09). Entspricht GitHub Epic [#4](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/4) (geschlossen), alle Teilaufgaben [#5](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/5)–[#13](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/13) abgeschlossen.

---

# Epic 10: Block-Editor für Admin-Textfelder (Gutenberg-Prinzip)

## Ziel
Der Vorstand kann Inhalte im Admin-Backend als einzelne, frei anordenbare Blöcke (Text, Überschrift, Bild, Zitat, Liste) erstellen, per Pfeil-Buttons verschieben und löschen — statt einen einzigen zusammenhängenden Fliesstext zu bearbeiten.

## Kontext / Hintergrund
Über den ursprünglichen Fahrplan (Epics 1–9) hinausgehende Erweiterung. Ersetzt die einfache WYSIWYG-Toolbar aus Epic 9 durch ein Block-Datenmodell nach dem Vorbild von WordPress Gutenberg: Inhalt wird als geordnete Liste von Blöcken (JSON) gespeichert statt als einzelner HTML-String. Bewusst weiterhin selbstgebaut in Vanilla JS/PHP ohne Frontend-Framework.

## Teilaufgaben
- [x] Block-Datenmodell, Server-Renderer und Sanitisierung (`includes/blocks.php`)
- [x] Editor-UI mit Einfüge-Menü, Verschieben und Löschen (`assets/js/block-editor.js`)
- [x] Bild-Block mit Upload (`admin/upload-block-image.php`)
- [x] Altbestand-Kompatibilität: Legacy-HTML-Inhalte werden automatisch erkannt und als ein Text-Block geladen, kein Datenverlust bei der Umstellung
- [x] CSS für alle Blocktypen im Corporate Design
- [x] Rollout auf alle 11 Admin-Textfelder (Startseite, Über uns, Naturschutzspürhunde, Projekte, Unsere Hunde, Ausbildung, Unterstützen, News)
- [x] Manuell getestet inkl. Sicherheitstest

## Abnahmekriterien
- [x] Blöcke lassen sich in jedem betroffenen Textfeld hinzufügen, verschieben, löschen.
- [x] Bestehende Alt-Inhalte werden ohne Datenverlust weiterhin korrekt angezeigt.
- [x] Jeder Blocktyp wird serverseitig sanitisiert.
- [x] Kein Frontend-Framework eingeführt.

## Out of Scope
- Drag-and-Drop-Verschieben per Maus (MVP nutzt Auf/Ab-Buttons).
- Spalten-Layouts, Embeds, wiederverwendbare Blöcke/Patterns.

## Vorfall während des Rollouts (wichtig für künftige Änderungen an der Speicherlogik)
Beim Rollout (Issue [#129](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/129)) wurde die Editor-Oberfläche auf Block-JSON umgestellt, aber die serverseitige Speicherlogik in allen 11 Admin-Formularen zunächst **nicht** angepasst: Sie rief weiterhin `sanitizeHtml()` direkt auf dem POST-Wert auf, der jetzt aber eine Block-JSON-Zeichenkette ist. Dadurch wurden beim Speichern Inhalte verstümmelt/geleert statt korrekt gespeichert. Betroffen waren die vier Textfelder der Startseite (`vision`, `nsh_text`, `ausbildung_teaser_text`, `cta_text`), die einmalig während des Testens gespeichert wurden, bevor der Fehler auffiel. Alle anderen Bereiche waren nicht betroffen, da sie seit dem Rollout nicht neu gespeichert worden waren.

**Fix:** Neue Funktion `sanitizeBlockFieldInput()` in `includes/blocks.php` erkennt Block-JSON korrekt, validiert es pro Blocktyp und speichert es wieder als JSON (Hotfix-PR [#143](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/143)). Die verlorenen Startseiten-Texte wurden vom Vorstand über den (jetzt korrigierten) Editor neu erfasst.

**Lehre für künftige Datenformat-Änderungen am Editor:** Bei jeder Änderung am Speicherformat client-seitiger Editor-Komponenten immer explizit prüfen, ob die serverseitige POST-Verarbeitung in **allen** Formularen, die die Komponente nutzen, mitgezogen wurde — nicht nur die Editor-Oberfläche selbst.

**Status:** Abgeschlossen (2026-08-09). Entspricht GitHub Epic [#123](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/123) (geschlossen), umgesetzt in Issues [#124](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/124)–[#130](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/130), PRs [#131](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/131)–[#136](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/136), Hotfix PR [#143](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/143).

---

# Epic 11: Startseite vollständig im Admin-Backend pflegbar machen

## Ziel
Der Vorstand kann alle Textinhalte der Startseite — einschliesslich des vormals fest im Code hinterlegten Ausbildung-Teasertexts — sowie die Anzahl der angezeigten Projekt-, Hunde- und News-Karten über das Admin-Backend pflegen.

## Kontext / Hintergrund
Über den ursprünglichen Fahrplan hinausgehende Erweiterung. Schliesst zwei verbliebene Lücken in Epic 1/9: den hartcodierten Ausbildung-Teaser-Absatz und die fest im Code verankerte Anzahl angezeigter Teaser-Karten. Bewusste Annahme: Abschnitts-Überschriften und die Reihenfolge der Abschnitte bleiben fest im Code.

## Teilaufgaben
- [x] Ausbildung-Teasertext editierbar gemacht (Issue [#138](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/138))
- [x] Anzahl Projekt-/Hunde-/News-Karten konfigurierbar gemacht, inkl. Validierung 1–12 (Issue [#139](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/139))
- [x] Hartcodierte Default-Texte für Vision/NSH aus `index.php` entfernt, da echte Inhalte bereits in der Datenbank gepflegt sind

## Abnahmekriterien
- [x] Ausbildung-Teasertext lässt sich im Admin-Backend bearbeiten.
- [x] Anzahl Projekt-/Hunde-/News-Karten lässt sich einzeln einstellen, ungültige Eingaben werden auf Standard 3 zurückgesetzt.
- [x] Ohne gespeicherte Einstellung gelten die bisherigen Standardwerte weiter.

## Out of Scope
- Abschnitts-Überschriften editierbar machen.
- Reihenfolge der Abschnitte ändern.

**Status:** Abgeschlossen (2026-08-09). Entspricht GitHub Epic [#137](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/137) (geschlossen), umgesetzt in Issues [#138](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/138), [#139](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/issues/139), PRs [#140](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/140), [#141](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/141), [#142](https://github.com/graunicole12-blip/naturschutzspuerhunde-website/pull/142).
