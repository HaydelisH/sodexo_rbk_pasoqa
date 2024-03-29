USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerPlantillasEmpresas]    Script Date: 1/22/2024 7:21:15 PM ******/
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
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerPlantillasEmpresas]
	@RutEmpresa VARCHAR (10)
AS
BEGIN
	
    SELECT
 		Plantillas.idPlantilla,
		Plantillas.Titulo_Pl,
		Plantillas.Descripcion_Pl,
		WorkflowProceso.NombreWF,
		TipoDocumentos.NombreTipoDoc,
		Empresas.RazonSocial,
		Categorias.Titulo,
		Plantillas.Aprobado
	FROM
		PLantillas
	INNER JOIN
		PlantillasEmpresa PE 
	ON 
		PE.idPlantilla = Plantillas.idPlantilla
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
		PE.RutEmpresa = Empresas.RutEmpresa
	INNER JOIN 
		Categorias
	ON
		Plantillas.idCategoria = Categorias.idCategoria
	WHERE	
		PE.RutEmpresa = @RutEmpresa
	AND
		Plantillas.Eliminado = 0
                         
    RETURN                                                             

END
GO
