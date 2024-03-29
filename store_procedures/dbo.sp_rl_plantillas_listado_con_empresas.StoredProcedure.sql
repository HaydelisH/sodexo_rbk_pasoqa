USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_plantillas_listado_con_empresas]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Cristian Soto
-- Creado el: 28/08/2020
-- Descripcion: Obtiene las Plantillas disponibles incluyendo la empresa
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_plantillas_listado_con_empresas
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_plantillas_listado_con_empresas]
AS
BEGIN
    SELECT
 	Plantillas.idPlantilla,
	Plantillas.Titulo_Pl,
	Plantillas.Descripcion_Pl,
	WorkflowProceso.NombreWF,
	TipoDocumentos.NombreTipoDoc,
	Categorias.Titulo,
	Plantillas.Aprobado,
	PlantillasEmpresa.RutEmpresa,
	Empresas.RazonSocial,
	Plantillas.idTipoGestor,
	TipoGestor.Nombre
FROM PlantillasEmpresa
INNER JOIN PLantillas ON PlantillasEmpresa.idPlantilla = Plantillas.idPlantilla
LEFT JOIN Empresas ON Empresas.RutEmpresa = PlantillasEmpresa.RutEmpresa
INNER JOIN WorkflowProceso ON Plantillas.idWF = WorkflowProceso.idWF 
INNER JOIN TipoDocumentos ON Plantillas.idTipoDoc = TipoDocumentos.idTipoDoc
INNER JOIN Categorias ON Plantillas.idCategoria = Categorias.idCategoria
INNER JOIN TipoGestor ON TipoGestor.idTipoGestor = Plantillas.idTipoGestor
WHERE	Plantillas.Eliminado = 0
AND Plantillas.tipogeneracion = 1
                         
    RETURN                                                             

END
GO
