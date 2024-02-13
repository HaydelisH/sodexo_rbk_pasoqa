USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 13/06/2018
-- Descripcion: Elimina flujo de firma logicamente
-- Ejemplo:exec sp_flujofirma_eliminar 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_flujofirma_eliminar]
@pidwf			INT		-- id flujo

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)

	IF EXISTS(SELECT idwf FROM WorkflowProceso WHERE  idwf = @pidwf AND Eliminado = 0)
		BEGIN 
            IF NOT EXISTS(SELECT idPlantilla FROM Plantillas WHERE idWF = @pidwf AND Eliminado = 0)
                BEGIN
                    UPDATE  WorkflowProceso
                    SET Eliminado = 1
                    WHERE idwf =@pidwf 
                    
                    SELECT @mensaje = ''
                    SELECT @error = 0
                END
            ELSE
                BEGIN
                    SET @error	= 1
                    SET @mensaje= 'No se puede eliminar, ya que existen Plantillas asociadas a este flujo.'			
                END
		END 
	ELSE
		BEGIN
			SET @error	= 1
			SET @mensaje= 'Flujo de firma ya fué eliminado '			
		END
	
		
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
