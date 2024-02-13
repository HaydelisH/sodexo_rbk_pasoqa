USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_eliminar_estado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 12/06/2018
-- Descripcion: Elimina estado del flujo de firma
-- Ejemplo:exec sp_flujofirma_eliminar_estado 1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_flujofirma_eliminar_estado]
@pidworkflow	INT,	-- id workflow
@pidestadowf	INT 	-- id estado 

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)

	IF EXISTS(SELECT idWorkflow FROM WorkflowEstadoProcesos WHERE  idWorkflow = @pidworkflow AND idEstadoWF = @pidestadowf)
		BEGIN 
			DELETE FROM WorkflowEstadoProcesos WHERE idWorkflow = @pidworkflow AND idEstadoWF = @pidestadowf
			
			UPDATE  tbl
			SET        Orden = rowno
			FROM (
            SELECT ORDEN,
            ROW_NUMBER()Over(Order by orden) AS rowno
            FROM       WorkflowEstadoProcesos
            WHERE idWorkflow = @pidworkflow
			) AS tbl
			
			SELECT @mensaje = ''
			SELECT @error = 0
		END 
	ELSE
		BEGIN
			SET @error	= 1
			SET @mensaje= 'Estado a eliminar ya no existe '			
		END
	
	SELECT @mensaje = ''
	SELECT @error = 0
	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
