USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_revisionListadoActor2_total]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_revisionListadoActor2_total]
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
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)

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
			empleadoFormulario.empleadoFormularioid,
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
		
		/*IF (@pEmpresa != '' )
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
							 --@totalorig = count(centrocostoid)
							 @totalorig = count(empleadoFormularioid)
					  FROM DocumentosTabla'

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
                            @pidEstadoGestion INT, 
                            --@pidEstadoContrato INT, 
                            --@pidTipoFirma INT, 
                            @totalorig INT OUTPUT' 
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
                              @pidEstadoGestion, 
                              --@pidEstadoContrato, 
                              --@pidTipoFirma, 
                              @totalorig = @totalorig OUTPUT
							  
		SELECT @totalreg = cast(@totalorig as decimal(18,6))/cast(@decuantos  as decimal(18,6))
			
		SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
	        
		IF @vdecimal > 0 
			SELECT @total = @totalreg + 1
		ELSE
			SELECT @total = @totalreg
			
		SET @totalreg = @totalreg * @decuantos
	 
		SELECT  @total as total, @totalreg as totalreg
		                                                                      					 
	 RETURN                   
END
GO
