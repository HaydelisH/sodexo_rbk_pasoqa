USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosporaprobar_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:
-- [sp_documentosporaprobar_listado_QA] 1,1,10,0,1,1,0,0,'','12382466-0',0
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosporaprobar_listado]

	@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@pidDocumento			INT,	-- Id Documento
	@pidtipodocumento		INT,	-- TipoDocumento
	@pidEstadoContrato		INT,	-- Estado del Contrato
	@pidTipoFirma			INT,	-- 1 = Manual y 2 = Elctronico
	@pidProceso				INT,	-- Id del Proceso
	@pRutEmpleado	varchar(10),	-- Rut del Empleado
	@pNombreEmpleado varchar(100),-- Nombre del Empleado
	@pusuarioid		varchar(50),	-- id usuario
	@pfichaid				INT,	-- Fichaid
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
		
AS
BEGIN
	
	SET NOCOUNT ON;

	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @pNombreEmpleadoLIKE	VARCHAR(100)
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
   SET @pNombreEmpleadoLIKE = '%' + @pNombreEmpleado + '%'; 
	
	DECLARE @sqlString nvarchar(max)
	
	DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)

	DECLARE @idEstado INT

	--Buscar el rol del usuario
	SELECT @rolid = rolid FROM Usuarios WHERE usuarioid = @pusuarioid

	IF( @rolid IS NULL )
	BEGIN 
		SELECT @lmensaje = 'El usuario no tiene rol asignado'
	END	
	create table #tdocxperfil (RowNum INT IDENTITY(1,1) ,idDocumento INT, idPlantilla int, idTipoDoc INT, idproceso INT, idestado INT,
			idtipofirma INT,FechaCreacion DateTime, Rut varchar(12) COLLATE database_default,RutEmpresa varchar(14) COLLATE database_default,
			idWf int, FechaUltimaFirma DateTime, CentroCosto NVARCHAR(14) COLLATE database_default )

		INSERT INTO #tdocxperfil
		 Select C.idDocumento, pl.idPlantilla, PL.idTipoDoc,idproceso, idestado, 
				C.idtipofirma,C.FechaCreacion, Rut, RutEmpresa, 
				C.idWf, FechaUltimaFirma, CDV.CentroCosto
		from Contratos C
		inner join Plantillas PL on PL.idPlantilla = C.idPlantilla
		inner join tiposdocumentosxperfil T ON PL.idTipoDoc = T.idtipodoc
		inner join Documentos D on C.idDocumento = D.idDocumento
		INNER JOIN ContratoDatosVariables CDV ON CDV.idDocumento = C.idDocumento
		INNER JOIN accesoxusuarioccosto     ACC	
			ON 
			ACC.empresaid = C.RutEmpresa 
			AND ACC.lugarpagoid = CDV.LugarPagoid 
			AND ACC.centrocostoid = CDV.CentroCosto 
			AND ACC.usuarioid = @pusuarioid   
        INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = C.idWF
            AND WorkflowProceso.tipoWF IS NULL
		where  
		C.Eliminado = 0 and 
		tipousuarioid = @ptipousuarioid and 
		C.idEstado = 1
			
	SET @sqlString = N';	
	;
	With DocumentosTabla
      as 
             (
        Select 
			C.idDocumento, C.idPlantilla, C.idTipoDoc,C.idproceso, C.idestado, idtipofirma,  C.FechaCreacion, C.Rut, C.RutEmpresa, C.idWf, C.FechaUltimaFirma,  C.CentroCosto, RowNum 
			,ROW_NUMBER() Over( Order by C.idDocumento DESC) As linea
		from #tdocxperfil  C		
		' + @nl

	IF (@pNombreEmpleado != '')
	BEGIN
		SET @sqlString += ' INNER JOIN personas PER	ON C.Rut = PER.personaid' + @nl
	END

	--Validar el rol
	/*IF( @rolid = 2 ) --1 : Privado y 2: Público
	BEGIN
		SET @sqlString += N' INNER JOIN Empleados Emp ON C.Rut = Emp.empleadoid AND Emp.rolid = @rolid ' + @nl
	END*/
			
	SET @sqlString += N' WHERE 1=1 ' + @nl		
	

	IF (@pRutEmpleado != '')
	BEGIN
		SET @sqlString += ' AND C.Rut = @pRutEmpleado  ' + @nl
	END
	
	IF (@pNombreEmpleado != '')
	BEGIN
		SET @sqlString += ' AND ( (PER.nombre + '' '' + PER.appaterno + '' '' + PER.apmaterno) LIKE @pNombreEmpleadoLIKE ) ' + @nl
	END

				
	IF (@pidDocumento != 0)
	BEGIN
		SET @sqlString += ' AND C.idDocumento = @pidDocumento ' + @nl
	END

	IF (@pidtipodocumento != 0)
	BEGIN
		SET @sqlString += ' AND C.idTipoDoc = @pidtipodocumento' + @nl
	END	

	IF (@pidTipoFirma != 0)
	BEGIN
		SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
	END
	
	IF (@pidProceso != 0)
	BEGIN
		SET @sqlString += ' AND C.idproceso = @pidProceso' + @nl
	END
			
	
	SET @sqlString += N') 
				  SELECT 
						C.idDocumento,
						TD.NombreTipoDoc,
						P.Descripcion As Proceso,
						CE.Descripcion As Estado,
						C.idEstado,
						FT.Descripcion As Firma,
						CONVERT(CHAR(10), C.FechaCreacion,105)	AS FechaCreacion,
						CONVERT(CHAR(10), C.FechaUltimaFirma,105)	AS FechaUltimaFirma,
						1 as Semaforo,
						WEP.DiasMax,
						C.idWF,
						ROW_NUMBER()Over(Order by C.idDocumento DESC) As RowNum,
						C.RutEmpresa,
						E.RazonSocial,
						C.Rut,
						PER.nombre,
						PER.appaterno,
						PER.apmaterno,
						REP.personaid as RutRep,
						REP.nombre as nombre_rep,
						REP.appaterno AS appaterno_rep,
						REP.apmaterno AS apmaterno_rep
				  FROM DocumentosTabla C
					INNER JOIN TipoDocumentos TD	    ON TD.idTipoDoc = C.idTipoDoc
					INNER JOIN Procesos P				ON P.idProceso = C.idProceso
					INNER JOIN ContratosEstados CE		ON CE.idEstado = C.idEstado
					LEFT JOIN ContratoFirmantes CF		ON C.idDocumento = CF.idDocumento AND C.idEstado = CF.idEstado 
					INNER JOIN FirmasTipos FT			ON FT.idTipoFirma = C.idTipoFirma
					INNER JOIN Empresas E				ON E.RutEmpresa = C.RutEmpresa
					LEFT JOIN WorkflowEstadoProcesos WEP	ON C.idWF = idWorkflow AND C.idEstado =  WEP.idEstadoWF
					INNER JOIN Personas PER				    ON PER.personaid = C.Rut
					LEFT JOIN personas REP					ON REP.personaid = CF.RutFirmante
				 WHERE	linea BETWEEN @Pinicio AND @Pfin '        
				  
		DECLARE @Parametros nvarchar(max)
		
		SET @Parametros =  N'@ptipousuarioid INT, @Pinicio INT,
							 @Pfin INT, @PidDocumento INT, @pidtipodocumento INT,
							 @pidEstadoContrato INT, @pidTipoFirma INT,
							 @pidProceso INT,  @pRutEmpleado	varchar(10), @pNombreEmpleado varchar(100),  @pusuarioid VARCHAR(50), @pNombreEmpleadoLIKE VARCHAR(100) , @lmensaje VARCHAR(100), @rolid INT, @pfichaid INT'
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @ptipousuarioid , @Pinicio , @Pfin, @PidDocumento, @pidtipodocumento,
							  @pidEstadoContrato,@pidTipoFirma,@pidProceso,  @pRutEmpleado, @pNombreEmpleado, @pusuarioid,@pNombreEmpleadoLIKE, @lmensaje, @rolid, @pfichaid
                       	
    RETURN                                                             

END
GO
