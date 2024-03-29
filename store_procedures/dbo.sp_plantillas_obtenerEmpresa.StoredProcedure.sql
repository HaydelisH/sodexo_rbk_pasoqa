USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerEmpresa]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene la Empresa de la que pertenece una Plantilla 
-- Ejemplo:exec sp_plantillas_obtenerEmpresa 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerEmpresa]
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
   
    BEGIN
		IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE (idPlantilla = @idPlantilla AND Eliminado=0))
			BEGIN
				SELECT PE.RutEmpresa FROM Plantillas P INNER JOIN PlantillasEmpresa PE ON P.idPlantilla = PE.idPlantilla
				WHERE P.Eliminado = 0 AND P.idPlantilla = @idPlantilla

				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA PLANTILLA NO EXISTE'
				SELECT @error = 1
			END	
	END
END
GO
