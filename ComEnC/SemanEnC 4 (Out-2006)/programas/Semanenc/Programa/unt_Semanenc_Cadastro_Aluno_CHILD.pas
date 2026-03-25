unit unt_Semanenc_Cadastro_Aluno_CHILD;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, Buttons, Mask, ExtCtrls;

type
    Tfrm_CadastroAluno = class(TForm)
    gbx_Menu: TGroupBox;
    bbtn_Gravar: TBitBtn;
    bbtn_Limpar: TBitBtn;
    bbtn_Sair: TBitBtn;
    edt_NomeAluno: TEdit;
    lbl_NomeAluno: TLabel;
    gbx_DocIdentidadeAluno: TGroupBox;
    medit_DataNascAluno: TMaskEdit;
    lbl_DataNascAluno: TLabel;
    edt_NumeroIdentidade: TEdit;
    cbx_OrgExpDocIdent: TComboBox;
    cbx_UfIdentidade: TComboBox;
    lbl_NumeroDocIdentidade: TLabel;
    lbl_OrgExpIdentidade: TLabel;
    lbl_UfDocIdentAluno: TLabel;
    rdg_SexoAluno: TRadioGroup;
    gbx_UniversidadeAluno: TGroupBox;
    gbx_EnderecoAluno: TGroupBox;
    gbx_ContatoAluno: TGroupBox;
    edt_CursoAluno: TEdit;
    cbx_PeridoAluno: TComboBox;
    lbl_CursoAluno: TLabel;
    lbl_PeridoAluno: TLabel;
    lbl_NomeUniversidade: TLabel;
    cbx_NomeUniversidade: TComboBox;
    spbtn_InserirUniversidade: TSpeedButton;
    edt_LogradouroAluno: TEdit;
    edt_UfCidade: TEdit;
    cbx_NomeCidade: TComboBox;
    edt_CodCidade: TEdit;
    edt_SetorAluno: TEdit;
    spbtn_InserirCidade: TSpeedButton;
    medt_TeleCelularAluno: TMaskEdit;
    medt_TeleResidencialAluno: TMaskEdit;
    edt_EmailAluno: TEdit;
    lbl_TeleCelularAluno: TLabel;
    lbl_TeleResidencialAluno: TLabel;
    lbl_EmailAluno: TLabel;
    edt_CodUniversidadeAluno: TEdit;
    lbl_CodUniversidadeAluno: TLabel;
    lbl_LogradouroAluno: TLabel;
    lbl_SetorAluno: TLabel;
    lbl_NomeCidade: TLabel;
    lbl_UfCidade: TLabel;
    lbl_CodCidade: TLabel;
    bbtn_AtualizarDados: TBitBtn;
    procedure FormClose(Sender: TObject; var Action: TCloseAction);
    procedure bbtn_SairClick(Sender: TObject);
    procedure bbtn_LimparClick(Sender: TObject);
    procedure FormCreate(Sender: TObject);
    procedure cbx_NomeUniversidadeSelect(Sender: TObject);
    procedure AtualizaDados;
    procedure bbtn_AtualizarDadosClick(Sender: TObject);
    procedure bbtn_GravarClick(Sender: TObject);
    procedure cbx_NomeCidadeSelect(Sender: TObject);
    procedure edt_NomeAlunoKeyPress(Sender: TObject; var Key: Char);
    procedure spbtn_InserirUniversidadeClick(Sender: TObject);
    procedure spbtn_InserirCidadeClick(Sender: TObject);
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  frm_CadastroAluno: Tfrm_CadastroAluno;

implementation

uses unt_Semanenc_Principal_MDI, unt_Semanenc_DataM, DB, ADODB;

{$R *.dfm}


procedure Tfrm_CadastroAluno.FormClose(Sender: TObject;
  var Action: TCloseAction);
begin
    FreeAndNil(frm_CadastroAluno);
end;

procedure Tfrm_CadastroAluno.bbtn_SairClick(Sender: TObject);
begin
    frm_CadastroAluno.Close;
end;

procedure Tfrm_CadastroAluno.bbtn_LimparClick(Sender: TObject);
begin
    frm_Principal.LimpaCampos(frm_CadastroAluno);
    edt_NomeAluno.SetFocus;
end;

procedure Tfrm_CadastroAluno.FormCreate(Sender: TObject);
begin
    AtualizaDados;
end;

procedure Tfrm_CadastroAluno.cbx_NomeUniversidadeSelect(Sender: TObject);
begin
    with dataM_Semanenc do
        begin
            ADOsql_Universidade.First;
            while not ADOsql_Universidade.EOF and (cbx_NomeUniversidade.Text <> ADOsql_UniversidadeNome_Universidade.Value) do
                begin
                    ADOsql_Universidade.Next;
                end;
            edt_CodUniversidadeAluno.Text := IntToStr(ADOsql_UniversidadeCod_Universidade.Value);
        end;
end;

procedure Tfrm_CadastroAluno.cbx_NomeCidadeSelect(Sender: TObject);
begin
    with dataM_Semanenc do
        begin
            ADOsql_Cidade.First;
            while not ADOsql_Cidade.EOF and (cbx_NomeCidade.Text <> ADOsql_CidadeNome_Cidade.Value) do
                begin
                    ADOsql_Cidade.Next;
                end;
            edt_CodCidade.Text := IntToStr(ADOsql_CidadeCod_Cidade.Value);
            edt_UfCidade.Text  := ADOsql_CidadeUf_Cidade.Value;
        end;
end;

procedure Tfrm_CadastroAluno.AtualizaDados; // ATUALIZA COMBOBOX NOMEUNIVERSIDADE E NOMECIDADE
var i: Integer;
begin
    with dataM_Semanenc do
        begin
            // COMBOBOX TB_UNIVERSIDADE
            cbx_NomeUniversidade.Items.Clear;
            ADOsql_Universidade.Close; // REALIZA NOVA CONSULTA
            ADOsql_Universidade.Open;
            ADOsql_Universidade.First; // POSICIONA NO PRIMEIRO REGISTRO
            for i:= 0 to ADOsql_Universidade.RecordCount - 1 do
                begin
                    cbx_NomeUniversidade.Items.Add(ADOsql_UniversidadeNome_Universidade.Value);
                    ADOsql_Universidade.Next; // POSICIONA PROXIMO REGISTRO
                end;
            // COMBOBOX  TB_CIDADE
            cbx_NomeCidade.Items.Clear;
            ADOsql_Cidade.Close;
            ADOsql_Cidade.Open;
            ADOsql_Cidade.First;
            for i:= 0 to ADOsql_Cidade.RecordCount - 1 do
                begin
                    cbx_NomeCidade.Items.Add(ADOsql_CidadeNome_Cidade.Value);
                    ADOsql_Cidade.Next;
                end;
        end;
end;

procedure Tfrm_CadastroAluno.bbtn_AtualizarDadosClick(Sender: TObject);
begin
    AtualizaDados;
end;

procedure Tfrm_CadastroAluno.bbtn_GravarClick(Sender: TObject);
begin
      if (application.MessageBox('Deseja cadastrar aluno?','Atenção', MB_YESNO + MB_ICONQUESTION + MB_DEFBUTTON2) = idyes) then
          begin
              try
                  with dataM_Semanenc.ADOsp_Aluno do  // STORED PROCEDURE SP_ALUNO
                      begin
                          Close;
                          Parameters.ParamByName('@Tipo').Value                   := 'I';
                          Parameters.ParamByName('@Nome_Aluno').Value             := Trim(edt_NomeAluno.Text);
                          Parameters.ParamByName('@DocIdentidade_Aluno').Value    := Trim(edt_NumeroIdentidade.Text + #32 + cbx_OrgExpDocIdent.Text + #45 + cbx_UfIdentidade.Text);
                          Parameters.ParamByName('@DataNascimento_Aluno').Value   := StrToDate(medit_DataNascAluno.Text);
                          Parameters.ParamByName('@Sexo_Aluno').Value             := rdg_SexoAluno.Items.Strings[rdg_SexoAluno.ItemIndex];
                          Parameters.ParamByName('@Logradouro_Aluno').Value       := edt_LogradouroAluno.Text;
                          Parameters.ParamByName('@Setor_Aluno').Value            := edt_SetorAluno.Text;
                          Parameters.ParamByName('@Cod_Cidade_Aluno').Value       := StrToInt(edt_CodCidade.Text);
                          Parameters.ParamByName('@TeleCelular_Aluno').Value      := medt_TeleCelularAluno.Text;
                          Parameters.ParamByName('@TeleResidencial_Aluno').Value  := medt_TeleResidencialAluno.Text;
                          Parameters.ParamByName('@Email_Aluno').Value            := edt_EmailAluno.Text;
                          Parameters.ParamByName('@Curso_Aluno').Value            := edt_CursoAluno.Text;
                          Parameters.ParamByName('@Periodo_Aluno').Value          := cbx_PeridoAluno.Text;
                          Parameters.ParamByName('@Cod_Universidade_Aluno').Value := StrToInt(edt_CodUniversidadeAluno.Text);
                          ExecProc;
                      end;
                  frm_Principal.LimpaCampos(frm_CadastroAluno);
                  edt_NomeAluno.SetFocus;
              except on e: exception do
                  begin
                      ShowMessage(e.Message);
                  end;
              end;
          end;
end;

procedure Tfrm_CadastroAluno.edt_NomeAlunoKeyPress(Sender: TObject;
  var Key: Char);
begin
    if ((edt_NomeAluno.Text <> '' ) and (edt_NumeroIdentidade.Text <> '')) then
        begin
            bbtn_Gravar.Enabled := True;
        end
    else
        begin
            bbtn_Gravar.Enabled := False;
        end;
end;

procedure Tfrm_CadastroAluno.spbtn_InserirUniversidadeClick(
  Sender: TObject);
begin
    frm_Principal.Universidade1Click(Sender);
end;

procedure Tfrm_CadastroAluno.spbtn_InserirCidadeClick(Sender: TObject);
begin
    frm_Principal.Cidade1Click(Sender);
end;

end.
