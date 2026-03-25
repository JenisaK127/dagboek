# 🗂️ STAP-VOOR-STAP INSTALLATIE HANDLEIDING

## STAP 1: XAMPP Starten
1. Start XAMPP Control Panel
2. Zorg dat "Apache" en "MySQL" groen zijn (Running status)

## STAP 2: Database Aanmaken
1. Open je browser en ga naar: **http://localhost/phpmyadmin**
2. Je ziet het phpMyAdmin dashboard
3. Links zie je "New" - klik erop
4. Vul bij "Database name" in: **dagboek**
5. Selecteer "utf8mb4_unicode_ci" als Collation
6. Klik blauwe "Create" knop

## STAP 3: Tabellen Aanmaken
1. In phpMyAdmin klik je op de zojuist aangemaakte "dagboek" database
2. Ga naar het "Import" tabblad (bovenin)
3. Klik "Choose File" en selecteer: `database.sql`
4. Klik blauwe "Import" knop onderaan

Je ziet dan 2 tabellen:
- `users` (voor gebruikersaccounts)
- `diary_entries` (voor dagboekentries)

## STAP 4: Applicatie Starten
In je browser ga je naar één van deze URLs:
- http://localhost/Persoonlijk%20werk/dagboek/
- of http://localhost/Persoonlijk%20werk/dagboek/index.php

Je ziet het inlogscherm! 🎉

## STAP 5: Account Aanmaken
1. Klik op "Account aanmaken" link
2. Vul in:
   - **Volledige naam**: Jouw naam (dit verschijnt op de kaft!)
   - **Gebruikersnaam**: Een unieke naam
   - **Email**: Jouw email
   - **Wachtwoord**: Minimaal 6 karakters
   - **Wachtwoord bevestigen**: Hetzelfde wachtwoord
3. Klik "Account Aanmaken"

## STAP 6: Inloggen
1. Je wordt teruggezonden naar inlogscherm
2. Vul je **gebruikersnaam** en **wachtwoord** in
3. Klik "Inloggen"

## STAP 7: Kaftpagina
1. Je ziet nu de kaftpagina van je dagboek
2. Je naam staat bovenop (groot geschreven!)
3. Klik "OPEN →" knop om naar je dashboard te gaan

## STAP 8: Dashboard
Je ziet nu:
- 🔍 **Zoekbalk** bovenin
- ➕ **Knop voor nieuwe entry**
- Je dagboekentries (5 per pagina)

## STAP 9: Eerste Entry Toevoegen
1. Klik "+ Nieuwe dagboekentry"
2. Vul in:
   - **Datum**: Kies een datum
   - **Titel**: Titel van je entry
   - **Inhoud**: Jouw gedachten/gevoelens
3. Klik "💾 Opslaan"

## STAP 10: Navigeren
- **Vorige/Volgende**: Navigate door je entries
- **Zoeken**: Zoek op woord in titel of inhoud
- **Bewerken**: Klik ✏️ op een entry
- **Verwijderen**: Klik 🗑️ op een entry
- **Terug naar kaft**: Klik "← Terug naar kaft"
- **Uitloggen**: Klik "Uitloggen" knop op kaft

---

## 🐛 Veelgestelde Problemen

### ❌ "Kan pagina niet bereiken"
**Oplossing:**
- Check of Apache en MySQL beide draaien in XAMPP
- Probeer exact deze URL: http://localhost/Persoonlijk%20werk/dagboek/index.php

### ❌ "Database connection error"
**Oplossing:**
- MySQL is niet gestart in XAMPP
- Of je hebt niet het juiste wachtwoord ingevoerd in config.php (standaard is leeg)

### ❌ "Table diary_entries doesn't exist"
**Oplossing:**
- Je hebt database.sql niet geïmporteerd
- Ga naar phpMyAdmin → dagboek database → Import tab
- Kies database.sql file en klik Import

### ❌ "Wachtwoord klopt niet na registratie"
**Oplossing:**
- Zorg dat beide wachtwoordvelden identiek zijn
- Probeer opnieuw

### ❌ "Kan entry niet opslaan voor deze datum"
**Oplossing:**
- Je hebt al een entry voor die datum
- Per dag kun je maar 1 entry hebben

---

## 📂 Bestandenstructuur

Als alles goed is geïnstalleerd zie je deze mappenstructuur:

```
c:\xampp\htdocs\Persoonlijk werk\dagboek\
├── config.php              ← Database instellingen
├── database.sql            ← SQL code voor tabellen
├── index.php               ← Inlogpagina
├── register.php            ← Registratie
├── cover.php               ← Kaftpagina
├── dashboard.php           ← Overzicht entries
├── entry.php               ← Toevoegen/bewerken
├── delete_entry.php        ← Verwijderen
├── logout.php              ← Uitloggen
├── README.md               ← Documentatie
└── INSTALLATION.md         ← Dit bestand
```

---

Enjoy je dagboek! 📝✨
