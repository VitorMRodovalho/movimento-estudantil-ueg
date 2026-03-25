-------------------- INICIO DO SCRIPT REFERENTE  AO BANCO DE DADOS 'bd_Semanenc' ----------------------
/*
	ULTIMA MODIFICAÇÃO: DATA 13/02/06 HORA 21:30
	SCRIPT DE CRIAÇÃO DE TABELAS REFERENTE AO BANCO DE
	DADOS: 'bd_Semanenc' DO PROJETO CADASTRO SEMANA DE ENGENHARIA
	MARCELL FLÁVIO
        CHARLES D'RICHER
*/

/*
	TABELA REFERENTE CIDADE 'tb_Cidade'
	CAMPO UF - APENAS SIGLA
*/
CREATE TABLE tb_Cidade(
Cod_Cidade int identity primary key,
Nome_Cidade varchar(20),
Uf_Cidade char(2)
)

/*
	TABELA REFERENTE UNIVERSIDADE 'tb_Universidade'
	CAMPO UF - APENAS SIGLA
*/
CREATE TABLE tb_Universidade(
Cod_Universidade int identity primary key,
Nome_Universidade varchar(50)
)

/*
	TABELA REFERENTE AOS ALUNOS 'tb_Aluno'
	CHAVER ESTRANGUEIRA - 'Cod_Cidade_Aluno'E 'Cod_Universiade_Aluno'
*/
CREATE TABLE tb_Aluno(
Cod_Aluno int identity primary key,
Nome_Aluno varchar(50) not null unique,
DocIdentidade_Aluno varchar(20),
DataNascimento_Aluno datetime,
Sexo_Aluno char(1),
Logradouro_Aluno varchar(70),
Setor_Aluno varchar(20),
Cod_Cidade_Aluno int,
TeleCelular_Aluno char(13),
TeleResidencial_Aluno char(13),
Email_Aluno varchar(25),
Curso_Aluno varchar(25),
Periodo_Aluno varchar(11),
Cod_Universidade_Aluno int,
CONSTRAINT rel_Cidade_Aluno FOREIGN KEY(Cod_Cidade_Aluno) REFERENCES tb_Cidade,
CONSTRAINT rel_Universidade_Aluno FOREIGN KEY(Cod_Universidade_Aluno) REFERENCES tb_Universidade
)

/*
	TABELA REFERENTE AO MICURSO 'tb_Minicurso'
*/
CREATE TABLE tb_Minicurso(
Cod_Minicurso int identity primary key,
Nome_Minicurso varchar(50) not null,
TotalVaga_Minicurso int not null,
Data_Minicurso char(25),
Horario_Minicurso char(14)
)


/*
	TABELA REFERENTE AO EVENTO 'tb_Evento'
	CHAVE ESTRAGUEIRA - 'Cod_Minicurso_Evento' E 'Cod_Aluno_Evento'
*/
CREATE TABLE tb_Evento(
Cod_Evento int identity primary key,
DataMatricula_Evento datetime,
Cod_Minicurso_Evento int,
Cod_Aluno_Evento int,
CONSTRAINT rel_Minicurso_Evento FOREIGN KEY(Cod_Minicurso_Evento) REFERENCES tb_Minicurso,
CONSTRAINT rel_Aluno_Evento FOREIGN KEY(Cod_Aluno_Evento) REFERENCES tb_Aluno
)

/*
	STORED PROCEDURE - TABELA 'tb_Cidade' - ALTERAR INCLUIR E EXCLUIR
	NOME 'sp_Cidade'
*/
CREATE PROC sp_Cidade
@tipo char(1),--PARAMETRO DE CONTROLE
@Cod_Cidade int,
@Nome_Cidade varchar(20),
@UF_Cidade char(2)
as
begin
	if (@tipo = 'I')--INCLUIR CIDADE
	    begin
		insert into tb_Cidade values
			(@Nome_Cidade, @UF_Cidade)
	    end 		
	    else if (@tipo = 'E')--EXCLUIR CIDADE
		     begin
			delete from tb_Cidade 
			where Cod_Cidade = @Cod_Cidade
		     end
	    	     else if (@tipo = 'A')--ALTERAR CIDADE
			      begin
				update tb_Cidade set
				Nome_Cidade = @Nome_Cidade,
				Uf_Cidade   = @Uf_Cidade
				where Cod_Cidade = @Cod_Cidade
			      end
			      else RaisError('Erro de Parametro sp_Cidade', 16,2)
end

/*
	STORED PROCEDURE - TABELA 'tb_Universidade' - ALTERAR INCLUIR E EXCLUIR
	NOME 'sp_Universidade'
*/
CREATE PROC sp_Universidade
@tipo char(1),--PARAMETRO DE CONTROLE
@Cod_Universidade int,
@Nome_Universidade varchar(50)
as
begin
	if (@tipo = 'I')--INCLUIR
	    begin
		insert into tb_Universidade values
			(@Nome_Universidade)
	    end 		
	    else if (@tipo = 'E')--EXCLUIR
		     begin
			delete from tb_Universidade
			where Cod_Universidade = @Cod_Universidade
		     end
	    	     else if (@tipo = 'A')--ALTERAR
			      begin
				update tb_Universidade set
				Nome_Universidade = @Nome_Universidade
				where Cod_Universidade = @Cod_Universidade
			      end
			      else RaisError('Erro de Parametro sp_Universidade', 16,2)
end

/*
	STORED PROCEDURE - TABELA 'tb_Minicurso'  - ALTERAR INCLUIR EXCLUIR
	NOME 'sp_Minicurso'
*/
CREATE PROC sp_Minicurso
@tipo char(1),--PARAMETRO DE CONTROLE
@Cod_Minicurso int,
@Nome_Minicurso varchar(50),
@TotalVaga_Minicurso int,
@Data_Minicurso char(25),
@Horario_Minicurso char(14)
as
begin
	if (@tipo = 'I')--INCLUIR
	    begin
		insert into tb_Minicurso values
			(@Nome_Minicurso, @TotalVaga_Minicurso,
			 @Data_Minicurso, @Horario_Minicurso)
	    end 		
	    else if (@tipo = 'E')--EXCLUIR
		     begin
			delete from tb_Minicurso 
			where Cod_Minicurso = @Cod_Minicurso
		     end
	    	     else if (@tipo = 'A')--ALTERAR
			      begin
				update tb_Minicurso set
				Nome_Minicurso         = @Nome_Minicurso,
				TotalVaga_Minicurso    = @TotalVaga_Minicurso,
				Data_Minicurso         = @Data_Minicurso,
				Horario_Minicurso      = @Horario_Minicurso
				where Cod_Minicurso = @Cod_Minicurso
			      end
			      else RaisError('Erro de Parametro sp_Minicurso', 16,2)
end

/*
	STORED PROCEDURE - TABELA tb_Aluno  - ALTERAR INCLUIR E EXCLUIR
	NOME 'sp_Aluno'
*/
CREATE PROC sp_Aluno
@tipo char(1),--PARAMETRO DE CONTROLE
@Cod_Aluno int,
@Nome_Aluno varchar(50),
@DocIdentidade_Aluno varchar(20),
@DataNascimento_Aluno datetime,
@Sexo_Aluno char(1),
@Logradouro_Aluno varchar(70),
@Setor_Aluno varchar(20),
@Cod_Cidade_Aluno int,
@TeleCelular_Aluno char(13),
@TeleResidencial_Aluno char(13),
@Email_Aluno varchar(25),
@Curso_Aluno varchar(25),
@Periodo_Aluno varchar(11),
@Cod_Universidade_Aluno int
as
begin
	if (@tipo = 'I')--INCLUIR
	    begin
		insert into tb_Aluno values
			(@Nome_Aluno, @DocIdentidade_Aluno, @DataNascimento_Aluno, @Sexo_Aluno,
                         @Logradouro_Aluno, @Setor_Aluno, @Cod_Cidade_Aluno, @TeleCelular_Aluno,
                         @TeleResidencial_Aluno, @Email_Aluno, @Curso_Aluno, @Periodo_Aluno, @Cod_Universidade_Aluno)
	    end 		
	    else if (@tipo = 'E')--EXCLUIR
		     begin
			delete from tb_Aluno
			where Cod_Aluno = @Cod_Aluno
		     end
	    	     else if (@tipo = 'A')--ALTERAR
			      begin
				update tb_Aluno set
				Nome_Aluno = @Nome_Aluno, 
				DocIdentidade_Aluno = @DocIdentidade_Aluno,
				DataNascimento_Aluno      = @DataNascimento_Aluno,
				Sexo_Aluno                = @Sexo_Aluno,
                         	Logradouro_Aluno          = @Logradouro_Aluno,
 				Setor_Aluno               = @Setor_Aluno,
				Cod_Cidade_Aluno          = @Cod_Cidade_Aluno,
				TeleCelular_Aluno         = @TeleCelular_Aluno,
                         	TeleResidencial_Aluno     = @TeleResidencial_Aluno,
				Email_Aluno               = @Email_Aluno,
				Curso_Aluno               = @Curso_Aluno,
				Periodo_Aluno             = @Periodo_Aluno,
				Cod_Universidade_Aluno    = @Cod_Universidade_Aluno
				where Cod_Aluno = @Cod_Aluno
			      end
			      else RaisError('Erro de Parametro sp_Aluno', 16,2)
end

/*
	STORED PROCEDURE - 'TABELA tb_Evento'  - ALTERAR INCLUIR E EXCLUIR
*/
CREATE PROC sp_Evento
@tipo char(1),--parametro de controle
@Cod_Evento int,
@DataMatricula_Evento datetime,
@Cod_Minicurso_Evento int,
@Cod_Aluno_Evento int
as
begin
	if (@tipo = 'I')--incluir
	    begin
		insert into tb_Evento values
			(@DataMatricula_Evento, @Cod_Minicurso_Evento, @Cod_Aluno_Evento)
	    end 		
	    else if (@tipo = 'E')--excluir cidade
		     begin
			delete from tb_Evento
			where Cod_Evento = @Cod_Evento
		     end
	    	     else if (@tipo = 'A')--ALTERAR
			      begin
				update tb_Evento set
				DataMatricula_Evento = @DataMatricula_Evento,
				Cod_Minicurso_Evento = @Cod_Minicurso_Evento,
				Cod_Aluno_Evento     = @Cod_Aluno_Evento
				where Cod_Evento = @Cod_Evento
			      end
			      else RaisError('Erro de Parametro sp_Evento', 16,2)
end


-------------------- FIM DO SCRIPT REFERENTE  AO BANCO DE DADOS 'bd_Semanenc' ----------------------


SELECT * FROM tb_Cidade
SELECT * FROM tb_Aluno
SELECT * FROM tb_Minicurso
SELECT * FROM tb_Evento

SELECT a.Cod_Aluno, a.Nome_Aluno, a.Universidade_Aluno
FROM tb_Aluno a, tb_Minicurso m, tb_Evento e
WHERE e.Cod_Minicurso_Evento = m.Cod_Minicurso 
AND m.Nome_Minicurso  =  'DELPHI'
AND a.Cod_Aluno = e.Cod_Aluno_Evento
ORDER BY a.Nome_Aluno
GO
SELECT COUNT(e.Cod_Minicurso_Evento) 
FROM tb_Aluno a, tb_Minicurso m, tb_Evento e
WHERE e.Cod_Minicurso_Evento = m.Cod_Minicurso 
AND m.Nome_Minicurso  =  'DELPHI'
AND a.Cod_Aluno = e.Cod_Aluno_Evento
DROP PROC SP_Minicurso;
