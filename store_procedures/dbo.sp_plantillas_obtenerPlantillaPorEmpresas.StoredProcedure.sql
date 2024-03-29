USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerPlantillaPorEmpresas]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 07/05/2019
-- Descripcion: Consulta si Plantilla esta asociada a mas de una empresa
-- Ejemplo:exec sp_plantillas_obtenerPlantillaPorEmpresas 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerPlantillaPorEmpresas]
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
		IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE (idPlantilla=@idPlantilla AND Eliminado=0))
			BEGIN
				SELECT 
					@total = COUNT(*)
				FROM Plantillas P
				INNER JOIN PlantillasEmpresa PE ON P.idPlantilla = PE.idPlantilla
				WHERE 
					P.idPlantilla = @idPlantilla
				
				SELECT @total As Total 
				RETURN
                  
			END 
	END
END
GO
