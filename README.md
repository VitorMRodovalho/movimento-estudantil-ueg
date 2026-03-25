# Movimento Estudantil UEG

> Student movement archive from the Civil Engineering program at Universidade Estadual de Goias (UEG), Anapolis campus (~2005-2008).

This repository preserves the organizational life of engineering students who built institutions from scratch: a student council (CAEC), an annual engineering conference (SemanEnC), a junior enterprise, and the governance structures to run them all. It also contains two software systems they built to manage their events — a Delphi desktop app and a PHP web system.

**Key figure:** Vitor Rodovalho served as **Director General (President)** of the CAEC in the "Chapa RECONSTRUIR" slate (elected December 2005). He was also the [official web designer](https://github.com/VitorMRodovalho/engenharia-civil-ueg) for the department portal under a formal UEG subdomain contract.

**Important distinction:** The **CAEC** (Centro Academico) and **ComEnC** (Comissao Permanente) are **separate organizations**. The CAEC is the elected student government body. The ComEnC is the standing committee that organizes the annual SemanEnC engineering conference. They have different leadership, different mandates, and different archives — though their memberships often overlapped.

## What's Here

### [CAEC/](CAEC/) -- Student Council

The **Centro Academico de Engenharia Civil Marcus Vinicius Cavalcanti** — named after the department coordinator **Prof. Marcus Vinicius Silva Cavalcanti** — was the elected student government body for Civil Engineering at UEG.

The CAEC was led by the **"Chapa RECONSTRUIR"** slate (elected December 2005), with Vitor Rodovalho as Director General. The `CAEC_Chapa RECONSTRUIR_Objetivos, Proposta, Membros.pdf` documents the slate's objectives, proposals, and members.

This archive contains:

- **Governance:** Estatuto (bylaws), regimento eleitoral (election rules), atas (meeting minutes)
- **Chapa RECONSTRUIR:** Slate platform, objectives, and member list (Dec 2005)
- **CAEC web presence:** `www.engenhariacivil.ueg.br-caec.doc` — documentation of the CAEC subsite
- **Gestao 2006:** Official letters (oficios), meeting convocations, collegiate council records
- **Movimentos:** Campaigns for a permanent CAEC headquarters ("Cantinho E-Civil"), summer courses, and a university cafeteria
- **Manual do Calouro:** Freshman guide with Joomla components (com_zoom gallery, internship module)
- **Simbolo do CAEC:** Branding and identity design files (.cdr, .pdf)
- **Fotos:** Event and meeting photos

### [ComEnC/](ComEnC/) -- Engineering Week Conference

The **Comissao Permanente de Engenharia Civil (ComEnC)** — a standing committee separate from the CAEC student council — organized the annual **SemanEnC** (Semana de Engenharia Civil) conference. Each edition had its own sub-site under `engenhariacivil.ueg.br/semana/`. Four editions are archived, including the formal project document (`projeto_semanenc4.pdf`):

| Edition | Date | Highlights |
|---|---|---|
| **SemanEnC 3** | Oct 2005 | Marketing materials, CorelDRAW poster designs, event photos, cerimonial docs, [SQL database schema](ComEnC/SemanEnC%203%20(Out-2005)/Marketing%20e%20Publicidde/Site%20e%20Banco%20de%20Dados/engcivil.sql) |
| **SemanEnC 4** | Oct 2006 | **Delphi desktop app** by Marcell Flavio & Charles D'Richer (source in `programas/Semanenc/`), CorelDRAW certificates, formal project document (`projeto_semanenc4.pdf`) |
| **SemanEnC 5** | Nov 2007 | PHP web system (**[extracted to own repo](https://github.com/VitorMRodovalho/semanenc-php-registration)**), event photos, marketing materials |
| **SemanEnC 6** | Nov 2008 | Marketing/scheduling documents |

#### SemanEnC 4 Desktop App (Delphi)

Borland Delphi 7 desktop app (v0.2a) with SQL Server backend. MDI architecture for student registration, mini-course enrollment, and event management. Full source code in `programas/Semanenc/`.

**Primary developers: Marcell Flavio and Charles D'Richer** (credited in SQL schema header). Vitor Rodovalho's role was organizational as a ComEnC member, not as primary developer of this application.

#### SemanEnC 5 Web System (PHP) -- Extracted

**Extracted to: [semanenc-php-registration](https://github.com/VitorMRodovalho/semanenc-php-registration)**

PHP 4/5 web app with MySQL backend for online registration and certificate generation (LaTeX/HTML/TXT). Session-based authentication, mini-course enrollment tracking. Connected to UEG's `dbase.ueg.br`. Non-code files (photos, marketing) remain in this repo.

### [AMEnC-UEG/](AMEnC-UEG/) -- Engineering Student Association

Minimal content: a single meeting invitation PDF. The AMEnC (Associacao dos Estudantes de Engenharia Civil) operated at a higher organizational level than the CAEC.

### [onedrive-errors/](onedrive-errors/) -- Failed Downloads

78 files that failed to download from OneDrive. Notable losses:

- **Empresa Junior documents** — The junior enterprise folder, including the **GR INFOART connection**: a web development contract (`contrato final.doc`), project briefing (`Briefing.doc`), pricing table (`tabela_de_precos_de_sites.pdf`), satisfaction survey, and final proposal. These document the business relationship between GR InfoArt and the student organizations. All failed to download.
- **Documentos Diversos / Confidencial** — Unknown content, all failed
- **Additional ComEnC files** from SemanEnC editions 4, 5, and 6

## The GR InfoArt Connection

The `onedrive-errors/Empresa Junior - Eng. Civil/GR INFOART -Sites/` folder reveals that GR InfoArt (see [gr-infoart](https://github.com/VitorMRodovalho/gr-infoart)) had a formal business relationship with the Civil Engineering junior enterprise. The error logs show contract documents, a project briefing, a pricing table, and a satisfaction survey — evidence that student organizations were among GR InfoArt's clients. Unfortunately, all these documents were lost in the OneDrive download failure.

## Historical Context

Brazilian public universities have a strong tradition of student self-governance. The CAEC and ComEnC were distinct student-run organizations — the CAEC as elected student government (with its own bylaws, board, and budget) and the ComEnC as a standing conference committee — though their memberships often overlapped. The Empresa Junior operated as a student consulting company.

Under the "Chapa RECONSTRUIR" leadership (Dec 2005), with Vitor Rodovalho as Director General, the CAEC ran campaigns for a permanent headquarters, summer courses, and a university cafeteria. Vitor simultaneously served as the official web designer for the department under a formal UEG subdomain contract, building and maintaining engenhariacivil.ueg.br and its sub-sites.

The SemanEnC conference grew from edition to edition, each with its own sub-site under `engenhariacivil.ueg.br/semana/`. The technology evolved accordingly: a SQL database schema in 2005, a full Delphi desktop app with SQL Server in 2006, and a PHP web system for online registration and certificate generation in 2007. This progression mirrors the broader technology adoption in small-city Brazil during the mid-2000s.

## Credential Sanitization

| File | What was redacted |
|---|---|
| `ComEnC/SemanEnC 5 (Nov-2007)/sistema/conexao/conexao.php` | MySQL host, user, password, database |

## Excluded Files (PII)

| File | Reason |
|---|---|
| `CAEC_Diretoria, nome, cpf, rg, endereco.doc` | Board members' full CPF, RG, addresses |
| `Relacao da Diretoria eleita com CPF e Carteira de Identidade dos membros.doc/pdf` | Same — elected board IDs |
| `CAEC_Dados dos academicos com direito a voto...doc/pdf` | Student voter list with full names |
| `CAEC_Gastos (2006).xls` | Financial records with specific amounts |

## Data Source

- **OneDrive backup** — 1,656 files, 637 MB (78 files failed to download)
- No TrabalhoSite equivalent exists for this content

## License

MIT License for repository organization, documentation, and student-developed code. See [LICENSE](LICENSE).

Joomla components in CAEC Manual retain GPL v2. Design files (.cdr, .psd) are shared for historical/archival purposes.

---

# Movimento Estudantil UEG (PT-BR)

> Arquivo do movimento estudantil do curso de Engenharia Civil da UEG, campus Anapolis (~2005-2008).

Este repositorio preserva a vida organizacional dos estudantes de engenharia que construiram instituicoes do zero: um centro academico (CAEC), uma conferencia anual (SemanEnC), uma empresa junior, e as estruturas de governanca para gerencia-las. Contem tambem dois sistemas de software que eles construiram para gerenciar seus eventos.

## O Que Esta Aqui

### CAEC -- Centro Academico

Centro Academico de Engenharia Civil Marcus Vinicius Cavalcanti — nomeado em homenagem ao coordenador **Prof. Marcus Vinicius Silva Cavalcanti**. Liderado pela **Chapa RECONSTRUIR** (Dez 2005) com Vitor Rodovalho como Diretor Geral. Estatuto, regimentos, atas, oficios, movimentos (sede propria, curso de ferias, restaurante universitario), manual do calouro, fotos e identidade visual.

### ComEnC -- Semana de Engenharia

Comissao Permanente de Engenharia Civil — comite separado do CAEC, responsavel pela organizacao da SemanEnC. Cada edicao tinha seu proprio sub-site em `engenhariacivil.ueg.br/semana/`. Quatro edicoes documentadas (2005-2008), incluindo:
- **SemanEnC 4 (2006):** Aplicativo desktop Delphi para registro de participantes, banco SQL Server
- **SemanEnC 5 (2007):** Sistema web PHP para inscricao online e geracao de certificados
- Marketing, artes em CorelDRAW, fotos, documentos de cerimonial

### Empresa Junior

Apenas logs de erro sobreviveram. Os documentos originais — incluindo contrato com a GR InfoArt, briefing, tabela de precos — foram perdidos na falha de download do OneDrive.

## Contexto Historico

Universidades publicas brasileiras tem forte tradicao de autogestao estudantil. CAEC, ComEnC e Empresa Junior eram organizacoes geridas por estudantes com estatutos, diretorias eleitas, orcamentos e relacoes institucionais. A evolucao tecnologica da SemanEnC — de schema SQL (2005) para app Delphi (2006) para sistema web PHP (2007) — espelha a adocao tecnologica no interior do Brasil nos anos 2000.
