USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_documentosvigentes_totalPorTiempo]    Script Date: 1/22/2024 7:21:15 PM ******/
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
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_documentosvigentes_totalPorTiempo]

	@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@pidDocumento			INT,	-- Id Documento
	@pidtipodocumento		INT,	-- TipoDocumento
	@pidEstadoContrato		INT,	-- Estado del Contrato
	@pidTipoFirma			INT,	-- 1 = Manual y 2 = Elctronico
	@pidProceso				INT,	-- Id del Proceso
	@pFirmante		varchar(100),	-- Firmante del Contrato
	@pusuarioid		varchar(50),	-- id usuario
	@pfichaid				INT,	-- Fichaid
	@fechaInicio			DATE,		-- Fecha inicio
	@fechaFin				DATE,		-- Fecha fin 
	@idPlantilla			INT,
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta

AS
BEGIN
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	DECLARE @nl   char(2) = char(13) + char(10)
	DECLARE @FirmanteLIKE	VARCHAR(100)									  
              
	DECLARE @sqlString nvarchar(max)
	
	SET @FirmanteLIKE = '%' + @pFirmante + '%'; 
	 
    DECLARE @vdecimal DECIMAL (9,2)
    
    DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)

	
	--Buscar el rol del usuario
	SELECT @rolid = rolid FROM Usuarios WHERE usuarioid = @pusuarioid	
	
	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
			C.idDocumento
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
		INNER JOIN accesoxusuarioccosto     ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.lugarpagoid = CDV.lugarpagoid AND ACC.centrocostoid = CDV.CentroCosto AND ACC.usuarioid = @pusuarioid 
		INNER JOIN tiposdocumentosxperfil TAPP	ON TAPP.idtipodoc = PL.idTipoDoc AND TAPP.tipousuarioid = @ptipousuarioid
		LEFT JOIN ContratoFirmantes CF			ON CF.idDocumento = C.idDocumento AND CF.RutEmpresa = C.RutEmpresa AND C.idEstado = CF.idEstado
		LEFT JOIN personas REP					ON REP.personaid = CF.RutFirmante
		LEFT JOIN fichasdocumentos FD	ON C.idDocumento = FD.documentoid 
        INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = C.idWF
            AND WorkflowProceso.tipoWF = 1' + @nl
		
		--Validar el rol
		IF( @rolid = 2 ) --1 : Privado y 2: Público
		BEGIN
			SET @sqlString += N' INNER JOIN Empleados Emp ON CDV.Rut = Emp.empleadoid AND Emp.rolid = @rolid ' + @nl
		END
																		
		SET @sqlString += N' WHERE C.Eliminado = 0 ' + @nl

		IF (@pFirmante != '')
		BEGIN
			SET @sqlString += ' AND ( PER.personaid LIKE @FirmanteLIKE OR PER.nombre LIKE @FirmanteLIKE OR PER.appaterno LIKE @FirmanteLIKE OR PER.apmaterno LIKE @FirmanteLIKE OR 
									REP.personaid LIKE @FirmanteLIKE OR REP.nombre LIKE @FirmanteLIKE OR REP.appaterno LIKE @FirmanteLIKE OR REP.apmaterno LIKE @FirmanteLIKE  ) ' + @nl
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
			SET @sqlString += ' AND C.idEstado IN (1,2,3,4,6,8,9,10,11)' + @nl
		END
		
		IF( @pidEstadoContrato = 0 )
		BEGIN
			SET @sqlString += ' AND C.idEstado IN (2,3,10,11)' + @nl
		END
		
		IF (@pidTipoFirma != 0)
		BEGIN
			SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
		END
		
		IF (@pidProceso != 0)
		BEGIN
			SET @sqlString += ' AND P.idProceso = @pidProceso' + @nl
		END
		
		IF( @pfichaid > 0 )
		BEGIN 
			SET @sqlString += ' AND FD.fichaid = @pfichaid' + @nl
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
		
		IF ( @idPlantilla > 0 )  
		BEGIN
			SET @sqlString += ' AND C.idPlantilla = @idPlantilla' + @nl
		END

		SET @sqlString += N') 
					  SELECT 
							 @totalorig = count(idDocumento)
					  FROM DocumentosTabla
					  '                              

			DECLARE @Parametros nvarchar(max)
			
			SET @Parametros =  N'@ptipousuarioid INT, @PidDocumento INT, @pidtipodocumento INT,
							 @pidEstadoContrato INT, @pidTipoFirma INT,
							 @pidProceso INT,@pFirmante VARCHAR(100),
							 @pusuarioid VARCHAR(50),@FirmanteLIKE VARCHAR(100), 
							 @lmensaje VARCHAR(100),@rolid INT,@pfichaid INT, @fechaInicio DATE,
							 @fechaFin DATE,@idPlantilla INT, @totalorig INT OUTPUT'
						
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

			EXECUTE sp_executesql @sqlString, @Parametros, 
								  @ptipousuarioid,@PidDocumento,@pidtipodocumento,
								  @pidEstadoContrato,@pidTipoFirma,@pidProceso,@pFirmante,@pusuarioid, 
								  @FirmanteLIKE,@lmensaje,@rolid, @pfichaid, @fechaInicio,@fechaFin,@idPlantilla,
								  @totalorig = @totalorig OUTPUT
										
						
			SELECT @totalreg = (@totalorig/@decuantos)
			
			SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
	            
			 IF @vdecimal > 0 
				SELECT @total = @totalreg + 1
			 ELSE
				SELECT @total = @totalreg
				
			SET @totalreg = @totalreg * @decuantos
		 
		select  @total as total, @totalreg as totalreg	                                                                       
	 RETURN                   
END
GO
