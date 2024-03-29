USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_modificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 12/06/2018
-- Descripcion: Modificar información del id del flujo de firma
-- Ejemplo:exec sp_flujofirma_modificar 1,'flujo',3
-- =============================================
CREATE PROCEDURE [dbo].[sp_flujofirma_modificar]
@pidwf			INT,			-- id flujo
@pnombrewf		NVARCHAR(50),	-- nombre flujo
@pdiasmax		INT				-- dias maximo

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @xidwf		INT


	IF EXISTS(SELECT nombrewf FROM WorkflowProceso WHERE idwf = @pidwf AND Eliminado = 0) 
		BEGIN
			
			SELECT @xidwf = idwf FROM WorkflowProceso WHERE NombreWF = @pnombrewf
			IF (ISNULL(@xidwf,0) = @pidwf OR ISNULL(@xidwf,0) = 0)
				BEGIN
					UPDATE Workflowproceso SET
					nombrewf	= @pnombrewf,
					DiasMax		= @pdiasmax
					WHERE idWF  = @pidwf
					
					SELECT @mensaje = ''
					SELECT @error = 0
				END
			ELSE
				BEGIN
					SET @error	= 1
					SET @mensaje= 'Nombre de flujo de firma ' + @pnombrewf + ' ya existe '		
				END
		END 
	ELSE
		BEGIN
			SET @error	= 1
			SET @mensaje= 'Identificador del flujo no Existe '		
		END
	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
