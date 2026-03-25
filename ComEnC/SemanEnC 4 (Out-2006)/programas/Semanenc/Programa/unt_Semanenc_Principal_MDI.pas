unit unt_Semanenc_Principal_MDI;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, Menus, XPMan, ComCtrls, ExtCtrls, StdCtrls;

type
  Tfrm_Principal = class(TForm)
    MainMenu_Principal: TMainMenu;
    Cadastro1: TMenuItem;
    stb_Principal: TStatusBar;
    XPManifest1: TXPManifest;
    Timer1: TTimer;
    Alunos1: TMenuItem;
    Evento1: TMenuItem;
    Configuraes1: TMenuItem;
    Universidade1: TMenuItem;
    Cidade1: TMenuItem;
    Minicurso1: TMenuItem;
    procedure Timer1Timer(Sender: TObject);
    procedure Alunos1Click(Sender: TObject);
    procedure LimpaCampos(LimpaForm: TForm);
    procedure Universidade1Click(Sender: TObject);
    procedure Cidade1Click(Sender: TObject);
    procedure Minicurso1Click(Sender: TObject);
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  frm_Principal: Tfrm_Principal;
  OldHeight: Integer;

implementation

uses unt_Semanenc_Cadastro_Aluno_CHILD, unt_Semanenc_Inserir_CHILD;

{$R *.dfm}

procedure Tfrm_Principal.Timer1Timer(Sender: TObject);
begin
    stb_Principal.Panels[0].Text := FormatDateTime('dddd"," dd "de" mmmm "de" yyyy',date);
end;

procedure Tfrm_Principal.Alunos1Click(Sender: TObject);
begin
    if not Assigned(frm_CadastroAluno) then
        begin
            Application.CreateForm(Tfrm_CadastroAluno, frm_CadastroAluno);
            frm_CadastroAluno.Show;
        end
    else
        begin
            frm_CadastroAluno.WindowState := wsNormal;
            frm_CadastroAluno.Show;
        end;
end;

procedure Tfrm_Principal.Universidade1Click(Sender: TObject);
begin
    if frm_inserir = nil then
        begin
            Application.CreateForm(Tfrm_Inserir, frm_Inserir);
            frm_Inserir.tbSheet_Universidade.Show;
        end
    else
        begin
            frm_Inserir.tbSheet_Universidade.Show;
        end;
end;

procedure Tfrm_Principal.Cidade1Click(Sender: TObject);
begin
    if frm_inserir = nil then
        begin
            Application.CreateForm(Tfrm_Inserir, frm_Inserir);
            frm_Inserir.tbSheet_Cidade.Show;
        end
    else
        begin
            frm_Inserir.tbSheet_Cidade.Show;
        end;
end;

procedure Tfrm_Principal.Minicurso1Click(Sender: TObject);
begin
    if frm_inserir = nil then
        begin
            Application.CreateForm(Tfrm_Inserir, frm_Inserir);
            frm_Inserir.tbSheet_Minicurso.Show;
        end
    else
        begin
            frm_Inserir.tbSheet_Minicurso.Show;
        end;
end;

procedure Tfrm_Principal.LimpaCampos(LimpaForm: TForm);
var i : Integer;
begin
    for i := 0 to LimpaForm.ComponentCount - 1 do
        begin
            if (LimpaForm.Components[i] is TCustomEdit) then
                begin
                    (LimpaForm.Components[i] as TCustomEdit).Clear;
                end;
            if (LimpaForm.Components[i] is TCustomComboBox) then
                begin
                    (LimpaForm.Components[i] as TCustomComboBox).ItemIndex := -1;
                end;
        end;
end;


end.
