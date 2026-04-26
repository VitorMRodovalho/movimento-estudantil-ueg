# STATUS REPORT — movimento-estudantil-ueg

**Data:** 2026-04-18
**Executor:** Claude Code (instância do projeto movimento-estudantil-ueg)
**Origem:** /home/vitormrodovalho/Documents/movimento-estudantil-ueg
**Destino:** /home/vitormrodovalho/projects/movimento-estudantil-ueg

## 1. Estado pré-migração
- Branch: main
- Commit HEAD: a572754b9037a4e459be649d5ae80916158f6519 ("Initial commit")
- Dirty: 0 arquivos
- Unpushed: 0 commits (remote `origin/main` em sincronia com HEAD local)
- Tamanho: 1.1 GB

## 2. Git — ações executadas
- Commits criados: nenhum
- Push: não necessário — remote já em sincronia
- Branches empurradas: nenhuma

## 3. Dados externos (map-deps)
Referências encontradas a caminhos fora do projeto:

| Arquivo do projeto | Referência externa | Status |
|---|---|---|
| _(nenhuma)_ | _(n/a)_ | Nenhuma referência a `~/Downloads/`, `~/Documents/Backup`, `OneDrive_2026*`, paths absolutos em `/home/vitormrodovalho/` |

O README cita repos irmãos (`engenharia-civil-ueg`, `gr-infoart`, `semanenc-php-registration`) via URLs públicas do GitHub — links externos legítimos, não dependências de filesystem.

## 4. Mineração de dados (se aplicável)
- Status: Completa antes (documentado no próprio README). O repo é arquivo organizado em três domínios (CAEC, ComEnC, AMEnC-UEG) e já foi objeto de extração — o repo `semanenc-php-registration` foi separado a partir do sub-projeto SemanEnC 5, conforme registrado no README.
- Fontes consumidas: provavelmente `~/Documents/Backup/OneDrive_2026-03-24/Movimento Estudantil/` (ver seção 6 para evidência estrutural)
- Pendências: nenhuma do ponto de vista deste projeto — mineração aparenta estar concluída

## 5. Migração — integridade
- `cp -a` executado: sim (cópia concluída em ~2.3s — reflink no mesmo filesystem)
- `diff -qr` exit code: 0 (`/tmp/migration-diff-movimento-estudantil-ueg.txt` 0 linhas)
- Verificação git pós-cópia: HEAD bate (`a572754…`)
- Origem deletada: sim

## 6. Sinalizações para consolidação (fase 3)

### `~/Documents/Backup/OneDrive_2026-03-24/Movimento Estudantil/` — BACKUP EXISTE

Top-level observado no backup:
- `AMEnC-UEG/`
- `C.A.E.C. - Centro Acadêmico Marcus Vinícus Cavalcanti/`
- `ComEnC - Comissão Permante (Semana de Engenharia)/`

Top-level do repo migrado:
- `AMEnC-UEG/`
- `CAEC/`
- `ComEnC/`

**Observação**: a correspondência 1:1 por domínio é fortíssima evidência de que o backup é a fonte bruta já ingerida neste repo. O README confirma o padrão documentando a extração de `semanenc-php-registration` a partir de `ComEnC/SemanEnC 5`.

Classificação provisória: **Candidato a "consumido / seguro deletar"**. Mas **não decido deletar** — recomendo à conversa mestra:

1. Rodar `diff -qr` entre `OneDrive_2026-03-24/Movimento Estudantil/AMEnC-UEG/` e `~/projects/movimento-estudantil-ueg/AMEnC-UEG/` (e idem para CAEC, ComEnC) para confirmar superset.
2. Se o backup for subset do repo (nada extra), **seguro arquivar**.
3. Caso tenha arquivos não-importados (docs antigos, versões anteriores de PDF, fotos descartadas), decidir caso a caso.

Arquivos externos que PODEM ser deletados com segurança (escopo deste projeto):
- Nenhum decidido aqui — aguarda diff cruzado acima.

Arquivos externos que NÃO PODEM ser deletados ainda:
- `~/Documents/Backup/OneDrive_2026-03-24/Movimento Estudantil/` — aguarda análise superset.

## 7. Problemas encontrados
Nenhum.

## 8. Confirmação final
- [x] Projeto funcional em `~/projects/movimento-estudantil-ueg/`
- [x] Git remoto em dia
- [x] Origem removida
- [x] Report pronto para envio à conversa mestra
