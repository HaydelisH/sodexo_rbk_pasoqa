USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_modificar_estado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 11/06/2018
-- Descripcion: Modificar flujo de firma
-- Ejemplo:exec sp_flujofirma_modificar_estado 1,1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_flujofirma_modificar_estado]
@pidworkflow	INT,	-- id workflow
@pidestadowf	INT,	-- estado del documento
@pdiasmax		INT		-- dias maximo

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)

	UPDATE WorkflowEstadoProcesos SET
	DiasMax = @pdiasmax
	WHERE idWorkflow = @pidworkflow
	AND idEstadoWF = @pidestadowf
	
	SELECT @mensaje = ''
	SELECT @error = 0
	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
