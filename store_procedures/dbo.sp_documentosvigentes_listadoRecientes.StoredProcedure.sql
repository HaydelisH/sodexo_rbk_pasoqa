USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosvigentes_listadoRecientes]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 13/11/2018
-- Descripcion: Muestra el listado de de Documetnos Generados Recientememte
-- Ejemplo:exec [sp_documentosvigentes_listadoRecientes] 1,'12123123-1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosvigentes_listadoRecientes]
	@ptipousuarioid     INT,                        
	@pidusuario			varchar(10)
AS
BEGIN

	IF( @ptipousuarioid = 2)
		BEGIN 
			SELECT	TOP 10 C.idDocumento
				,TD.NombreTipoDoc		
				,EC.Descripcion		AS Nombre
								
			FROM [Contratos] C
				INNER JOIN Documentos D				ON D.idDocumento = C.idDocumento
				INNER JOIN ContratosEstados EC	    ON C.idEstado = EC.idEstado		
				INNER JOIN Plantillas PL			ON PL.idPlantilla = C.idPlantilla
				INNER JOIN TipoDocumentos TD	    ON PL.idTipoDoc = TD.idTipoDoc
				INNER JOIN Procesos P				ON P.idProceso = C.idProceso
				INNER JOIN ContratosEstados CE		ON CE.idEstado = C.idEstado
				INNER JOIN FirmasTipos FT			ON FT.idTipoFirma = C.idTipoFirma
				INNER JOIN Empresas E				ON E.RutEmpresa = C.RutEmpresa
				LEFT JOIN WorkflowEstadoProcesos WEP	ON C.idWF = idWorkflow AND C.idEstado =  WEP.idEstadoWF
				INNER JOIN ContratoDatosVariables CDV	ON CDV.idDocumento = C.idDocumento
				INNER JOIN Personas PER				    ON PER.personaid = CDV.Rut
				INNER JOIN ContratoFirmantes CF			ON CF.idDocumento = C.idDocumento AND CF.RutFirmante = @pidusuario
				LEFT JOIN personas REP					ON REP.personaid = CF.RutFirmante
			WHERE C.Eliminado = 0 
			AND C.idEstado IN (1,2,3,6,8,9,10,11)
			AND C.idTipoFirma = 2

			GROUP BY C.idDocumento,TD.NombreTipoDoc,EC.Descripcion ORDER BY C.idDocumento DESC
		END
	ELSE
		BEGIN 
			With DocumentosTabla
			as 
			(
				SELECT	TOP 10
					C.idDocumento,
					TD.NombreTipoDoc		
					,EC.Descripcion	--	AS Nombre
				FROM [Contratos] C
					INNER JOIN Documentos D				ON D.idDocumento = C.idDocumento
					INNER JOIN ContratoFirmantes CF		 ON C.idDocumento = CF.idDocumento AND CF.RutFirmante = @pidusuario
					INNER JOIN Plantillas PL			 ON PL.idPlantilla = C.idPlantilla
					INNER JOIN TipoDocumentos TD	     ON PL.idTipoDoc = TD.idTipoDoc
					INNER JOIN ContratosEstados EC	     ON C.idEstado = EC.idEstado		
					INNER JOIN ContratoDatosVariables CDV	ON CDV.idDocumento = C.idDocumento
					INNER JOIN accesoxusuarioccosto     ACC	
					ON ACC.empresaid = C.RutEmpresa 
					AND ACC.centrocostoid = CDV.CentroCosto 
					AND ACC.lugarpagoid = CDV.lugarpagoid 
					--AND ACC.departamentoid = CDV.departamentoid 
					AND ACC.usuarioid = @pidusuario 
					--INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.centrocostoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid AND ACC.lugarpagoid = CDV.lugarpagoid
					--INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.lugarpagoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid AND ACC.centrocostoid = CDV.lugarpagoid
					INNER JOIN tiposdocumentosxperfil TDP ON TD.idTipoDoc = TDP.idtipodoc 
															AND TDP.tipousuarioid = @ptipousuarioid
					WHERE C.idTipoFirma = 2 AND (CF.idEstado = C.idEstado OR C.idEstado = 6)
					AND C.idEstado NOT IN(1, 8)
			--		GROUP BY C.idDocumento,TD.NombreTipoDoc,EC.Descripcion ORDER BY C.idDocumento DESC
				--UNION
				--SELECT	
				--	C.idDocumento,
				--	TD.NombreTipoDoc		
				--	,EC.Descripcion --		AS Nombre
				--FROM [Contratos] C
				--	INNER JOIN Documentos D				ON D.idDocumento = C.idDocumento
				--	INNER JOIN ContratoFirmantes CF		 ON C.idDocumento = CF.idDocumento AND CF.RutFirmante = @pidusuario
				--	INNER JOIN Plantillas PL			 ON PL.idPlantilla = C.idPlantilla
				--	INNER JOIN TipoDocumentos TD	     ON PL.idTipoDoc = TD.idTipoDoc
				--	INNER JOIN ContratosEstados EC	     ON C.idEstado = EC.idEstado		
				--	INNER JOIN ContratoDatosVariables CDV	ON CDV.idDocumento = C.idDocumento
				--	INNER JOIN accesoxusuarioccosto     ACC	
				--	ON ACC.empresaid = C.RutEmpresa 
				--	AND ACC.centrocostoid = CDV.CentroCosto 
				--	AND ACC.lugarpagoid = CDV.lugarpagoid 
				--	--AND ACC.departamentoid = CDV.departamentoid 
				--	AND ACC.usuarioid = @pidusuario 
				--	--INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.centrocostoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid AND ACC.lugarpagoid = CDV.lugarpagoid
				--	--INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.lugarpagoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid AND ACC.centrocostoid = CDV.lugarpagoid
				--	INNER JOIN tiposdocumentosxperfil TDP ON TD.idTipoDoc = TDP.idtipodoc 
				--											AND TDP.tipousuarioid = @ptipousuarioid
				--	WHERE C.idTipoFirma = 2 AND C.idEstado = 6
				--	AND C.idEstado NOT IN(1, 8) 
				
				GROUP BY C.idDocumento,TD.NombreTipoDoc,EC.Descripcion --ORDER BY C.idDocumento DESC
			)
			SELECT idDocumento,
						 NombreTipoDoc,
						Descripcion AS Nombre
			FROM DocumentosTabla
			--	  WHERE	RowNum BETWEEN @Pinicio AND @Pfin
			--	  AND LineaFirmante = 1
			ORDER BY idDocumento DESC
		END
    RETURN                                            
                                                        

END
GO
