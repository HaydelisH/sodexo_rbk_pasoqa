USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_listado_misdocumentos_20210920]    Script Date: 1/22/2024 7:21:13 PM ******/
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
--		sp_documentos_listado_misdocumentos 1,1,10,0,0,-1,2,0,'17028706-1',1 
--		sp_documentos_listado_misdocumentos 1,1,10,0,0,-1,2,0,'26131316-2',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_listado_misdocumentos_20210920]

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
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
    SET @FirmanteLIKE = '%' + @pFirmante + '%'; 
	
	DECLARE @sqlString nvarchar(max)

	DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)
	
	----Buscar el rol del usuario
	--SELECT @rolid = rolid FROM Usuarios WHERE usuarioid = @pFirmante		
		
	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
			C.idDocumento,
			TD.NombreTipoDoc,
			P.Descripcion As Proceso,
			CE.Descripcion As Estado,
			CE.idEstado,
			FT.Descripcion As Firma,
			CONVERT(CHAR(10), C.FechaCreacion,105)	AS FechaCreacion,
			CONVERT(CHAR(10), C.FechaUltimaFirma,105)	AS FechaUltimaFirma,
			1 as Semaforo,
			WEP.DiasMax,
			C.idWF,
			ROW_NUMBER()Over(Order by C.idDocumento DESC) As RowNum,
			C.RutEmpresa,
			E.RazonSocial,
			CDV.Rut,
			PER.nombre,
			PER.appaterno,
			PER.apmaterno,
			REP.personaid as RutRep,
			REP.nombre as nombre_rep,
			REP.appaterno AS appaterno_rep,
			REP.apmaterno AS apmaterno_rep
			,ROW_NUMBER() Over(PARTITION BY C.idDocumento Order by C.idDocumento) As LineaFirmante				
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
		INNER JOIN ContratoFirmantes CF			ON CF.idDocumento = C.idDocumento AND CF.RutFirmante = @pFirmante
		LEFT JOIN ContratoFirmantes CF_R			ON CF_R.idDocumento = C.idDocumento AND C.idEstado = CF_R.idEstado AND CF_R.RutFirmante <> @pFirmante
		LEFT JOIN personas REP					ON REP.personaid = CF_R.RutFirmante' + @nl
	
	--Validar el rol
	--IF( @rolid = 2 ) --1 : Privado y 2: Público
	--BEGIN
	--	SET @sqlString += N' INNER JOIN Empleados Emp ON CDV.Rut = Emp.empleadoid AND Emp.rolid = @rolid ' + @nl
	--END

	SET @sqlString += N' WHERE C.Eliminado = 0 AND C.idEstado NOT IN (8)' + @nl

	IF (@pidDocumento != 0)
	BEGIN
		SET @sqlString += ' AND C.idDocumento = @pidDocumento ' + @nl
	END

	IF (@pidtipodocumento != 0)
	BEGIN
		SET @sqlString += ' AND TD.idTipoDoc = @pidtipodocumento' + @nl
	END	

	IF( @pidEstadoContrato > 0 )
	BEGIN
		SET @sqlString += ' AND C.idEstado = @pidEstadoContrato' + @nl
	END

	IF( @pidEstadoContrato < 0 )--Se usa cuando vienes del dashboard y quieres ver el total la otra parte se competa mas abajo
	BEGIN
		SET @sqlString += ' AND C.idEstado IN (2,3,9,10,11) AND CF.idEstado = C.idEstado' + @nl
	END
	
	IF( @pidEstadoContrato = 0 )
	BEGIN
		SET @sqlString += ' AND C.idEstado IN (2,3,9,10,11) AND CF.idEstado = C.idEstado' + @nl
	END
	
	IF (@pidTipoFirma != 0)
	BEGIN
		SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
	END
	
	IF (@pidProceso != 0)
	BEGIN
		SET @sqlString += ' AND P.idProceso = @pidProceso' + @nl
	END

	--Se usa cuando vienes del dashboard y quieres ver el total------------------------------------------------------------------
	IF ( @pidEstadoContrato < 0 )
	BEGIN
		SET @sqlString += N'
			UNION
			SELECT	
				C.idDocumento,
				TD.NombreTipoDoc,
				P.Descripcion As Proceso,
				CE.Descripcion As Estado,
				CE.idEstado,
				FT.Descripcion As Firma,
				CONVERT(CHAR(10), C.FechaCreacion,105)	AS FechaCreacion,
				CONVERT(CHAR(10), C.FechaUltimaFirma,105)	AS FechaUltimaFirma,
				1 as Semaforo,
				WEP.DiasMax,
				C.idWF,
				ROW_NUMBER()Over(Order by C.idDocumento DESC) As RowNum,
				C.RutEmpresa,
				E.RazonSocial,
				CDV.Rut,
				PER.nombre,
				PER.appaterno,
				PER.apmaterno,
				REP.personaid as RutRep,
				REP.nombre as nombre_rep,
				REP.appaterno AS appaterno_rep,
				REP.apmaterno AS apmaterno_rep
				,ROW_NUMBER() Over(PARTITION BY C.idDocumento Order by C.idDocumento) As LineaFirmante				
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
			INNER JOIN ContratoFirmantes CF			ON CF.idDocumento = C.idDocumento AND CF.RutFirmante = @pFirmante
			LEFT JOIN ContratoFirmantes CF_R			ON CF_R.idDocumento = C.idDocumento AND C.idEstado = CF_R.idEstado AND CF_R.RutFirmante <> @pFirmante
			LEFT JOIN personas REP					ON REP.personaid = CF_R.RutFirmante' + @nl
		SET @sqlString += N' WHERE C.Eliminado = 0 AND C.idEstado NOT IN (8)' + @nl

		IF (@pidDocumento != 0)
		BEGIN
			SET @sqlString += ' AND C.idDocumento = @pidDocumento ' + @nl
		END

		IF (@pidtipodocumento != 0)
		BEGIN
			SET @sqlString += ' AND TD.idTipoDoc = @pidtipodocumento' + @nl
		END	

		IF( @pidEstadoContrato > 0 )
		BEGIN
			SET @sqlString += ' AND C.idEstado = @pidEstadoContrato' + @nl
		END

		IF( @pidEstadoContrato < 0 )--Se usa cuando vienes del dashboard y quieres ver el total, esta es la otra parte
		BEGIN
			SET @sqlString += ' AND C.idEstado IN (6)' + @nl
		END
		
		IF( @pidEstadoContrato = 0 )
		BEGIN
			SET @sqlString += ' AND C.idEstado IN (2,3,9,10,11) AND CF.idEstado = C.idEstado' + @nl
		END
		
		IF (@pidTipoFirma != 0)
		BEGIN
			SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
		END
		
		IF (@pidProceso != 0)
		BEGIN
			SET @sqlString += ' AND P.idProceso = @pidProceso' + @nl
		END

	END
	--------------------------------------------------------------------
	SET @sqlString += N') 
				  SELECT 
						 idDocumento
						,NombreTipoDoc
						,Proceso
						,Estado
						,idEstado
						,Firma
						,FechaCreacion
						,FechaUltimaFirma
						,Semaforo
						,DiasMax as DiasEstadoActual
						,idWF
						,Rownum
						,RutEmpresa
						,RazonSocial
						,Rut
						,nombre
						,appaterno
						,apmaterno
						,RutRep
						,nombre_rep
						,appaterno_rep
						,apmaterno_rep
						,LineaFirmante
				  FROM DocumentosTabla
				  WHERE	RowNum BETWEEN @Pinicio AND @Pfin
				  AND LineaFirmante = 1
				  '        
				  
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
							  @pidEstadoContrato,@pidTipoFirma,@pidProceso, @pFirmante, @FirmanteLIKE, @rolid
                       	
    RETURN                                                             

END
GO
