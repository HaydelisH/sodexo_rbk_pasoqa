USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerDatosClausulasPlantilla]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 07/08/2018
-- Descripcion: Obtiene las Clausulas de una Plantilla en la tabla PlantillasClausulas
-- Ejemplo:exec sp_plantillas_obtenerDatosClausulaPlantilla 1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerDatosClausulasPlantilla]
	@idClausula INT,
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
 	
	SELECT idClausula, idPlantilla, Orden, Encabezado, Titulo FROM PlantillasClausulas WHERE idClausula= @idClausula AND idPlantilla= @idPlantilla


END
GO
