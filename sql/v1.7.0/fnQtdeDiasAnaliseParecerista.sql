-- =========================================================================================
-- Autor: Rômulo Menhô Barbosa
-- Data de Criação: 17/04/2014
-- Descrição: Calcular o tempo de análise do parecerista
-- =========================================================================================
-- Data de Alteração: 20/04/2016
-- Motivo: Inclusão do tipo de tempo de análise
-- =========================================================================================

IF OBJECT_ID(N'dbo.fnQtdeDiasAnaliseParecerista') IS NOT NULL
    DROP FUNCTION dbo.fnQtdeDiasAnaliseParecerista;
GO


SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS OFF 
GO

CREATE FUNCTION dbo.fnQtdeDiasAnaliseParecerista (@p_idPronac int, @p_idProduto int,@p_Tipo int) 
RETURNS int
AS  
BEGIN 
    DECLARE @qtDiasDiligencia         int
    DECLARE @qtDiasAnaliseParecerista int
    DECLARE @qtDiasAnaliseTotal       int
    DECLARE @dtDistribuicao           datetime
    DECLARE @dtDevolucao              datetime
    DECLARE @dtSolicitacao            datetime
    DECLARE @dtResposta               datetime
	DECLARE @qtDias                   int
--===============================================================================================================
-- PEGAR AS DATAS DE DISTRIBUICAO E DEVOLUÇÃO DA ANALISE   
--===============================================================================================================
    SELECT @dtDistribuicao = DtDistribuicao, @dtDevolucao =  dtDevolucao
           FROM tbDistribuirParecer 
           WHERE TipoAnalise in (1,3) and FecharAnalise = 0 and stEstado = 0 and idpronac = @p_idPronac and idProduto = @p_idProduto  
--===============================================================================================================
-- PEGAR AS DATAS DE  SOLICITAÇÃO E RESPOSTAS DA DILIGÊNCIA   
--===============================================================================================================
    SELECT @dtSolicitacao = dtSolicitacao,@dtResposta = dtResposta 
           FROM tbDiligencia 
           WHERE stEstado = 0 and idTipoDiligencia = 124 and idpronac = @p_idPronac and idProduto = @p_idProduto 
--===============================================================================================================
-- CALCULAR TEMPO DE RESPOSTA DA DILIGÊNCIA   
--===============================================================================================================
    SET @qtDiasDiligencia = 0  
	IF @dtSolicitacao IS NOT NULL AND @dtResposta IS NULL
          SET @qtDiasDiligencia = DATEDIFF(DAY,@dtSolicitacao,GETDATE()) 
    ELSE
    IF @dtSolicitacao IS NOT NULL AND @dtResposta IS NOT NULL
          SET @qtDiasDiligencia = DATEDIFF(DAY,@dtSolicitacao,@dtResposta)
		   
    IF CONVERT(CHAR(10),@dtSolicitacao,112) < CONVERT(CHAR(10),@dtDistribuicao,112)  
       SET @qtDiasDiligencia = 0  
--===============================================================================================================
-- CALCULAR TEMPO DE ANÁLISE TOTAL  
--===============================================================================================================
    SET @qtDiasAnaliseTotal = 0
    IF @dtDistribuicao IS NOT NULL AND @dtDevolucao IS NULL
          SET @qtDiasAnaliseTotal = DATEDIFF(DAY,@dtDistribuicao,GETDATE()) 
    ELSE
    IF @dtDistribuicao IS NOT NULL AND @dtDevolucao IS NOT NULL
          SET @qtDiasAnaliseTotal = DATEDIFF(DAY,@dtDistribuicao,@dtDevolucao) 
--===============================================================================================================
-- CALCULAR TEMPO DE ANÁLISE DO PARECERISTA  
--===============================================================================================================
    IF @qtDiasAnaliseTotal > @qtDiasDiligencia
	   SET @qtDiasAnaliseParecerista = @qtDiasAnaliseTotal - @qtDiasDiligencia
	ELSE
	   SET @qtDiasAnaliseParecerista = @qtDiasDiligencia - @qtDiasAnaliseTotal
--===============================================================================================================
-- PREPARAR VARIÁVEL DE RETORNO CONFORME O TIPO
--  TIPO 0 : TEMPO TOTAL DE ANÁLISE
--  TIPO 1 : TEMPO TEMPO GASTO PELO PARECERSITA
--  TIPO 2 : TEMPO DE DILIGÊNCIA
--===============================================================================================================
	IF @p_Tipo = 0
	   SET @qtDias = @qtDiasAnaliseTotal
	ELSE
	IF @p_Tipo = 1
	   SET @qtDias = @qtDiasAnaliseParecerista
	ELSE
	   SET @qtDias = @qtDiasDiligencia

    RETURN(@qtDias)
END

GO
SET QUOTED_IDENTIFIER OFF 
GO
SET ANSI_NULLS ON 
GO

GRANT  EXECUTE  ON dbo.fnQtdeDiasAnaliseParecerista  TO usuarios_internet
GO
