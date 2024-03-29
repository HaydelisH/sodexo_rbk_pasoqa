USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_revisionListadoActor2_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 -- Todo
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,212,'','',0,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 -- idDocuemnto
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'26131316-2','',0,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 --Rut de creador
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','Haydelis',0,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 --Nombre del creador
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',10016,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 -- idPlantilla
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'FOOD',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 --Descripcion de plantilla
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',83969,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1-- Centrocostoid
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'Mutual','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 -- Nombre de centro de costo
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','Casino',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 -- Nombre de casino
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',4,'',0,'','','',-1,NULL,NULL,0,0,0,1 -- Codigo de cargo
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'"A"',0,'','','',-1,NULL,NULL,0,0,0,1 -- Cargo
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',1,'','','',-1,NULL,NULL,0,0,0,1 -- idProceso
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',0,'76040054-8','','',-1,NULL,NULL,0,0,0,1 -- RutEmpresa
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',0,'','12634720-0','',-1,NULL,NULL,0,0,0,1 -- RutEmpleado
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',0,'','','Gustavo',-1,NULL,NULL,0,0,0,1 -- NombreEmpleado
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 -- Estado de gestion
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,1,0,1 -- Estado de contrato
-- [sp_revisionListadoActor2_obtener] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,2,1 --Tipo de firma
-- =============================================
CREATE PROCEDURE [dbo].[sp_revisionListadoActor2_obtener]
	--@pusuarioid				VARCHAR(10),
	@pagina					INT,	-- numero de pagina
	@decuantos				DECIMAL,	-- total pagina
	@pidDocumento			INT,	-- Id Documento
	--@prutcreador			VARCHAR(10),
	--@pnombrecreador			VARCHAR(50),
	--@pidPlantilla			INT,
	--@pDescripcion_Pl		VARCHAR(50),
	--@pcentrocostoid			INT, 
	--@pNombreCentroCosto		VARCHAR(50), 
	--@pNombreCasino			VARCHAR(50),
	--@pCodCargo				INT,
	--@pCargo					VARCHAR(50),
	--@pidProceso				INT,
	--@pEmpresa				VARCHAR(10),
	@pRutEmpleado			VARCHAR(10),
	@pNombreEmpleado		VARCHAR(50),
	--@pEnviado				INT,
	@fechaInicio			DATE,		-- Fecha inicio
	@fechaFin				DATE,		-- Fecha fin 
	@pidEstadoGestion		INT,	
	--@pidEstadoContrato		INT,	-- Estado del Contrato
	--@pidTipoFirma			INT,	-- 1 = Manual y 2 = Elctronico
	@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
	@pusuarioid				VARCHAR(10),
	@debug					tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	
	SET NOCOUNT ON;
	
	DECLARE @nl   char(2) = char(13) + char(10)	
	--DECLARE @pnombrecreadorLIKE VARCHAR(50)		
	--DECLARE @pDescripcion_PlLIKE VARCHAR(50)
	--DECLARE @pNombreCentroCostoLIKE VARCHAR(50)
	--DECLARE @pNombreCasinoLIKE VARCHAR(50)
	--DECLARE @pCargoLIKE VARCHAR(50)
	DECLARE @pNombreEmpleadoLIKE VARCHAR(50)
	DECLARE @sqlString nvarchar(max)
    DECLARE @vdecimal DECIMAL (9,2)
    DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT

	--SET @pnombrecreadorLIKE = '%' + @pnombrecreador + '%'; 
	--SET @pDescripcion_PlLIKE = '%' + @pDescripcion_Pl + '%'; 
	--SET @pNombreCentroCostoLIKE = '%' + @pNombreCentroCosto + '%'; 
	--SET @pNombreCasinoLIKE = '%' + @pNombreCasino + '%'; 
	--SET @pCargoLIKE= '%' + @pCargo + '%'; 
	SET @pNombreEmpleadoLIKE = '%' + @pNombreEmpleado + '%'; 
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
		
	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT
			Contratos.idDocumento,
			empleadoFormulario.empleadoid As RutEmpleado,
			ISNULL(	Personas.nombre,'''') + '' '' + ISNULL(Personas.appaterno,'''')+ '' ''+ ISNULL(Personas.apmaterno,'''') As NombreEmpleado,
            CONVERT(CHAR(10), Contratos.FechaUltimaFirma,105)	AS FechaUltimaFirma,
            estadoFormulario.nombre AS EstadoGestion,
            CONVERT(CHAR(10), empleadoFormulario.fechaCarga,105)	AS fechaCarga,
			--CREADOR.personaid, 
			--ISNULL( CREADOR.nombre,'''')+ '' ''+ ISNULL(CREADOR.appaterno,'''') + '' '' + ISNULL(CREADOR.apmaterno,'''') As NombreCreador,
			--PL.idPlantilla, 
			--PL.Descripcion_Pl,
			--CC.centrocostoid,
			--CC.nombrecentrocosto,
			--CDV.NombreCasino,
			--CDV.CodCargo, 
			--CAR.Descripcion As Cargo,
			--CONVERT(CHAR(10), C.FechaCreacion,105)	AS FechaCreacion,
			--P.idProceso, 
			--P.Descripcion, 
			--C.RutEmpresa,
			--E.RazonSocial,
			--CASE C.Enviado WHEN 1 THEN ''Si''
			--	ELSE ''No'' END As Enviado,
			--EGI.idEstadoGestion,
			--EGI.Descripcion As EstadoGestion,
			--CASE  WHEN EGI.idEstadoGestion IS NULL THEN 10
			--    ELSE  EGI.idEstadoGestion END as idEstadoGestion,
			--CASE  WHEN EGI.Descripcion IS NULL THEN ''Sin gestion''
			--    ELSE  EGI.Descripcion END as EstadoGestion,
			--CE.Descripcion As EstadoContrato,
			--FT.Descripcion As Firma,
			--CONVERT(CHAR(10), GI.FechaSolicitud,105)	AS FechaSolicitud,
			--GI.FechaPayroll,
			--GI.Responsable,
			--CONVERT(CHAR(10), GI.FechaDT,105)	AS FechaDT,
            empleadoFormulario.empleadoFormularioid,
			Contratos.RutEmpresa,
			(
				SELECT TOP(1) 
					personas.nombre
				FROM datosFormulario
				INNER JOIN personas ON personas.personaid = datosFormulario.usuarioid
				WHERE 
					datosFormulario.empleadoFormularioid = empleadoFormulario.empleadoFormularioid
					AND estadoFormularioid IN (4,5)
				ORDER BY fecha DESC) AS nombreUsuario,
			ROW_NUMBER()Over(Order by empleadoFormulario.empleadoFormularioid DESC) As RowNum
		FROM empleadoFormulario
        LEFT JOIN Contratos ON Contratos.idDocumento = empleadoFormulario.idDocumento
		INNER JOIN Personas ON Personas.personaid = empleadoFormulario.empleadoid
        INNER JOIN estadoFormulario ON estadoFormulario.estadoFormularioid = empleadoFormulario.estadoFormularioid
		--INNER JOIN accesodocxperfilempresas ON accesodocxperfilempresas.RutEmpresa = Contratos.RutEmpresa
		--	AND accesodocxperfilempresas.tipousuarioid = @ptipousuarioid
		--	AND Contratos.Eliminado = 0
		INNER JOIN accesoxusuarioccosto     ACC	ON ACC.empresaid = Contratos.RutEmpresa 
		--AND ACC.centrocostoid = CDV.CentroCosto 
		AND ACC.usuarioid = @pusuarioid 
		AND Contratos.Eliminado = 0

		--INNER JOIN Documentos D				ON D.idDocumento = C.idDocumento
		--INNER JOIN Plantillas PL			ON PL.idPlantilla = C.idPlantilla
		--INNER JOIN TipoDocumentos TD	    ON PL.idTipoDoc = TD.idTipoDoc
		--INNER JOIN Procesos P				ON P.idProceso = C.idProceso
		--INNER JOIN ContratosEstados CE		ON CE.idEstado = C.idEstado
		--INNER JOIN FirmasTipos FT			ON FT.idTipoFirma = C.idTipoFirma
		--INNER JOIN Empresas E				ON E.RutEmpresa = C.RutEmpresa
		--LEFT JOIN WorkflowEstadoProcesos WEP	ON C.idWF = idWorkflow AND C.idEstado =  WEP.idEstadoWF
		--INNER JOIN ContratoDatosVariables CDV	ON CDV.idDocumento = C.idDocumento
		--INNER JOIN Personas PER				    ON PER.personaid = CDV.Rut
		--INNER JOIN accesoxusuarioccosto     ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.centrocostoid = CDV.CentroCosto AND ACC.lugarpagoid = CDV.lugarpagoid --AND ACC.usuarioid = @pusuarioid
		--INNER JOIN tiposdocumentosxperfil TAPP	ON TAPP.idtipodoc = PL.idPlantilla --AND TAPP.tipousuarioid = @ptipousuarioid
		--LEFT JOIN ContratoFirmantes CF			ON CF.idDocumento = C.idDocumento AND CF.RutEmpresa = C.RutEmpresa AND C.idEstado = CF.idEstado
		--LEFT JOIN personas REP					ON REP.personaid = CF.RutFirmante
		--LEFT JOIN fichasdocumentos FD	ON C.idDocumento = FD.documentoid 
		--LEFT JOIN personas CREADOR	ON C.usuarioid = CREADOR.personaid
		--INNER JOIN CentrosCosto CC	ON CDV.CentroCosto = CC.centrocostoid 
		--LEFT JOIN GestionInterna GI ON C.idDocumento = GI.idDocumento
		--LEFT JOIN EstadosGestionInterna EGI ON GI.idEstadoGestion = EGI.idEstadoGestion
		--LEFT JOIN Cargos CAR ON CDV.CodCargo = CAR.idCargo' + @nl
																				
		--SET @sqlString += N' WHERE C.Eliminado = 0 ' + @nl
        SET @sqlString += N' WHERE empleadoFormulario.estadoFormularioid IN (4,5) ' + @nl
			
		IF (@pidDocumento != 0)
		BEGIN
			SET @sqlString += ' AND Contratos.idDocumento = @pidDocumento ' + @nl
		END

		/*IF (@prutcreador != '')
		BEGIN
			SET @sqlString += ' AND C.usuarioid = @pusuarioid ' + @nl
		END*/
	
		--IF (@pnombrecreadorLIKE != '')
		--BEGIN
		--	SET @sqlString += ' AND NombreCreador LIKE @pnombrecreadorLIKE ' + @nl
		--END
		/*IF (@pnombrecreador != '')
		BEGIN
			SET @sqlString += ' AND ISNULL( CREADOR.nombre,'''')+ '' ''+ ISNULL(CREADOR.appaterno,'''') + '' '' + ISNULL(CREADOR.apmaterno,'''')  LIKE @pnombrecreadorLIKE ' + @nl 
		END*/
		
		/*IF (@pidPlantilla > 0 )
		BEGIN
			SET @sqlString += ' AND C.idPlantilla = @pidPlantilla ' + @nl
		END*/
	
		/*IF (@pDescripcion_Pl != '')
		BEGIN
			SET @sqlString += ' AND PL.Descripcion_PL LIKE @pDescripcion_PlLIKE ' + @nl
		END*/
	
		/*IF (@pcentrocostoid > 0)
		BEGIN
			SET @sqlString += ' AND CDV.CentroCosto =  @pcentrocostoid' + @nl
		END*/
		
		/*IF (@pNombreCentroCosto != '' )
		BEGIN
			SET @sqlString += ' AND CC.nombrecentrocosto LIKE  @pNombreCentroCostoLIKE' + @nl
		END*/
		
		/*IF (@pNombreCasino != '' )
		BEGIN
			SET @sqlString += ' AND CDV.NombreCasino LIKE  @pNombreCasinoLIKE' + @nl
		END*/
		
		/*IF (@pCodCargo > 0)
		BEGIN
			SET @sqlString += ' AND CDV.CodCargo =  @pCodCargo' + @nl
		END*/
		
		/*IF (@pCargo != '' )
		BEGIN
			SET @sqlString += ' AND CAR.Descripcion LIKE @pCargoLIKE' + @nl
		END*/

		/*IF (@pidProceso != 0)
		BEGIN
			SET @sqlString += ' AND P.idProceso = @pidProceso' + @nl
		END*/
		
		/*IF (@pEmpresa != '')
		BEGIN
			SET @sqlString += ' AND C.RutEmpresa = @pEmpresa' + @nl
		END*/
		
		IF (@pRutEmpleado != '')
		BEGIN
			SET @sqlString += ' AND empleadoFormulario.empleadoid = @pRutEmpleado' + @nl
		END
		
		IF (@pNombreEmpleado != '' )
		BEGIN
			SET @sqlString += ' AND ISNULL(	Personas.nombre,'''') + '' '' + ISNULL(Personas.appaterno,'''')+ '' ''+ ISNULL(Personas.apmaterno,'''') LIKE @pNombreEmpleadoLIKE' + @nl
		END
		
		/*IF (@pEnviado > -1 )
		BEGIN
			SET @sqlString += ' AND C.Enviado = @pEnviado' + @nl
		END*/
					
		IF ( @fechaInicio IS NOT NULL AND @fechaFin IS NULL)  
		BEGIN
			SET @fechaFin = DATEADD (DAY, 1,@fechaInicio)
			SET @sqlString += ' AND Contratos.FechaUltimaFirma BETWEEN @fechaInicio AND @fechaFin' + @nl
		END
		
		IF ( @fechaInicio IS NOT NULL AND @fechaFin IS NOT NULL)  
		BEGIN
			SET @sqlString += ' AND Contratos.FechaUltimaFirma BETWEEN @fechaInicio AND @fechaFin' + @nl
		END
		
		IF ( @fechaInicio IS NULL AND @fechaFin IS NOT NULL)  
		BEGIN
			SET @sqlString += ' AND Contratos.FechaUltimaFirma <= @fechaFin' + @nl
		END
		
		IF (@pidEstadoGestion != 0)
		BEGIN
			SET @sqlString += ' AND empleadoFormulario.estadoFormularioid = @pidEstadoGestion' + @nl
		END

		--IF (@pidEstadoGestion > 0 AND @pidEstadoGestion <> 10)
		--BEGIN
		--	SET @sqlString += ' AND EGI.idEstadoGestion = @pidEstadoGestion' + @nl
		--END
		
		--IF (@pidEstadoGestion > 0 AND @pidEstadoGestion = 10)
		--BEGIN
		--	SET @sqlString += ' AND EGI.idEstadoGestion IS NULL' + @nl
		--END
		
		/*IF( @pidEstadoContrato > 0 )
		BEGIN
			SET @sqlString += ' AND C.idEstado = @pidEstadoContrato' + @nl
		END*/
		
		/*IF (@pidTipoFirma > 0)
		BEGIN
			SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
		END*/
		
		SET @sqlString += N') 
					  SELECT 
							idDocumento,
							--personaid, 
							--NombreCreador,
							--idPlantilla, 
							--Descripcion_Pl,
							--centrocostoid,
							--nombrecentrocosto,
							--NombreCasino,
							--CodCargo, 
							--Cargo,
							FechaUltimaFirma,
							--idProceso, 
							--Descripcion, 
							RutEmpresa,
							--RazonSocial,
							RutEmpleado,
							NombreEmpleado,
							--Enviado,
							--idEstadoGestion,
							EstadoGestion,
							fechaCarga,
							--EstadoContrato,
							--Firma,
							--FechaSolicitud,
							--FechaPayroll,
							--Responsable,
							--FechaDT,
                            empleadoFormularioid,
							nombreUsuario,
							RowNum
					  FROM DocumentosTabla
					  WHERE	RowNum BETWEEN @Pinicio AND @Pfin'

			DECLARE @Parametros nvarchar(max)
	
			SET @Parametros =  N'
                                @ptipousuarioid INT, 
                                @pusuarioid VARCHAR(10), 
                                @Pinicio INT, @Pfin INT, @pidDocumento INT, 
                                --@prutcreador VARCHAR(10),
                                --@pnombrecreadorLIKE VARCHAR(50),
								--@pidPlantilla INT,
                                --@pDescripcion_PlLIKE VARCHAR(50),
                                --@pcentrocostoid INT,
                                --@pNombreCentroCostoLIKE VARCHAR(50),
                                --@pNombreCasinoLIKE VARCHAR(50),
								--@pCodCargo INT,
                                --@pCargoLIKE VARCHAR(50),
                                --@pidProceso INT,
                                --@pEmpresa VARCHAR(10),
                                @pRutEmpleado VARCHAR(10),@pNombreEmpleadoLIKE VARCHAR(50),
								--@pEnviado INT, 
                                @fechaInicio DATE,
                                @fechaFin DATE, 
                                @pidEstadoGestion INT'--, 
                                --@pidEstadoContrato INT, 
                                --@pidTipoFirma INT'
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

			EXECUTE sp_executesql @sqlString, @Parametros, 
								  @ptipousuarioid, 
                                  @pusuarioid, 
                                  @Pinicio,@Pfin, @pidDocumento, 
                                  --@prutcreador, 
                                  --@pnombrecreadorLIKE, 
                                  --@pidPlantilla,
                                  --@pDescripcion_PlLIKE,
                                  --@pcentrocostoid,
								  --@pNombreCentroCostoLIKE,
                                  --@pNombreCasinoLIKE,
                                  --@pCodCargo,
                                  --@pCargoLIKE,
                                  --@pidProceso,
                                  --@pEmpresa,
                                  @pRutEmpleado,
								  @pNombreEmpleadoLIKE,
                                  --@pEnviado,
                                  @fechaInicio,
                                  @fechaFin, 
                                  @pidEstadoGestion--, 
                                  --@pidEstadoContrato, 
                                  --@pidTipoFirma
								 
	 RETURN                   
END
GO
