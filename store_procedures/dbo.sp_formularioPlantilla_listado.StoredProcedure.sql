USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_formularioPlantilla_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Modificado: gdiaz 11/04/2021
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_formularioPlantilla_listado] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_formularioPlantilla_listado]
	@RutEmpresa VARCHAR(10)
AS
BEGIN	
	SET NOCOUNT ON;			
 
    SELECT
		formularioPlantilla.idFormulario,
        formularioPlantilla.nombreFormulario,
        formularioPlantilla.IdArchivo,
		COUNT(WorkflowEstadoProcesos.idWorkflow) AS NumeroRepresentantes
    FROM formularioPlantilla
    INNER JOIN Plantillas ON Plantillas.idPlantilla = formularioPlantilla.idPlantilla AND Plantillas.Eliminado = 0 AND formularioPlantilla.eliminado = 0
    LEFT JOIN WorkflowEstadoProcesos ON WorkflowEstadoProcesos.idWorkflow = Plantillas.idWF AND WorkflowEstadoProcesos.idEstadoWF IN (2,10)
    INNER JOIN PlantillasEmpresa ON PlantillasEmpresa.idPlantilla = Plantillas.idPlantilla AND PlantillasEmpresa.RutEmpresa = @RutEmpresa
    GROUP BY 
        formularioPlantilla.idFormulario,
        formularioPlantilla.nombreFormulario,
        formularioPlantilla.IdArchivo	
    /*SELECT 
		formularioPlantilla.idFormulario,
        formularioPlantilla.nombreFormulario,
        formularioPlantilla.IdArchivo
    FROM 
	    formularioPlantilla
    WHERE 
        formularioPlantilla.eliminado = 0*/
	
END
GO
