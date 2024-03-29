USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_documentos_listado_firmaunitaria]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Modificado por: Gdiaz 11/01/2021
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
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_documentos_listado_firmaunitaria]

	@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@pidDocumento			INT,	-- Id Documento
	@pidtipodocumento		INT,	-- TipoDocumento
	@pidEstadoContrato		INT,	-- Estado del Contrato
	@pidTipoFirma			INT,	-- 1 = Manual y 2 = Elctronico
	@pidProceso				INT,	-- Id del Proceso
	@pFirmante		varchar(100),	-- Firmante del Contrato
	@debug			tinyint	= 0	,	-- DEBUG 1= imprime consulta
	@nombreTrabajador		varchar(100),	-- Firmante del Contrato
	@rlTipoDocumento nvarchar(200), -- Nombre del documento
	@fechaInicio			DATE,	-- Fecha inicio
	@fechaFin				DATE	-- Fecha fin 
		
AS
BEGIN
	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @nombreTrabajadorLIKE	VARCHAR(100)
	DECLARE @rlTipoDocumentoLIKE	VARCHAR(202)	

	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  

    SET @nombreTrabajadorLIKE = '%' + @nombreTrabajador + '%'; 
	SET @rlTipoDocumentoLIKE = '%' + @rlTipoDocumento + '%'

	DECLARE @sqlString nvarchar(max)
	
	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
			C.idDocumento,
			TD.NombreTipoDoc,
			P.Descripcion As Proceso,
			CE.idEstado,
			CE.Descripcion As Estado,
			FT.Descripcion As Firma,
			CONVERT(CHAR(10), C.FechaCreacion,105)	AS FechaCreacion,
			CONVERT(CHAR(10), C.FechaUltimaFirma,105)	AS FechaUltimaFirma,
			1 as Semaforo,
			WEP.DiasMax,
			C.idWF,
			ROW_NUMBER()Over(Order by C.idDocumento DESC) As RowNum,
            --personas.nombre + '' '' + personas.appaterno + '' '' + personas.apmaterno AS nombreTrabajador
            ContratoDatosVariables.rlTipoDocumento AS nombreDocumento
		FROM [Contratos] C
		INNER JOIN Documentos D				ON D.idDocumento = C.idDocumento
		INNER JOIN Plantillas PL			ON PL.idPlantilla = C.idPlantilla
		INNER JOIN TipoDocumentos TD	    ON PL.idTipoDoc = TD.idTipoDoc
		INNER JOIN Procesos P				ON P.idProceso = C.idProceso
		INNER JOIN ContratosEstados CE		ON CE.idEstado = C.idEstado
		INNER JOIN FirmasTipos FT			ON FT.idTipoFirma = C.idTipoFirma	
		INNER JOIN Empresas E				ON E.RutEmpresa = C.RutEmpresa
        INNER JOIN ContratoDatosVariables ON ContratoDatosVariables.iddocumento = C.iddocumento
        --INNER JOIN personas ON personas.personaid = ContratoDatosVariables.Rut
		LEFT  JOIN WorkflowEstadoProcesos WEP	ON C.idWF = idWorkflow AND C.idEstado =  WEP.idEstadoWF
        INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = C.idWF
            AND WorkflowProceso.tipoWF = 1' + @nl
		
	IF (@pFirmante != '')
	BEGIN
		--SET @sqlString += ' INNER JOIN ContratoFirmantes CF	ON CF.idDocumento = C.idDocumento AND CF.RutFirmante = @pFirmante AND CF.idEstado = C.idEstado' + @nl
		--SET @sqlString +='INNER JOIN ContratoFirmantes CF	ON CF.idDocumento = C.idDocumento AND CF.idEstado = C.idEstado' + @nl
		--SET @sqlString += ' INNER JOIN ContratoFirmantes CF	ON CF.idDocumento = C.idDocumento AND CF.RutFirmante = @pFirmante AND CF.idEstado = C.idEstado AND CF.Firmado= 0' + @nl
		SET @sqlString += ' INNER JOIN ContratoFirmantes CF	ON CF.idDocumento = C.idDocumento AND CF.RutFirmante = @pFirmante AND CF.idEstado = C.idEstado AND CF.Firmado = 0
		AND	CF.RutFirmante = CASE WHEN CF.OrdenMismoEstado IS NULL THEN CF.RutFirmante ELSE
		(
			SELECT TOP 1 
				RutFirmante 
			FROM ContratoFirmantes CF
				WHERE idDocumento = C.idDocumento AND idEstado= C.idEstado AND Firmado = 0
			ORDER BY OrdenMismoEstado 
		)
		END' + @nl
	END
																		
	SET @sqlString += N' WHERE C.Eliminado = 0 ' + @nl
				
	IF (@rlTipoDocumento != '')
	BEGIN
		SET @sqlString += ' AND ContratoDatosVariables.rlTipoDocumento LIKE @rlTipoDocumentoLIKE'
	END

    IF (@nombreTrabajador != '')
    BEGIN
        SET @sqlString += ' AND ( personas.nombre LIKE @nombreTrabajadorLIKE OR personas.appaterno LIKE @nombreTrabajadorLIKE OR personas.apmaterno LIKE @nombreTrabajadorLIKE) ' + @nl
    END

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

	IF( @pidEstadoContrato < 0 )
	BEGIN
		SET @sqlString += ' AND C.idEstado IN (2,3,6,8,9,10,11)' + @nl
	END
			
	IF( @pidEstadoContrato = 0 )
	BEGIN
		SET @sqlString += ' AND C.idEstado IN (2,3,10,11,12)' + @nl
	END

	
	IF (@pidTipoFirma != 0)
	BEGIN
		SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
	END
	
	IF (@pidProceso != 0)
	BEGIN
		SET @sqlString += ' AND P.idProceso = @pidProceso' + @nl
	END

	IF ( @fechaInicio IS NOT NULL AND @fechaFin IS NULL)  
	BEGIN
		SET @fechaFin = DATEADD (DAY, 1,@fechaInicio)
		SET @sqlString += ' AND C.FechaCreacion BETWEEN @fechaInicio AND @fechaFin' + @nl
	END
	IF ( @fechaInicio IS NOT NULL AND @fechaFin IS NOT NULL)  
	BEGIN
		SET @sqlString += ' AND C.FechaCreacion BETWEEN @fechaInicio AND @fechaFin' + @nl
	END
	IF ( @fechaInicio IS NULL AND @fechaFin IS NOT NULL)  
	BEGIN
		SET @sqlString += ' AND C.FechaCreacion <= @fechaFin' + @nl
	END	

	SET @sqlString += N') 
				  SELECT 
						 idDocumento
						,NombreTipoDoc
						,Proceso
						,idEstado
						,Estado
						,Firma
						,FechaCreacion
						,FechaUltimaFirma
						,Semaforo
						,DiasMax as DiasEstadoActual
						,idWF
						,Rownum
                        --,nombreTrabajador
                        ,nombreDocumento
				  FROM DocumentosTabla
				  WHERE	RowNum BETWEEN @Pinicio AND @Pfin'        
				  
		DECLARE @Parametros nvarchar(max)
		
		SET @Parametros =  N'@ptipousuarioid INT, @Pinicio INT,
							 @Pfin INT, @PidDocumento INT, @pidtipodocumento INT,
							 @pidEstadoContrato INT, @pidTipoFirma INT,
							 @pidProceso INT, @pFirmante VARCHAR(100), @nombreTrabajadorLIKE VARCHAR(100), @rlTipoDocumento VARCHAR(200),
							@rlTipoDocumentoLIKE  VARCHAR(202), @fechaInicio DATE, @fechaFin DATE '
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @ptipousuarioid , @Pinicio , @Pfin, @PidDocumento, @pidtipodocumento,
							  @pidEstadoContrato,@pidTipoFirma,@pidProceso, @pFirmante , @nombreTrabajadorLIKE,@rlTipoDocumento,@rlTipoDocumentoLIKE
							,@fechaInicio, @fechaFin
    RETURN                                                             

END
GO
