USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_eliminarPlantillaEmpresa]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05-05-2019
-- Descripcion: Elimina una Plantilla de una empresa
-- Ejemplo:exec sp_plantillas_eliminarPlantillaEmpresa
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_eliminarPlantillaEmpresa]
	@pRutEmpresa VARCHAR(10),
	@idPlantilla INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF EXISTS ( SELECT idPlantilla FROM PlantillasEmpresa WHERE idPlantilla = @idPlantilla AND RutEmpresa = @pRutEmpresa )
		BEGIN
			DELETE FROM PlantillasEmpresa WHERE idPlantilla = @idPlantilla AND RutEmpresa = @pRutEmpresa 
			
			SELECT @lmensaje = ''
			SELECT @error = 0
		END
	ELSE
		BEGIN
			SELECT @lmensaje = 'ESTA PLANILLA NO PERTENECE A ESTA EMPRESA'
			SELECT @error = 1
		END 
     
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
