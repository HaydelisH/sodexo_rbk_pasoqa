USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerPorAprobar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Obtiene los datos del Contrato solo con algunos datos 
-- Ejemplo:exec [sp_documentos_obtenerPorAprobar] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerPorAprobar]
	@idDocumento INT
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		IF EXISTS (SELECT idDocumento FROM Contratos WHERE (idDocumento = @idDocumento) )
			BEGIN
				SELECT	 C.idDocumento	
						,TD.NombreTipoDoc
				  FROM [Contratos] C
					INNER JOIN TipoGeneracion TG	ON TG.idTipoGeneracion = C.idTipoGeneracion
					INNER JOIN Plantillas PL		ON PL.idPlantilla = C.idPlantilla
					INNER JOIN TipoDocumentos TD	ON TD.idTipoDoc = PL.idTipoDoc
					INNER JOIN ContratosEstados EW	ON EW.idEstado = C.idEstado					
					INNER JOIN WorkflowProceso WP	ON C.idWF = WP.idWF
					INNER JOIN Documentos  D		ON C.idDocumento = D.idDocumento
					INNER JOIN FirmasTipos	F		ON F.idTipoFirma = C.idTipoFirma
					INNER JOIN Procesos	P			ON P.idProceso = C.idProceso
				WHERE 
					C.idDocumento = @idDocumento
			END 
	END
END
GO
