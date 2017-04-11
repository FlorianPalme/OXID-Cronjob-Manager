# OXID-Cronjob-Manager
Fügt dem OXID-Backend einen Cronjob-Manager ein. 

## Installation
1. Downloade das Repository
2. Kopiere den Inhalt des copy_this/-Ordners in das Root-Verzeichnis deines Shops
3. Aktiviere das Modul im Backend des Shops

## Verwendung
Unter Service -> Cronjob Manager findest du alle von Modulen bereitgestellten Cronjobs.

### Cronjob-Liste
In der Liste oben findest du folgende Informationen:

| Spalte        | Beschreibung  |
| ------------- | ------------- |
| S             | Status des Cronjobs |
| Modul         | Name des Moduls, zu welchem der Cronjob gehört | 
| Cronjob-Name  | Name des Cronjobs, vergeben vom Modul | 
| Crontab       | Ausführungs-Intervall | 
| Letzte Ausführung | Datum und Status der letzten Ausführung des Cronjobs |

### Bearbeitung - Tab "Cronjob Manager"
Nach dem Klick auf einen Cronjob lassen sich folgende Informationen bearbeiten:

#### Status

| Wert          | Beschreibung  |
| ------------- | ------------- |
| Aktiv         | Cronjob ist Aktiv und wird ausgeführt |
| Pausiert      | Die Ausführung wurde pausiert |
| Abgebrochen   | Die Ausführung wurde automatisch pausiert, da das Modul nicht mehr Aktiv ist oder die Cronjob-Funktion nicht vorhanden ist |

#### Crontab
Beschreibt den Ausführungs-Intervall des Cronjobs im [Linux Crontab Format](https://www.stetic.com/developer/cronjob-linux-tutorial-und-crontab-syntax.html)

### Bearbeitung - Tab "Log"
Hier findest du eine kurze Übersicht über die Anzahl der Ausführung, die durchschnittliche Ausführungszeit und die Anzahl der Fehlgeschlagenen Cronjobs.

In der ausgegebenen Liste werden Status, Start- & Endzeit, Ausführungszeit und ggf. eine Fehlermeldung ausgegeben.
