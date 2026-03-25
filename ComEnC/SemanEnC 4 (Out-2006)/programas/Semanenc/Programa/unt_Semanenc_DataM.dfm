object dataM_Semanenc: TdataM_Semanenc
  OldCreateOrder = False
  Left = 289
  Top = 161
  Height = 325
  Width = 632
  object ADOCon_Semanenc: TADOConnection
    Connected = True
    ConnectionString = 
      'Provider=SQLOLEDB.1;Persist Security Info=False;User ID=sa;Initi' +
      'al Catalog=bd_Semanenc'
    LoginPrompt = False
    Provider = 'SQLOLEDB.1'
    Left = 47
    Top = 14
  end
  object ADOsql_Universidade: TADOQuery
    Connection = ADOCon_Semanenc
    CursorType = ctStatic
    Parameters = <>
    SQL.Strings = (
      'SELECT * '
      'FROM tb_Universidade'
      'ORDER BY Nome_Universidade')
    Left = 48
    Top = 85
    object ADOsql_UniversidadeCod_Universidade: TAutoIncField
      FieldName = 'Cod_Universidade'
      ReadOnly = True
    end
    object ADOsql_UniversidadeNome_Universidade: TStringField
      FieldName = 'Nome_Universidade'
      Size = 50
    end
  end
  object ADOsql_Cidade: TADOQuery
    Active = True
    Connection = ADOCon_Semanenc
    CursorType = ctStatic
    Parameters = <>
    SQL.Strings = (
      'SELECT *'
      'FROM tb_Cidade'
      'ORDER BY Nome_Cidade')
    Left = 153
    Top = 85
    object ADOsql_CidadeCod_Cidade: TAutoIncField
      FieldName = 'Cod_Cidade'
      ReadOnly = True
    end
    object ADOsql_CidadeNome_Cidade: TStringField
      FieldName = 'Nome_Cidade'
    end
    object ADOsql_CidadeUf_Cidade: TStringField
      FieldName = 'Uf_Cidade'
      FixedChar = True
      Size = 2
    end
  end
  object ADOsp_Aluno: TADOStoredProc
    Connection = ADOCon_Semanenc
    ProcedureName = 'sp_Aluno;1'
    Parameters = <
      item
        Name = 'RETURN_VALUE'
        DataType = ftInteger
        Direction = pdReturnValue
        Precision = 10
        Value = Null
      end
      item
        Name = '@tipo'
        Attributes = [paNullable]
        DataType = ftString
        Size = 1
        Value = ''
      end
      item
        Name = '@Cod_Aluno'
        Attributes = [paNullable]
        DataType = ftInteger
        Precision = 10
        Value = 0
      end
      item
        Name = '@Nome_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 50
        Value = Null
      end
      item
        Name = '@DocIdentidade_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 20
        Value = Null
      end
      item
        Name = '@DataNascimento_Aluno'
        Attributes = [paNullable]
        DataType = ftDateTime
        Value = Null
      end
      item
        Name = '@Sexo_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 1
        Value = Null
      end
      item
        Name = '@Logradouro_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 70
        Value = Null
      end
      item
        Name = '@Setor_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 20
        Value = Null
      end
      item
        Name = '@Cod_Cidade_Aluno'
        Attributes = [paNullable]
        DataType = ftInteger
        Precision = 10
        Value = Null
      end
      item
        Name = '@TeleCelular_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 13
        Value = Null
      end
      item
        Name = '@TeleResidencial_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 13
        Value = Null
      end
      item
        Name = '@Email_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 25
        Value = Null
      end
      item
        Name = '@Curso_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 25
        Value = Null
      end
      item
        Name = '@Periodo_Aluno'
        Attributes = [paNullable]
        DataType = ftString
        Size = 11
        Value = Null
      end
      item
        Name = '@Cod_Universidade_Aluno'
        Attributes = [paNullable]
        DataType = ftInteger
        Precision = 10
        Value = Null
      end>
    Left = 243
    Top = 85
  end
  object ADOTb_Minicurso: TADOTable
    Active = True
    Connection = ADOCon_Semanenc
    CursorType = ctStatic
    TableName = 'tb_Minicurso'
    Left = 352
    Top = 12
    object ADOTb_MinicursoCod_Minicurso: TAutoIncField
      FieldName = 'Cod_Minicurso'
      ReadOnly = True
    end
    object ADOTb_MinicursoNome_Minicurso: TStringField
      FieldName = 'Nome_Minicurso'
      Size = 50
    end
    object ADOTb_MinicursoTotalVaga_Minicurso: TIntegerField
      FieldName = 'TotalVaga_Minicurso'
    end
    object ADOTb_MinicursoData_Minicurso: TStringField
      FieldName = 'Data_Minicurso'
      FixedChar = True
      Size = 25
    end
    object ADOTb_MinicursoHorario_Minicurso: TStringField
      FieldName = 'Horario_Minicurso'
      FixedChar = True
      Size = 14
    end
  end
  object dts_Minicurso: TDataSource
    DataSet = ADOTb_Minicurso
    Left = 354
    Top = 84
  end
  object ADOTb_Cidade: TADOTable
    Active = True
    Connection = ADOCon_Semanenc
    CursorType = ctStatic
    TableName = 'tb_Cidade'
    Left = 444
    Top = 11
  end
  object dts_Cidade: TDataSource
    DataSet = ADOsql_Cidade
    Left = 450
    Top = 85
  end
  object dts_Universidade: TDataSource
    DataSet = ADOsql_UniversidadeInserir
    Left = 538
    Top = 184
  end
  object ADOsp_Universidade: TADOStoredProc
    Connection = ADOCon_Semanenc
    ProcedureName = 'sp_Universidade;1'
    Parameters = <
      item
        Name = 'RETURN_VALUE'
        DataType = ftInteger
        Direction = pdReturnValue
        Precision = 10
        Value = Null
      end
      item
        Name = '@tipo'
        Attributes = [paNullable]
        DataType = ftString
        Size = 16
        Value = '0'
      end
      item
        Name = '@Cod_Universidade'
        Attributes = [paNullable]
        DataType = ftInteger
        Precision = 10
        Value = Null
      end
      item
        Name = '@Nome_Universidade'
        Attributes = [paNullable]
        DataType = ftString
        Size = 50
        Value = Null
      end>
    Left = 536
    Top = 72
  end
  object ADOsql_UniversidadeInserir: TADOQuery
    Connection = ADOCon_Semanenc
    CursorType = ctStatic
    Parameters = <
      item
        Name = 'pNome'
        Attributes = [paNullable]
        DataType = ftString
        NumericScale = 255
        Precision = 255
        Size = 50
        Value = Null
      end>
    SQL.Strings = (
      'SELECT * '
      'FROM tb_Universidade'
      'WHERE Nome_Universidade LIKE :pNome + '#39'%'#39
      'ORDER BY Cod_Universidade'
      '')
    Left = 536
    Top = 128
  end
end
