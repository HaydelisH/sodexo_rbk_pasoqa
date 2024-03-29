USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_listado_20210928_HH]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtiene las Plantillas disponibles
-- Ejemplo:exec sp_plantillas_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_listado_20210928_HH]
AS
BEGIN
    SELECT
 	Plantillas.idPlantilla,
	Plantillas.Titulo_Pl,
	Plantillas.Descripcion_Pl,
	WorkflowProceso.NombreWF,
	TipoDocumentos.NombreTipoDoc,
	Categorias.Titulo,
	Plantillas.Aprobado
FROM
	PLantillas
INNER JOIN
	WorkflowProceso
ON
	Plantillas.idWF = WorkflowProceso.idWF 
INNER JOIN 
	TipoDocumentos
ON 	
	Plantillas.idTipoDoc = TipoDocumentos.idTipoDoc

INNER JOIN 
	Categorias
ON
	Plantillas.idCategoria = Categorias.idCategoria
WHERE	
	Plantillas.Eliminado = 0
                         
    RETURN                                                             

END
GO
