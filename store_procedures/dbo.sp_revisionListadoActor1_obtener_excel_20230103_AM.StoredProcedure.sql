USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_revisionListadoActor1_obtener_excel_20230103_AM]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


-- =============================================
-- Autor: Luis Morales
-- Creado el: 15/12/2020
-- Descripcion: Muestra el listado de Documemtos Revision RRHH 
-- Modificado: gdiaz 14/04/2021
-- Ejemplo:
-- [sp_revisionListadoActor1_obtener_excel] 1,'26131316-2',1,10,0,'','',0,'',0,'','',0,'',0,'','','',-1,NULL,NULL,0,0,0,1 -- Todo
-- =============================================
--NUEVO
CREATE PROCEDURE [dbo].[sp_revisionListadoActor1_obtener_excel_20230103_AM]
	@pagina					INT,	-- numero de pagina
	@decuantos				DECIMAL,	-- total pagina
	@pidDocumento			INT,	-- Id Documento
	@pRutEmpleado			VARCHAR(10),
	@pNombreEmpleado		VARCHAR(50),
	@fechaInicio			DATE,		-- Fecha inicio
	@fechaFin				DATE,		-- Fecha fin 
	@pidEstadoGestion		INT,	
    @ptipousuarioid         INT,
    @usuarioid              VARCHAR(10),
	@idFormulario		INT,	
	@debug					tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	
	SET NOCOUNT ON;
	
	DECLARE @nl   char(2) = char(13) + char(10)	
	DECLARE @pNombreEmpleadoLIKE VARCHAR(50)
	DECLARE @sqlString nvarchar(max)
    DECLARE @vdecimal DECIMAL (9,2)
    DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT

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
			estadoFormulario.estadoformularioid AS IDEstadoGestion,
            estadoFormulario.nombre AS EstadoGestion,
            empleadoFormulario.empleadoFormularioid,
			ContratoDatosVariables.querySiNoObs1 AS Respuesta1,
			ContratoDatosVariables.querySiNoObs1_texto AS Observacion1,
			formularioPlantilla.nombreFormulario,
			--ContratoDatosVariables.querySiNoObs2 AS Respuesta2,
			--ContratoDatosVariables.querySiNoObs2_texto AS Observacion2,
			--ContratoDatosVariables.querySiNoObs3  AS Respuesta3,
			--ContratoDatosVariables.querySiNoObs3_texto AS Observacion3,
			--ContratoDatosVariables.querySiNoObs4 AS Respuesta4,
			--ContratoDatosVariables.querySiNoObs4_texto AS Observacion4,
			--ContratoDatosVariables.querySiNoDinamico1 AS Respuesta5,
			--ContratoDatosVariables.querySiNoDinamico1_texto AS Observacion5,
			--ContratoDatosVariables.querySiNoObs5 AS Respuesta6,
			--ContratoDatosVariables.querySiNoObs5_texto AS Observacion6,
			ROW_NUMBER()Over(Order by empleadoFormulario.empleadoFormularioid DESC) As RowNum
		FROM empleadoFormulario
		INNER JOIN formularioPlantilla ON formularioPlantilla.idFormulario = empleadoFormulario.idFormulario
        LEFT JOIN Contratos ON Contratos.idDocumento = empleadoFormulario.idDocumento
		INNER JOIN Personas ON Personas.personaid = empleadoFormulario.empleadoid
		LEFT JOIN ContratoDatosVariables ON  Contratos.idDocumento = ContratoDatosVariables.idDocumento
        INNER JOIN estadoFormulario ON estadoFormulario.estadoFormularioid = empleadoFormulario.estadoFormularioid' + @nl
																				
		--SET @sqlString += N' WHERE C.Eliminado = 0 ' + @nl
        SET @sqlString += N' WHERE empleadoFormulario.estadoFormularioid IN (2,3,4,6) ' + @nl
			
		IF (@pidDocumento != 0)
		BEGIN
			SET @sqlString += ' AND Contratos.idDocumento = @pidDocumento ' + @nl
		END
		
		IF (@idFormulario != 0)
		BEGIN
			SET @sqlString += ' AND empleadoFormulario.idFormulario = @idFormulario' + @nl
		END

		IF (@pRutEmpleado != '')
		BEGIN
			SET @sqlString += ' AND empleadoFormulario.empleadoid = @pRutEmpleado' + @nl
		END
		
		IF (@pNombreEmpleado != '' )
		BEGIN
			SET @sqlString += ' AND ISNULL(	Personas.nombre,'''') + '' '' + ISNULL(Personas.appaterno,'''')+ '' ''+ ISNULL(Personas.apmaterno,'''') LIKE @pNombreEmpleadoLIKE' + @nl
		END
					
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
		
		SET @sqlString += N') 
					  SELECT 
							idDocumento,
							FechaUltimaFirma,
							RutEmpleado,
							NombreEmpleado,
							IDEstadoGestion,
							EstadoGestion,
                            empleadoFormularioid,
							respuesta1,
							observacion1,
							nombreFormulario,
							--respuesta2,
							--observacion2,
							--respuesta3,
							--observacion3,
							--respuesta4,
							--observacion4,
							--respuesta5,
							--observacion5,
							--respuesta6,
							--observacion6,
							RowNum
					  FROM DocumentosTabla
					  WHERE	RowNum BETWEEN @Pinicio AND @Pfin'

			DECLARE @Parametros nvarchar(max)
	
			SET @Parametros =  N'
                                @Pinicio INT, @Pfin INT, @pidDocumento INT, 
                                @pRutEmpleado VARCHAR(10),@pNombreEmpleadoLIKE VARCHAR(50),
                                @fechaInicio DATE,
                                @fechaFin DATE, 
                                @pidEstadoGestion INT, 
                                @idFormulario INT'--, 
 
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

			EXECUTE sp_executesql @sqlString, @Parametros, 
                                  @Pinicio,@Pfin, @pidDocumento, 
                                  @pRutEmpleado,
								  @pNombreEmpleadoLIKE,
                                  @fechaInicio,
                                  @fechaFin, 
                                  @pidEstadoGestion, 
                                @idFormulario--, 

								 
	 RETURN                   
END

SET ANSI_NULLS OFF
GO
