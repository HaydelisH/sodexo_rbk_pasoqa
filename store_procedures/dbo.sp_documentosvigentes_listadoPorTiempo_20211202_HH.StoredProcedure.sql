USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosvigentes_listadoPorTiempo_20211202_HH]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--[sp_documentosvigentes_listadoPorTiempo_qa] 1,1,7000,0,0,-1,2,0,'','26131316-2',0,null,null,'','','',0,1
-- Modificado: gdiaz 01/03/2021
CREATE  PROCEDURE [dbo].[sp_documentosvigentes_listadoPorTiempo_20211202_HH]

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
	@fechaInicio			DATE,	-- Fecha inicio
	@fechaFin				DATE,	-- Fecha fin 
	@empresaid		nvarchar(10),	-- Rut empresa 
	@lugarpagoid	nvarchar(14),	-- Lugar de pago 
	@centrocostoid  nvarchar(14),	-- Centro de costo 
	@idplantilla			int,	-- Plantilla
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	
	
	SET NOCOUNT ON;
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @FirmanteLIKE	VARCHAR(100)
	DECLARE @centrocostoLIKE VARCHAR(100);
	SET @centrocostoLIKE = '%' + @centrocostoid + '%'
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
    SET @FirmanteLIKE = '%' + @pFirmante + '%'; 
	
	DECLARE @sqlString nvarchar(max)
	
	DECLARE @rolid			INT 
	DECLARE @lmensaje		VARCHAR(100)

	DECLARE @base_gestor		VARCHAR(120);
    SELECT @base_gestor = parametro FROM Parametros WHERE idparametro = 'gestor'	
	
	--Buscar el rol del usuario
	SELECT @rolid = rolid FROM Usuarios WHERE usuarioid = @pusuarioid		

	DECLARE @idEstado INT
	
	create table #tdocxperfil (RowNum INT IDENTITY(1,1) ,idDocumento INT, idPlantilla int, idTipoDoc INT, idproceso INT, idestado INT,
			idtipofirma INT,fichaid INT,FechaCreacion DateTime, Rut varchar(12) COLLATE database_default,RutEmpresa varchar(14) COLLATE database_default,
			idWf int, FechaUltimaFirma DateTime, LugarPagoid varchar(14) COLLATE database_default, CentroCosto varchar(14) COLLATE database_default , Observacion varchar(200) COLLATE database_default)

		INSERT INTO #tdocxperfil
		 Select C.idDocumento, pl.idPlantilla, PL.idTipoDoc,idproceso, idestado, 
				C.idtipofirma,FD.fichaid,C.FechaCreacion, Rut, RutEmpresa, 
				C.idWf, FechaUltimaFirma , CDV.lugarpagoid , CDV.CentroCosto, C.Observacion
		from Contratos C
		inner join Plantillas PL on PL.idPlantilla = C.idPlantilla
		inner join tiposdocumentosxperfil T ON PL.idTipoDoc = T.idtipodoc
		INNER JOIN ContratoDatosVariables CDV ON CDV.idDocumento = C.idDocumento
		INNER JOIN accesoxusuarioccosto     ACC	
			ON 
			ACC.empresaid = C.RutEmpresa 
			AND ACC.centrocostoid = CDV.CentroCosto 
			AND ACC.lugarpagoid = CDV.lugarpagoid 
			--AND ACC.departamentoid = CDV.departamentoid 
			AND ACC.usuarioid = @pusuarioid  
		LEFT JOIN fichasdocumentos FD	ON C.idDocumento = FD.documentoid							
		where  
		C.Eliminado = 0 and 
		tipousuarioid = @ptipousuarioid	 
		
		-- ademas Filtrar por el ROL
		-- Obtener Rol y hacer logica para wl where


	SET @sqlString = N';	
	With DocumentosTabla
      as 
             (
        Select 
			C.idDocumento, C.idPlantilla, C.idTipoDoc,C.idproceso, C.idestado, C.idtipofirma, C.fichaid, C.FechaCreacion, C.Rut, C.RutEmpresa, C.idWf, C.FechaUltimaFirma, C.LugarPagoid ,C.CentroCosto, RowNum,  estadosGestor.nombreestado
			,ROW_NUMBER() Over( Order by C.idDocumento DESC) As linea, C.Observacion
		from #tdocxperfil  C
		LEFT JOIN ' + @base_gestor + '.dbo.empleados empleadosGestor ON C.Rut = empleadosGestor.empleadoid COLLATE SQL_Latin1_General_CP1_CI_AS
		LEFT JOIN ' + @base_gestor + '.dbo.estados estadosGestor ON estadosGestor.estadoid = empleadosGestor.estado
            
		' + @nl

		
	IF (@pidProceso != 0)
	BEGIN
		SET @sqlString += ' INNER JOIN Procesos P                 ON P.idProceso = C.idProceso	' + @nl
	END
			
		
	--IF (@pFirmante != '')
	--BEGIN
	--	SET @sqlString += ' INNER JOIN Personas PER				ON PER.personaid = CDV.Rut
 --                         LEFT JOIN ContratoFirmantes CF        ON CF.idDocumento = C.idDocumento AND CF.RutEmpresa = C.RutEmpresa AND C.idEstado = CF.idEstado
 --                         LEFT JOIN personas REP                ON REP.personaid = CF.RutFirmante   ' + @nl
	--END									


						
	--Validar el rol
	IF( @rolid = 2 ) --1 : Privado y 2: Público
	BEGIN
		SET @sqlString += N' INNER JOIN Empleados Emp ON C.Rut = Emp.empleadoid AND Emp.rolid = @rolid ' + @nl
	END
			
	SET @sqlString += N' WHERE 1=1 ' + @nl					
		
	IF( @pidEstadoContrato > 0 )
	BEGIN
		SET @sqlString += ' AND C.idEstado = @pidEstadoContrato ' + @nl
	END	
		
	IF( @pidEstadoContrato < 0 )
	BEGIN
		SET @sqlString += ' AND C.idEstado != 7 ' + @nl					
	END
	
	IF( @pidEstadoContrato = 0 )
	BEGIN
		SET @sqlString += ' AND C.idEstado IN (2,3,10,11) ' + @nl
	END

	IF (@pidDocumento != 0)
	BEGIN
		SET @sqlString += ' AND C.idDocumento = @pidDocumento ' + @nl
	END
		
	--IF (@pFirmante != '')
	--BEGIN
	--	SET @sqlString += ' AND ( PER.personaid LIKE @FirmanteLIKE OR PER.nombre LIKE @FirmanteLIKE OR 
	--							REP.personaid LIKE @FirmanteLIKE OR REP.nombre LIKE @FirmanteLIKE  ) ' + @nl
	--END				

	IF (@pFirmante != '')
	BEGIN
		SET @sqlString += ' AND  C.rut = @pFirmante ' + @nl
	END	

	IF (@pidtipodocumento != 0)
	BEGIN
		SET @sqlString += ' AND C.idTipoDoc = @pidtipodocumento' + @nl
	END	

	IF (@idplantilla != 0)
	BEGIN
		SET @sqlString += ' AND C.idPlantilla = @idplantilla' + @nl
	END	
	
	IF (@pidTipoFirma != 0)
	BEGIN
		SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
	END
	
	IF (@pidProceso != 0)
	BEGIN
		SET @sqlString += ' AND C.idProceso = @pidProceso' + @nl
	END

	IF( @pfichaid > 0 )
		BEGIN 
			SET @sqlString += ' AND C.fichaid = @pfichaid' + @nl
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
		
	IF ( @empresaid != '' AND @empresaid != '0' )  
	BEGIN
		SET @sqlString += ' AND C.RutEmpresa = @empresaid' + @nl
	END	
	
	IF ( @lugarpagoid != '' AND  @lugarpagoid != '0')  
	BEGIN
		SET @sqlString += ' AND C.LugarPagoid = @lugarpagoid' + @nl
	END		
	
	IF ( @centrocostoid != '' AND @centrocostoid != '0')  
	BEGIN
		SET @sqlString += ' AND C.CentroCosto LIKE @centrocostoLIKE' + @nl
	END	

	 --SET @sqlString +=	N' AND RowNum BETWEEN @Pinicio AND @Pfin '

		
	SET @sqlString += N') 							
SELECT 
DT.idDocumento
,PL.Descripcion_Pl AS NombreTipoDoc
,p.Descripcion as Proceso
,ce.Descripcion AS Estado
,DT.idEstado
,F.Descripcion as Firma
,CONVERT(CHAR(10), DT.FechaCreacion,105) AS FechaCreacion
,CONVERT(CHAR(10), DT.FechaUltimaFirma,105)    AS FechaUltimaFirma
,1 as Semaforo
								   
,WEP.DiasMax as DiasEstadoActual
,DT.idWF
--,Rownum
,DT.RutEmpresa
,E.RazonSocial
,Rut 
,PER.nombre
,PER.appaterno AS appaterno
,PER.apmaterno AS apmaterno
,REP.personaid as RutRep
,REP.nombre as nombre_rep
,REP.appaterno AS appaterno_rep
,REP.apmaterno AS apmaterno_rep
,DT.idTipoFirma
,DT.fichaid
,DT.LugarPagoid 
,DT.CentroCosto
,CC.nombrecentrocosto
,DT.nombreestado
,DT.Observacion
FROM DocumentosTabla DT
INNER JOIN Procesos P                 ON P.idProceso = DT.idProceso
INNER JOIN ContratosEstados CE		ON CE.idEstado = DT.idEstado
INNER JOIN Plantillas PL              ON PL.idPlantilla = DT.idPlantilla
INNER JOIN TipoDocumentos TD			ON PL.idTipoDoc = TD.idTipoDoc
INNER JOIN Empresas E					ON E.RutEmpresa = DT.RutEmpresa COLLATE database_default
INNER JOIN Personas PER				ON PER.personaid = DT.Rut COLLATE database_default
LEFT JOIN ContratoFirmantes CF        ON CF.idDocumento = DT.idDocumento AND CF.RutEmpresa = DT.RutEmpresa COLLATE database_default AND DT.idEstado = CF.idEstado
LEFT JOIN personas REP                ON REP.personaid = CF.RutFirmante
INNER JOIN FirmasTipos F              ON DT.idTipoFirma = F.idTipoFirma
LEFT JOIN WorkflowEstadoProcesos WEP  ON DT.idWF = idWorkflow AND DT.idEstado =  WEP.idEstadoWF                                                    						
 
--INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = DT.idWF
--	AND WorkflowProceso.PorEnte IS NULL
INNER JOIN centroscosto CC ON CC.centrocostoid = DT.CentroCosto AND CC.lugarpagoid = DT.LugarPagoid AND CC.empresaid = DT.RutEmpresa

WHERE	Linea BETWEEN @Pinicio AND @Pfin '  

	-- SET @sqlString += N' WHERE C.Eliminado = 0 ' + @nl /*SE ELIMINA POR QUE SE FILTRA EN TEMPORAL*/
	--SET @sqlString += N' WHERE 1=1' + @nl /*SE ELIMINA POR QUE SE FILTRA EN TEMPORAL*/

	--IF (@pFirmante != '')
	--BEGIN
	--	SET @sqlString += ' AND ( PER.personaid LIKE @FirmanteLIKE OR PER.nombreLIKE @FirmanteLIKE OR PER.appaterno LIKE @FirmanteLIKE OR PER.apmaterno LIKE @FirmanteLIKE OR 
	--							REP.personaid LIKE @FirmanteLIKE OR REP.nombre LIKE @FirmanteLIKE OR REP.appaterno LIKE @FirmanteLIKE OR REP.apmaterno LIKE @FirmanteLIKE  ) ' + @nl
	--END		
	

				      
				  
		DECLARE @Parametros nvarchar(max)
		
		SET @Parametros =  N'@ptipousuarioid INT, @Pinicio INT,
							 @Pfin INT, @PidDocumento INT, @pidtipodocumento INT,
							 @pidEstadoContrato INT, @pidTipoFirma INT,
							 @pidProceso INT, @pFirmante VARCHAR(100),
							 @pusuarioid VARCHAR(50), @FirmanteLIKE VARCHAR(100), 
							 @lmensaje VARCHAR(100), @rolid INT, @pfichaid INT, @fechaInicio DATE,
							 @fechaFin DATE,@empresaid nvarchar(10), @lugarpagoid nvarchar(14), 
							 @centrocostoid  nvarchar(14),@centrocostoLIKE VARCHAR(100), @idplantilla int'
		IF (@debug = 1)
		BEGIN
			Print LEN(@sqlString)
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @ptipousuarioid , @Pinicio , @Pfin, @PidDocumento, @pidtipodocumento,
							  @pidEstadoContrato,@pidTipoFirma,@pidProceso, @pFirmante,@pusuarioid, 
							  @FirmanteLIKE, @lmensaje, @rolid,@pfichaid, @fechaInicio, @fechaFin,
							  @empresaid, @lugarpagoid, @centrocostoid, @centrocostoLIKE, @idplantilla
                       	
    RETURN     

END
GO
