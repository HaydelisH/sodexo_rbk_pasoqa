USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_listado_PorTiposDocumentos_md]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_documentos_reportes] '', 2, 1, 10  
-- STRING ''
-- FECHA NULL	  
-- INT 0
--		exec [sp_documentos_listado] '', 1, 1, 10, 0, '1' , '' , '' , '', '', '', 1  -- TODOS
--		exec [sp_documentos_listado] '', 1, 1, 10, 0, '2' , '' , '' , '', '', '', 1  -- TODOS
--		exec [sp_documentos_listado] '', 2, 1, 10, 66, '', '' , '' , 1  -- X Contrato			                       
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, 'Contrato', '', '' , 1 -- X TipoDocumento
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, '', 'Gama ', '' , 1 -- X Empresa
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, '', '', 'Empori' , 1 -- X CLIENTE
CREATE PROCEDURE [dbo].[sp_documentos_listado_PorTiposDocumentos_md]

@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
@pagina					INT,	-- numero de pagina
@decuantos          DECIMAL,	-- total pagina
@pidDocumento			INT,	-- Id Documento
@pidtipodocumento		INT,	-- TipoDocumento
@pidEstadoContrato		INT,	-- Estado del Contrato
@pidTipoFirma			INT,	-- 1 = Manual y 2 = Elctronico
@pidProceso				INT,	-- Id del Proceso
@pFirmante		varchar(100),	-- Firmante del Contrato
@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
		
AS
BEGIN
	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @FirmanteLIKE	VARCHAR(100)									  
               
    SET @FirmanteLIKE = '%' + @pFirmante + '%'; 

	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
	
	DECLARE @sqlString nvarchar(max)
	
	DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)

	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
			TD.idTipoDoc,
			TD.NombreTipoDoc
	FROM [Contratos] C
			
			INNER JOIN Documentos D				ON D.idDocumento = C.idDocumento
			INNER JOIN Plantillas PL			ON PL.idPlantilla = C.idPlantilla
			INNER JOIN TipoDocumentos TD	    ON PL.idTipoDoc = TD.idTipoDoc
			INNER JOIN Procesos P				ON P.idProceso = C.idProceso
			INNER JOIN ContratosEstados CE		ON CE.idEstado = C.idEstado
			INNER JOIN FirmasTipos FT			ON FT.idTipoFirma = C.idTipoFirma
			INNER JOIN Empresas E				ON E.RutEmpresa = C.RutEmpresa
			LEFT JOIN WorkflowEstadoProcesos WEP	ON C.idWF = idWorkflow AND C.idEstado =  WEP.idEstadoWF
			INNER JOIN ContratoDatosVariables CDV	ON CDV.idDocumento = C.idDocumento
			INNER JOIN Personas PER				    ON PER.personaid = CDV.Rut
			--INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.centrocostoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid AND ACC.lugarpagoid = CDV.lugarpagoid
			--INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.lugarpagoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid AND ACC.centrocostoid = CDV.lugarpagoid
			--INNER JOIN tiposdocumentosxperfil TAPP	ON TAPP.idtipodoc = PL.idTipoDoc AND TAPP.tipousuarioid = @ptipousuarioid
			LEFT JOIN ContratoFirmantes CF			ON CF.idDocumento = C.idDocumento AND CF.RutEmpresa = C.RutEmpresa AND C.idEstado = CF.idEstado
			LEFT JOIN personas REP					ON REP.personaid = CF.RutFirmante' + @nl
																			
	SET @sqlString += N' WHERE C.Eliminado = 0 ' + @nl
	
	IF (@pFirmante != '')
	BEGIN
		SET @sqlString += ' AND ( PER.personaid LIKE @FirmanteLIKE OR PER.nombre LIKE @FirmanteLIKE OR PER.appaterno LIKE @FirmanteLIKE OR PER.apmaterno LIKE @FirmanteLIKE OR 
								REP.personaid LIKE @FirmanteLIKE OR REP.nombre LIKE @FirmanteLIKE OR REP.appaterno LIKE @FirmanteLIKE OR REP.apmaterno LIKE @FirmanteLIKE  ) ' + @nl
	END
																						
		
	SET @sqlString += N' GROUP BY TD.idTipoDoc, TD.NombreTipoDoc ' + @nl

	SET @sqlString += N') 
				  SELECT 
						idTipoDoc,
						NombreTipoDoc
				  FROM DocumentosTabla'        
				  
		DECLARE @Parametros nvarchar(max)
		
		SET @Parametros =  N'@ptipousuarioid INT, @Pinicio INT,
							 @Pfin INT, @PidDocumento INT, @pidtipodocumento INT,
							 @pidEstadoContrato INT, @pidTipoFirma INT,
							 @pidProceso INT, @pFirmante VARCHAR(100), @FirmanteLIKE VARCHAR(100), @rolid INT'
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @ptipousuarioid , @Pinicio , @Pfin, @PidDocumento, @pidtipodocumento,
							  @pidEstadoContrato,@pidTipoFirma,@pidProceso, @pFirmante,@FirmanteLIKE, @rolid 
                       	
    RETURN                                                             

END
GO
