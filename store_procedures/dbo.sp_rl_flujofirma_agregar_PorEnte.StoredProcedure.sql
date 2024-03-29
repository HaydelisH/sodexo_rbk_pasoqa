USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_flujofirma_agregar_PorEnte]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 21/10/2019
-- Descripcion: Agrega flujo de firma por ente 
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_flujofirma_agregar_PorEnte 'xxxxxxx',1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_flujofirma_agregar_PorEnte]
@pnombrewfl		NVARCHAR(50),	-- descripcion flujo
@pdiasmax		NVARCHAR(50),	-- dias maximo del proceso
@pEnte			INT
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @cantidad	INT
	DECLARE @idwf		INT
	

	IF NOT EXISTS(SELECT nombrewf FROM WorkflowProceso WHERE NombreWF  = @pnombrewfl AND Eliminado = 0) 
		BEGIN
			INSERT INTO WorkflowProceso (nombrewf,diasmax,eliminado,tipoWF)
			VALUES	(@pnombrewfl,@pdiasmax,0,@pEnte)

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
