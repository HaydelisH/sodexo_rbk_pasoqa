USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerPlantillasEmpresasTipoContrato]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene las Plantillas asociadas a una Empresa 
-- Ejemplo:exec sp_plantillas_obtenerPlantillasEmpresas 'xxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerPlantillasEmpresasTipoContrato]
	@RutEmpresa VARCHAR (10),
	@idTipoDoc INT
AS
BEGIN
	
    SELECT
		PlantillasEmpresa.idPlantilla,
		Plantillas.Descripcion_Pl,
		CASE Plantillas.Aprobado			
			WHEN 0 THEN 'disabled'
			WHEN 1 THEN ''
		END AS Aprobado
	FROM
		PLantillasEmpresa
	INNER JOIN
		Plantillas
	ON
		Plantillas.idPlantilla = PlantillasEmpresa.idPlantilla
        AND Plantillas.idPlantilla NOT IN (
            SELECT idPlantilla FROM formularioPlantilla
        )
	INNER JOIN
		WorkflowProceso
	ON
		Plantillas.idWF= WorkflowProceso.idWF 
	INNER JOIN 
		TipoDocumentos
	ON 	
		Plantillas.idTipoDoc = TipoDocumentos.idTipoDoc
	INNER JOIN 
		Empresas
	ON 	
		PlantillasEmpresa.RutEmpresa = Empresas.RutEmpresa
	INNER JOIN 
		Categorias
	ON
		Plantillas.idCategoria = Categorias.idCategoria
	WHERE	
		PlantillasEmpresa.RutEmpresa = @RutEmpresa
	AND
		Plantillas.Eliminado = 0
	AND		
		Plantillas.idTipoDoc = @idTipoDoc

                         
    RETURN                                                             
                                               

END
GO
