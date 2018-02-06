# OXID-Cronjob-Manager
Fügt dem OXID-Backend einen Cronjob-Manager ein. 

## Installation
```bash
composer require florianpalme/oxid-cronjobmanager
```

## Cronjob einrichten
Bevor Cronjobs mittels des OXID Cronjob Managers verwendet werden können, muss ein normaler Crontab eingerichtet werden. Dabei ist darauf zu achten, dass der Aufruf jeder Minute durchgeführt wird.

### Linux Crontab
Kann der Linux Crontab auf dem Server bearbeitet werden (`crontab -e`), kann folgende Zeile eingefügt werden.
 ```
 * * * * * php /pfad/zum/oxidshop/bin/cron.php
 ```
 
### Hoster
Ist es dir nicht möglich, den Crontab über die Linux-Konsole einzurichten, wende dich an deinen Hoster, welcher dir weiterhelfen kann. Dabei muss die Datei `bin/cron.php` im OXID-Root-Verzeichnis mittels php ausgeführt werden.

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

## Verwendung in Modulen
Als Modul-Entwickler ersparst du dir durch den Cronjob Manager das schreiben eigener Cronjob-Funktionen, wie Prüfungen, ob er nun ausgeführt werden darf oder nicht. 

### Überladen der Maintenance
Füge deinen Cronjob als Methode in der \OxidEsales\Eshop\Application\Model\Maintenance-Klasse hinzu.

### Fehler-Meldungen
Schlägt dein Cronjob aus irgendwelchen Gründen fehl, so kannst du eine Instanz von FlorianPalme\OXIDCronjobManager\Core\Exception\Exception werfen.
Diese wird abgefangen und die hinterlegte Message in den Log geschrieben.

### Anpassung metadata.php
Das `$aModule`-Array in der metadata.php wird um ein Array `cronjobs` wie folgt erweitert.
```php
$aModule = [
   /** Cronjobs */
   'cronjobs' => [
        'moduleid_cronjobid' => [
            'fnc' => 'doMyJob',
            'title' => [
                'de' => 'My Job',
            ],
            'crontab' => '* * * * *',
        ],
    ],
];
```
Für jeden im cronjobs-Array enthaltenen Cronjob muss eine eindeutige ID vergeben werden. Empfohlen wird MODULID_CRONJOBID.

### Parameter

#### fnc
Name der Methode für diesen Cronjob in der oxMaintenance-Klasse.

#### title
Titel des Cronjobs als String oder Array für mehrere Sprachen.

#### crontab
Empfohlene Crontab-Einstellung für diesen Cronjob.



# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2018-02-05
### Changed
- Upgrade auf OXID 6