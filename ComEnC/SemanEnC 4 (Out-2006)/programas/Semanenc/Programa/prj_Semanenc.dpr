program prj_Semanenc;

uses
  Forms,
  unt_Semanenc_Principal_MDI in 'unt_Semanenc_Principal_MDI.pas' {frm_Principal},
  unt_Semanenc_Cadastro_Aluno_CHILD in 'unt_Semanenc_Cadastro_Aluno_CHILD.pas' {frm_CadastroAluno},
  unt_Semanenc_Procedimentos in 'unt_Semanenc_Procedimentos.pas',
  unt_Semanenc_DataM in 'unt_Semanenc_DataM.pas' {dataM_Semanenc: TDataModule},
  unt_Semanenc_Inserir_CHILD in 'unt_Semanenc_Inserir_CHILD.pas' {frm_Inserir};

{$R *.res}

begin
  Application.Initialize;
  Application.Title := 'Semanenc 0.2a';
  Application.CreateForm(Tfrm_Principal, frm_Principal);
  Application.CreateForm(TdataM_Semanenc, dataM_Semanenc);
  Application.Run;
end.
