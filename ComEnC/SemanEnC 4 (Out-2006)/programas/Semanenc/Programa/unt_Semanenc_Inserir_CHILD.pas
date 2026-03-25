unit unt_Semanenc_Inserir_CHILD;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, ComCtrls, StdCtrls, Buttons, Grids, DBGrids, Mask, DBCtrls,
  ExtCtrls;

type
  Tfrm_Inserir = class(TForm)
    pgControl_Inserir: TPageControl;
    tbSheet_Cidade: TTabSheet;
    tbSheet_Minicurso: TTabSheet;
    tbSheet_Universidade: TTabSheet;
    gbx_Menu: TGroupBox;
    BitBtn1: TBitBtn;
    BitBtn2: TBitBtn;
    BitBtn3: TBitBtn;
    BitBtn4: TBitBtn;
    bbtn_Sair: TBitBtn;
    GroupBox1: TGroupBox;
    GroupBox2: TGroupBox;
    edt_NomeUniversidade: TEdit;
    lbl_NomeUniversidade: TLabel;
    Edit2: TEdit;
    Label3: TLabel;
    ComboBox2: TComboBox;
    Label4: TLabel;
    BitBtn6: TBitBtn;
    bbtn_GravarUniversidade: TBitBtn;
    bbtn_AlterarUniversidade: TBitBtn;
    bbtn_ExcluirUniversidade: TBitBtn;
    BitBtn10: TBitBtn;
    BitBtn11: TBitBtn;
    BitBtn12: TBitBtn;
    BitBtn13: TBitBtn;
    BitBtn14: TBitBtn;
    BitBtn15: TBitBtn;
    DBGrid2: TDBGrid;
    DBGrid_Minicurso: TDBGrid;
    Edit3: TEdit;
    Label5: TLabel;
    Edit4: TEdit;
    Label6: TLabel;
    MaskEdit1: TMaskEdit;
    MaskEdit2: TMaskEdit;
    MaskEdit3: TMaskEdit;
    MaskEdit4: TMaskEdit;
    Label7: TLabel;
    Label8: TLabel;
    Label9: TLabel;
    Label10: TLabel;
    DBGrid_Universidade: TDBGrid;
    DBNavigator_Universidade: TDBNavigator;
    edt_CodUniversidade: TEdit;
    Label1: TLabel;
    procedure FormClose(Sender: TObject; var Action: TCloseAction);
    procedure bbtn_SairClick(Sender: TObject);
    procedure bbtn_GravarUniversidadeClick(Sender: TObject);
    procedure FormDestroy(Sender: TObject);
    procedure DBGrid_UniversidadeDblClick(Sender: TObject);
    procedure FormCreate(Sender: TObject);
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  frm_Inserir: Tfrm_Inserir;

implementation

uses unt_Semanenc_DataM, ADODB, unt_Semanenc_Principal_MDI;

{$R *.dfm}

procedure Tfrm_Inserir.FormClose(Sender: TObject;
  var Action: TCloseAction);
begin
    Action := caFree;
end;

procedure Tfrm_Inserir.bbtn_SairClick(Sender: TObject);
begin
    frm_Inserir.Close;
end;

procedure Tfrm_Inserir.FormDestroy(Sender: TObject);
begin
    frm_Inserir := nil;
end;

// UNIVERSIDADE
procedure Tfrm_Inserir.bbtn_GravarUniversidadeClick(Sender: TObject);
begin
    With dataM_Semanenc.ADOsp_Universidade do
        begin
            Try
                Close;
                Parameters.ParamByName('@tipo').Value              := 'I';
                Parameters.ParamByName('@Nome_Universidade').Value := edt_NomeUniversidade.Text;
                ExecProc;
                frm_Principal.LimpaCampos(frm_Inserir);
                FormCreate(Self);
                ShowMessage('Universidade inserida com sucesso');
            Except on e: exception do
                begin
                  ShowMessage(e.message);
                end;
            end;
        end;
end;

procedure Tfrm_Inserir.DBGrid_UniversidadeDblClick(Sender: TObject);
begin
    //edt_CodUniversidade.Text := FormatFloat('00', );
end;

procedure Tfrm_Inserir.FormCreate(Sender: TObject);
begin
    with dataM_Semanenc do
        begin
            ADOsql_UniversidadeInserir.Close;
            ADOsql_UniversidadeInserir.Parameters.ParamByName('pNome').Value := '%';
            ADOsql_UniversidadeInserir.Open;
        end;
end;

end.
