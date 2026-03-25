# Dagboek Applicatie

Een volledig functionele web-based dagboektoepassing met gebruikersaccounts en dagboekentries.

## Functies

- ✅ **Registratie & Inloggen** - Maak een account aan en log in
- ✅ **Kaftpagina** - Mooie kaftpagina met naam van ingelogde gebruiker
- ✅ **Dashboard** - Overzicht van alle dagboekentries
- ✅ **Paginering** - Blader door je entries per 5 stuks
- ✅ **Zoekbalk** - Zoek op titel of inhoud
- ✅ **Dagboekentries** - Toevoegen, bewerken en verwijderen
- ✅ **Veiligheid** - Wachtwoorden gehashed, SQL injection protected

## Installatie

### 1. Database aanmaken
- Open phpMyAdmin (http://localhost/phpmyadmin)
- Klik op "New" of "Databases" tab
- Vul "dagboek" in bij databasenaam
- Klik "Create"

### 2. Tabellen importeren
- Kopen op de "dagboek" database
- Ga naar "Import" tab
- Upload of copy-paste inhoud van `database.sql`
- Klik "Go"

### 3. Start de applicatie
- Ga naar http://localhost/Persoonlijk%20werk/dagboek/
- Of probeer: http://localhost/Persoonlijk%20werk/dagboek/index.php

## Projectstructuur

```
dagboek/
├── config.php           # Database configuratie
├── database.sql         # SQL tabellenstructuur
├── index.php            # Inlogpagina
├── register.php         # Registratiewagina
├── cover.php            # Kaftpagina
├── dashboard.php        # Dashboard met entries
├── entry.php            # Toevoegen/bewerken entry
├── delete_entry.php     # Entry verwijderen
├── logout.php           # Uitloggen
└── README.md            # Dit bestand
```

## Accountgegevens voor testen

Je kunt direct een account aanmaken via de "Account aanmaken" link op de inlogpagina.

## Database Schema

### Users tabel
- `id` - Primaire sleutel
- `username` - Unieke gebruikersnaam
- `email` - Uniek emailadres
- `password` - Gehashed wachtwoord
- `full_name` - Volledige naam (wordt getoond op kaft)
- `created_at` - Aanmaakdatum

### Diary_entries tabel
- `id` - Primaire sleutel
- `user_id` - Verwijzing naar gebruiker
- `date` - Datum van entry (uniek per gebruiker)
- `title` - Titel van entry
- `content` - Inhoud van entry
- `created_at` - Aanmaakdatum
- `updated_at` - Bijwerkingsdatum

## Tips

- Entries zijn per datum uniek - je kunt maar 1 entry per dag hebben
- De dashboard toont entries in omgekeerde chronologische volgorde (nieuwste eerst)
- Zoeken werkt op zowel titel als inhoud
- Wachtwoorden zijn beveiligd met bcrypt hashing

## Troubleshooting

**Fout: Database connection error**
- Check of XAMPP MySQL draait
- Check database naam in config.php

**Fout: Tables don't exist**
- Import database.sql via phpMyAdmin

**Kan niet inloggen na registratie**
- Refresh de pagina en probeer opnieuw
- Check of je juiste wachtwoord van account hebt ingevuld

## Toekomstige verbeteringen

- [ ] Meerdere entries per dag
- [ ] Rich text editor
- [ ] Export als PDF/Word
- [ ] Backup & Restore
- [ ] Wachtwoord reset
- [ ] Profielbewerking
