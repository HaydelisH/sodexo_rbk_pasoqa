USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_revisionListadoActor1_obtener_20210513]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Modificado: gdiaz 14/04/2021
-- Ejemplo:
-- =============================================
create PROCEDURE [dbo].[sp_revisionListadoActor1_obtener_20210513]
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
	@idFormulario		INT,	
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
            empleadoFormulario.empleadoFormularioid,
			formularioPlantilla.nombreFormulario,
			ROW_NUMBER()Over(Order by empleadoFormulario.empleadoFormularioid DESC) As RowNum
		FROM empleadoFormulario
		INNER JOIN formularioPlantilla ON formularioPlantilla.idFormulario = empleadoFormulario.idFormulario
        LEFT JOIN Contratos ON Contratos.idDocumento = empleadoFormulario.idDocumento
		INNER JOIN Personas ON Personas.personaid = empleadoFormulario.empleadoid
        INNER JOIN estadoFormulario ON estadoFormulario.estadoFormularioid = empleadoFormulario.estadoFormularioid
        INNER JOIN ContratoDatosVariables ON ContratoDatosVariables.idDocumento = Contratos.idDocumento
		INNER JOIN accesoxusuarioccosto     ACC	ON 
            ACC.empresaid = Contratos.RutEmpresa 
            AND ContratoDatosVariables.CentroCosto = ACC.centrocostoid
            AND ContratoDatosVariables.LugarPagoid = ACC.lugarpagoid
		AND ACC.usuarioid = @pusuarioid 
		AND Contratos.Eliminado = 0 ' + @nl
																				
		--SET @sqlString += N' WHERE C.Eliminado = 0 ' + @nl
        SET @sqlString += N' WHERE empleadoFormulario.estadoFormularioid IN (2,3,4,6) ' + @nl
			
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

		IF (@idFormulario != 0)
		BEGIN
			SET @sqlString += ' AND empleadoFormulario.idFormulario = @idFormulario' + @nl
		END
		
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
							--RutEmpresa,
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
							nombreFormulario,
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
                                @pidEstadoGestion INT, 
                                @idFormulario INT'--, 
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
                                  @pidEstadoGestion, 
                                @idFormulario--, 
                                  --@pidEstadoContrato, 
                                  --@pidTipoFirma
								 
	 RETURN                   
END
GO
