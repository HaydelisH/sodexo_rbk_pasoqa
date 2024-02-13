USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerClausulasCategorias]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene las Clausulas disponibles de la Categoria de la que pertenece la Plantilla
-- Ejemplo:exec sp_plantillas_obtenerClausulasCategorias 1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerClausulasCategorias]
	@idPlantilla INT,
	@idCategoria INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
 	
	  SELECT 
		PlantillasClausulas.idPlantilla, 
		Clausulas.idClausula, 
		Clausulas.Titulo_Cl, 
		Clausulas.Descripcion_Cl, 
		Clausulas.Texto, 
		Categorias.idCategoria, 
		Categorias.Titulo, 
		Clausulas.RutModificador, 
		Clausulas.Aprobado  
	  FROM 
		Clausulas
	  INNER JOIN Categorias ON Categorias.idCategoria = Clausulas.idCategoria
	  LEFT JOIN PlantillasClausulas on Clausulas.idclausula = PlantillasClausulas.idclausula and PlantillasClausulas.idPlantilla = @idPlantilla
	  where PlantillasClausulas.idPlantilla is null AND
	  Clausulas.idCategoria = @idCategoria AND Clausulas.Eliminado = 0 
	  order by PlantillasClausulas.Orden ASC


END
GO
