USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerEncabezadosDocumento]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 11-04-2019
-- Descripcion: Obtiene los datos variables de un Contrato 
-- Ejemplo:exec [sp_documentos_obtenerVariablesDocumento] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerEncabezadosDocumento]

AS
BEGIN	
	SET NOCOUNT ON;			
   
	SELECT	TOP 1	
		 C.idDocumento
		,CE.Descripcion  As Estado
		,WP.NombreWF As FlujoFirmas
		,CONVERT(CHAR(10), C.FechaCreacion,105) As FechaCreacion
		,CONVERT(CHAR(10), C.FechaCreacion,105) As Fecha
		,F.Descripcion As TipoFirma	
		,P.Descripcion As Proceso
		,TD.NombreTipoDoc As TipoDocumento	
	FROM [Contratos] C
		INNER JOIN Plantillas PL		ON PL.idPlantilla = C.idPlantilla
		INNER JOIN TipoDocumentos TD	ON TD.idTipoDoc = PL.idTipoDoc
		INNER JOIN ContratosEstados CE	ON CE.idEstado = C.idEstado	
		INNER JOIN WorkflowProceso WP	ON WP.idWF = C.idWF				
		INNER JOIN FirmasTipos	F		ON F.idTipoFirma = C.idTipoFirma
		INNER JOIN Procesos	P			ON P.idProceso = C.idProceso
			
END
GO
