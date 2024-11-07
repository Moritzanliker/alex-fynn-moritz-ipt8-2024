# TaskMaster

## Projektübersicht
**TaskMaster** ist eine webbasierte Task-Management-Plattform, die darauf abzielt, Teams die effiziente Zusammenarbeit an Projekten zu ermöglichen. Mit TaskMaster können Nutzer Projekte erstellen und Aufgaben (To-Dos) innerhalb eines Projekts in einem geteilten Arbeitsbereich, einem sogenannten "Board", organisieren. Teams können Aufgaben gemeinsam bearbeiten, ihren Fortschritt nachverfolgen und auf diese Weise sicherstellen, dass alle Beteiligten stets auf dem aktuellen Stand sind.

## Hauptfunktionen
- **Benutzerauthentifizierung**: Nutzer können sich registrieren und anmelden.
- **Projekterstellung**: Projekte können erstellt und andere Nutzer zur Zusammenarbeit eingeladen werden.
- **Boards**: Jedes Projekt verfügt über ein eigenes Board, auf dem alle zugehörigen Aufgaben gelistet sind.
- **Aufgabenverwaltung**: Nutzer können Aufgaben erstellen, löschen und anderen Nutzern zuweisen.
- **Team-Kollaboration**: Mehrere Nutzer können an einem Projekt arbeiten, den Fortschritt der Aufgaben verfolgen und miteinander kommunizieren.
- **Fortschrittsverfolgung**: Visuelle Indikatoren zeigen den Abschlussgrad von Aufgaben an, um Teams zu unterstützen, den Überblick zu behalten.
- **Organisation**: Projekte und Aufgaben werden nach Deadline sortiert, also ist immer kalr was am wichtigsten ist.  
- **Dashboard**: Ein zentrales Dashboard bietet eine Übersicht über alle aktiven Projekte und Aufgaben.

## Installation und Einrichtung

1. **Voraussetzungen**:
   - XAMPP
   - PhpMyAdmin
   - Docker
     
2. **Repository klonen**:
   ```bash
   git clone https://github.com/Moritzanliker/TaskMaster.git
- in htdocs im ordner xampp (C:\xampp\htdocs) !


3. **XAMPP**
   - Apache server starten
     
4. **Docker**
    ```bash
   docker-compose up -d

5. **Datenbank**
   - phpMyAdmin starten
   - einloggen (root / 123)
   - datenbank importieren

6. **Webseite**
   -   http://localhost/alex-fynn-moritz-ipt8-2024/TaskMaster/pages/index.html
   - Username: user1
   - Password: 1
    

## Funktionsweise
- **Benutzeranmeldung**: Beim Starten werden Benutzer zur Anmeldeseite weitergeleitet. Nach erfolgreicher Anmeldung sehen sie ihr Dashboard mit einer Übersicht über alle aktiven Projekte und Aufgaben.
- **Dashboard**: Das Dashboard zeigt alle laufenden Projekte und Aufgaben. Von hier aus können Nutzer zu ihren Projekten und Boards navigieren. Die Projekte und Aufgaben werden sortiert nach Deadline. 
- **Projektverwaltung**: In der Projektansicht können Nutzer neue Projekte erstellen und Teammitglieder hinzufügen. Jedes Projekt enthält ein Board, das alle Aufgaben anzeigt. 
- **Aufgabenverwaltung**: Die Aufgaben in einem Projekt kann man selber von einer Spalte zu der anderen verschieben. Z.B. von "In Progress" zu "Completed", wenn die Task fertig ist.

### Autoren:
Alex Belik - Backend, Datenbank 
Fynn Piekarek - Funktionen
Moritz Anliker - Frontend, CI


