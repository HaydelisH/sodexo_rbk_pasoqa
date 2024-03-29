USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerClausulasPlantillas]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerClausulasPlantillas]
	@idPlantilla INT
AS
BEGIN	
    SELECT
		Clausulas.idClausula, 
		Clausulas.Titulo_Cl, 
		Clausulas.Descripcion_Cl, 
		Clausulas.Texto, 
		Categorias.idCategoria, 
		Categorias.Titulo ,
		Clausulas.Aprobado,
		Plantillas.idPlantilla,
		PlantillasClausulas.Orden,
		Plantillas.Titulo_Pl, 
		PlantillasClausulas.Encabezado,
		PlantillasClausulas.Titulo  
	FROM
		Plantillas
	INNER JOIN
		PlantillasClausulas
	ON
		Plantillas.idPlantilla = PlantillasClausulas.idPlantilla
	INNER JOIN 
		Clausulas
	ON 	
		Clausulas.idClausula = PlantillasClausulas.idClausula
	INNER JOIN 
		Categorias
	ON 	
		Clausulas.idCategoria = Categorias.idCategoria
	WHERE	
		PlantillasClausulas.idPlantilla = @idPlantilla
	AND 
		Clausulas.Eliminado = 0	
	ORDER BY 
		PlantillasClausulas.Orden
	ASC
                         
    RETURN                                                             

END
GO
