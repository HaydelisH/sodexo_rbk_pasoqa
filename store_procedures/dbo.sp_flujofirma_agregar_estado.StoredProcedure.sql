USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_agregar_estado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 10/06/2018
-- Descripcion: Agrega flujo de firma
-- Ejemplo:exec sp_flujofirma_agregar_estado 1,1,1
-- =============================================
CREATE  PROCEDURE [dbo].[sp_flujofirma_agregar_estado]
@pidworkflow	INT,	-- id workflow
@pidestadowf	INT,	-- estado del documento
@pdiasmax		INT		-- dias maximo

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

	INSERT INTO WorkflowEstadoProcesos (idWorkflow,idEstadoWF,Orden,DiasMax)
	VALUES (@pidworkflow,@pidestadowf,@orden,@pdiasmax)
	
	SELECT @mensaje = ''
	SELECT @error = 0
	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
