USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 08/06/2018
-- Descripcion: Agrega flujo de firma
-- Ejemplo:exec sp_flujofirma_agregar 'xxxxxxx',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_flujofirma_agregar]
@pnombrewfl		NVARCHAR(50),	-- descripcion flujo
@pdiasmax		NVARCHAR(50)	-- dias maximo del proceso
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @cantidad	INT
	DECLARE @idwf		INT
	

	IF NOT EXISTS(SELECT nombrewf FROM WorkflowProceso WHERE NombreWF  = @pnombrewfl AND Eliminado = 0) 
		BEGIN
			INSERT INTO WorkflowProceso (nombrewf,diasmax,eliminado)
			VALUES	(@pnombrewfl,@pdiasmax,0)

			SET @idwf = @@IDENTITY
			
			SELECT @idwf AS idwf
			
			RETURN
		END
	ELSE
		BEGIN
			SET @error	= 1
			SET @mensaje= 'Nombre flujo de firma ya existe'
		END
	

	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
