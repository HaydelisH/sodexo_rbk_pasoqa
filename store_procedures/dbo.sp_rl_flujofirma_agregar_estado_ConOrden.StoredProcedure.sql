USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_flujofirma_agregar_estado_ConOrden]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 21/108/2019
-- Descripcion: Agrega flujo de firma
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_flujofirma_agregar_estado_ConOrden 1,1,1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_flujofirma_agregar_estado_ConOrden]
@pidworkflow	INT,	-- id workflow
@pidestadowf	INT,	-- estado del documento
@pdiasmax		INT,	-- dias maximo
@pConOrden		INT

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @cantidad	INT
	DECLARE @idwf		INT
	
	DECLARE @orden		INT
	
	SELECT @orden = COUNT(*) FROM WorkflowEstadoProcesos WHERE idworkflow = @pidworkflow
	SET @orden = @orden + 1

	INSERT INTO WorkflowEstadoProcesos (idWorkflow,idEstadoWF,Orden,DiasMax,ConOrden)
	VALUES (@pidworkflow,@pidestadowf,@orden,@pdiasmax,@pConOrden)
	
	SELECT @mensaje = ''
	SELECT @error = 0
	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
