USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_eliminarClausula]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Quita una Clausula de una Plantilla 
-- Ejemplo:exec sp_plantillas_eliminarClausula 1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_eliminarClausula]
	@idPlantilla INT,
	@idClausula INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here

	IF EXISTS ( SELECT idPlantilla FROM PlantillasClausulas  WHERE idPlantilla = @idPlantilla AND idClausula = @idClausula)
		BEGIN				
			DELETE FROM PlantillasClausulas 
			WHERE
			idPlantilla = @idPlantilla AND idClausula = @idClausula
			
			UPDATE Plantillas SET Aprobado = 0 WHERE idPlantilla = @idPlantilla
			 
			SELECT @lmensaje = ''
			SELECT @error = 0
		END 
	ELSE
		BEGIN
			SELECT @lmensaje = 'ESTA PLANTILLA NO CONTIENE ESTA CLAUSULA'
			SELECT @error = 1
		END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
