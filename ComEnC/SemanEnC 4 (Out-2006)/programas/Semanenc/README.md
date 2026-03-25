# SemanEnC 4 Delphi Registration System

Borland Delphi 7 desktop application (version 0.2a) for managing conference attendee registration at the **IV Semana de Engenharia Civil (SemanEnC 4)**, UEG Anapolis (October 2006).

## Authors

- **Marcell Flavio** — Primary developer (database schema, stored procedures, application logic)
- **Charles D'Richer** — Primary developer (database schema, stored procedures)

Credited in the SQL schema header: *"MARCELL FLAVIO / CHARLES D'RICHER"* (`Sql/sql_bd_Semanenc.sql`). Vitor Rodovalho's role was organizational as a ComEnC committee member, not as primary developer of this application.

## Structure

```
Programa/     Delphi source: .pas, .dfm, .dpr, .exe (v0.2a)
Dados/        SQL Server database: .MDF, .LDF
Sql/          Schema with stored procedures (5 tables, 5 SPs)
Imagens/      App icons and bitmaps
delphi.zip    Archive of the source
```

## Tech Stack

Borland Delphi 7, SQL Server, MDI (Multiple Document Interface). Forms for student registration, city/university lookup, mini-course enrollment, and event tracking. By the following year, the team moved to a PHP web application — see the [SemanEnC 5 PHP system](https://github.com/VitorMRodovalho/semanenc-php-registration).
