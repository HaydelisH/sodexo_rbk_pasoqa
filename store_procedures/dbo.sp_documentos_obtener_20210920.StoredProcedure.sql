USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtener_20210920]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Obtiene los datos del Contrato
-- Ejemplo:exec [sp_documentos_obtener] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtener_20210920]
	@idDocumento INT
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		IF EXISTS (SELECT idDocumento FROM Contratos WHERE (idDocumento = @idDocumento) )
			BEGIN
				SELECT	 C.idDocumento	
						,C.idEstado
						,EW.Descripcion  As Estado
						,C.DocCode
						,C.idWF	
						,WP.NombreWF	
						--,CONVERT(CHAR(10), C.FechaCreacion,105) + ' ' + CONVERT(CHAR(10), C.FechaCreacion,108) As FechaCreacion
						,dbo.fn_ZonaHorario(C.FechaCreacion) As FechaCreacion
						,C.idTipoFirma
						,F.Descripcion
						,C.idPlantilla
						,PL.Descripcion_Pl
						,C.Observacion
						,C.idProceso						
						,C.idDocumento		AS idDocumento	
						,D.documento
						,C.idTipoFirma
						,C.Observacion
						,P.Descripcion As Proceso
						,CASE C.Enviado
						
							WHEN 1 THEN 'Enviado al Gestor'
							WHEN 0 THEN 'No enviado'
											
						END AS Enviado
						,C.idTipoGeneracion
						,TG.Descripcion
						,PL.idTipoDoc
						,C.RutEmpresa
						,TD.NombreTipoDoc
						,1 as Semaforo
						,WP.NombreWF
						,FD.fichaid
				  FROM [Contratos] C
					INNER JOIN TipoGeneracion TG	ON TG.idTipoGeneracion = C.idTipoGeneracion
					INNER JOIN Plantillas PL		ON PL.idPlantilla = C.idPlantilla
					INNER JOIN TipoDocumentos TD	ON TD.idTipoDoc = PL.idTipoDoc
					INNER JOIN ContratosEstados EW	ON EW.idEstado = C.idEstado					
					INNER JOIN WorkflowProceso WP	ON C.idWF = WP.idWF
					LEFT  JOIN Documentos  D		ON C.idDocumento = D.idDocumento
					INNER JOIN FirmasTipos	F		ON F.idTipoFirma = C.idTipoFirma
					INNER JOIN Procesos	P			ON P.idProceso = C.idProceso
					LEFT JOIN fichasdocumentos FD	ON C.idDocumento = FD.documentoid
				WHERE 
					C.idDocumento = @idDocumento
				
			END 
	END
END
GO
