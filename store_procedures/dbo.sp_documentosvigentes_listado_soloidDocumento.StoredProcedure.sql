USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosvigentes_listado_soloidDocumento]    Script Date: 1/22/2024 7:21:14 PM ******/
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
--	sp_documentosvigentes_listado_soloidDocumento 1,1,9,0,1,1,2,0,'','26131316-2'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosvigentes_listado_soloidDocumento]
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
@debug			tinyint	= 0		-- DEBUG 1= imprime consulta

AS
BEGIN
	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @FirmanteLIKE	VARCHAR(100)

	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
	
	DECLARE @sqlString nvarchar(max)

	SET @FirmanteLIKE = '%' + @pFirmante + '%'; 

	DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)

	
	--Buscar el rol del usuario
	SELECT @rolid = rolid FROM Usuarios WHERE usuarioid = @pusuarioid	
	
	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
			C.idDocumento,	
			ROW_NUMBER()Over(Order by C.idDocumento DESC) As RowNum
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
		INNER JOIN accesoxusuarioccosto     ACC	
        ON ACC.empresaid = C.RutEmpresa 
        AND ACC.centrocostoid = CDV.CentroCosto 
        AND ACC.lugarpagoid = CDV.lugarpagoid 
        --AND ACC.departamentoid = CDV.departamentoid 
        AND ACC.usuarioid = @pusuarioid 
		INNER JOIN tiposdocumentosxperfil TAPP	ON TAPP.idtipodoc = PL.idTipoDoc AND TAPP.tipousuarioid = @ptipousuarioid
		LEFT JOIN ContratoFirmantes CF			ON CF.idDocumento = C.idDocumento AND CF.RutEmpresa = C.RutEmpresa AND C.idEstado = CF.idEstado
		LEFT JOIN personas REP					ON REP.personaid = CF.RutFirmante' + @nl
	
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
		SET @sqlString += ' AND C.idEstado = @pidEstadoContrato' + @nl
	ELSE
		SET @sqlString += ' AND C.idEstado IN (2,3,9,10,11)' + @nl
	
	IF (@pidTipoFirma != 0)
	BEGIN
		SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
	END
	
	IF (@pidProceso != 0)
	BEGIN
		SET @sqlString += ' AND P.idProceso = @pidProceso' + @nl
	END
	
	SET @sqlString += N') 
				  SELECT 
						 idDocumento
				  FROM DocumentosTabla
				  WHERE	RowNum BETWEEN @Pinicio AND @Pfin'        
				  
		DECLARE @Parametros nvarchar(max)
		
		SET @Parametros =  N'@ptipousuarioid INT, @Pinicio INT,
							 @Pfin INT, @PidDocumento INT, @pidtipodocumento INT,
							 @pidEstadoContrato INT, @pidTipoFirma INT,
							 @pidProceso INT, @pusuarioid VARCHAR(50), @pFirmante VARCHAR(100),  @FirmanteLIKE	VARCHAR(100), @rolid INT'
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @ptipousuarioid , @Pinicio , @Pfin, @PidDocumento, @pidtipodocumento,
							  @pidEstadoContrato,@pidTipoFirma,@pidProceso,@pusuarioid,@pFirmante,  @FirmanteLIKE, @rolid
                       	
    RETURN                                                             

END
GO
