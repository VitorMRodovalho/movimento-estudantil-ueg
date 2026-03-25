unit unt_Semanenc_DataM;

interface

uses
  SysUtils, Classes, DB, ADODB;

type
  TdataM_Semanenc = class(TDataModule)
    ADOCon_Semanenc: TADOConnection;
    ADOsql_Universidade: TADOQuery;
    ADOsql_UniversidadeCod_Universidade: TAutoIncField;
    ADOsql_UniversidadeNome_Universidade: TStringField;
    ADOsql_Cidade: TADOQuery;
    ADOsql_CidadeCod_Cidade: TAutoIncField;
    ADOsql_CidadeNome_Cidade: TStringField;
    ADOsql_CidadeUf_Cidade: TStringField;
    ADOsp_Aluno: TADOStoredProc;
    ADOTb_Minicurso: TADOTable;
    dts_Minicurso: TDataSource;
    ADOTb_MinicursoCod_Minicurso: TAutoIncField;
    ADOTb_MinicursoNome_Minicurso: TStringField;
    ADOTb_MinicursoTotalVaga_Minicurso: TIntegerField;
    ADOTb_MinicursoData_Minicurso: TStringField;
    ADOTb_MinicursoHorario_Minicurso: TStringField;
    ADOTb_Cidade: TADOTable;
    dts_Cidade: TDataSource;
    dts_Universidade: TDataSource;
    ADOsp_Universidade: TADOStoredProc;
    ADOsql_UniversidadeInserir: TADOQuery;
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  dataM_Semanenc: TdataM_Semanenc;

implementation

{$R *.dfm}

end.
