USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_documentosvigentes_totalPorEstructura_20221011_CSB]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Modificado por: Gdiaz 11/01/2021
-- [sp_documentosvigentes_listadoPorEstructura] 1,1,100,0,0,0,2,0,'','26131316-2',0,NULL,NULL,'','1','1',1
-- [sp_rl_documentosvigentes_totalPorEstructura] 1,1,100,0,0,0,2,0,'','26131316-2',0,NULL,NULL,'','1','1',1
-- Modificado por RC 2020/10/05 AND @empresaid != '0' Modifica condicion por venir empresa con 0
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_documentosvigentes_totalPorEstructura_20221011_CSB]

	@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@pidDocumento			INT,	-- Id Documento
	@pidtipodocumento		INT,	-- TipoDocumento
	@pidEstadoContrato		INT,	-- Estado del Contrato
	@pidTipoFirma			INT,	-- 1 = Manual y 2 = Elctronico
	@pidProceso				INT,	-- Id del Proceso
	@pRutFirmante		varchar(10),	-- Firmante del Contrato
	@pusuarioid		varchar(50),	-- id usuario
	@pfichaid				INT,	-- Fichaid
	@fechaInicio			DATE,	-- Fecha inicio
	@fechaFin				DATE,	-- Fecha fin 
	@empresaid		nvarchar(10),	-- Rut empresa 
	@lugarpagoid	nvarchar(14),	-- Lugar de pago 
	@centrocostoid  nvarchar(14),	-- Centro de costo 
	@idPlantilla			INT,	-- Plantilla
	@rlTipoDocumento nvarchar(200), -- Nombre del documento
	@rutproveedor		nvarchar(10),	-- Rut empresa 
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta

AS
BEGIN
	SET NOCOUNT ON;
		
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	DECLARE @nl   char(2) = char(13) + char(10)
	DECLARE @FirmanteLIKE	VARCHAR(100)	
	DECLARE @rlTipoDocumentoLIKE	VARCHAR(202)	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT		
	DECLARE @centrocostoLIKE VARCHAR(100);					  
    
    SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos			
	         
	DECLARE @sqlString nvarchar(max)
	
	--SET @FirmanteLIKE = '%' + @pRutFirmante + '%'
	SET @centrocostoLIKE = '%' + @centrocostoid + '%'
	SET @rlTipoDocumentoLIKE = '%' + @rlTipoDocumento + '%'
	 
    DECLARE @vdecimal DECIMAL (9,2)
    
    DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)

	
	--Buscar el rol del usuario
	SELECT @rolid = rolid FROM Usuarios WHERE usuarioid = @pusuarioid	
	
	DECLARE @idEstado INT
	
	SET @sqlString = N';
	With DocumentosTabla
      	as (
			SELECT C.idDocumento, PL.idPlantilla, PL.idTipoDoc,idproceso, C.idestado, 
				C.idtipofirma,FD.fichaid,C.FechaCreacion, CDV.Rut, C.RutEmpresa, 
				C.idWf, FechaUltimaFirma,CDV.LugarPagoid, CDV.CentroCosto
				, CDV.rlTipoDocumento
				, rl_Proveedores.RutProveedor
				, rl_Proveedores.NombreProveedor
				,ROW_NUMBER() Over( Order by C.idDocumento DESC) As pLinea
			FROM Contratos C
			INNER JOIN Plantillas PL ON PL.idPlantilla = C.idPlantilla
			INNER JOIN tiposdocumentosxperfil T ON PL.idTipoDoc = T.idtipodoc AND T.tipousuarioid = @ptipousuarioid
			INNER JOIN ContratoDatosVariables CDV ON CDV.idDocumento = C.idDocumento
			INNER JOIN ContratoFirmantes ON ContratoFirmantes.idDocumento = C.idDocumento
			INNER JOIN accesoxusuarioccosto ACC	
				ON 
				ACC.empresaid = C.RutEmpresa 
				AND ACC.lugarpagoid = CDV.lugarpagoid 
				AND ACC.centrocostoid = CDV.CentroCosto 
				AND ACC.usuarioid = @pusuarioid 
			LEFT JOIN fichasdocumentos FD ON C.idDocumento = FD.documentoid							
			INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = C.idWF
				AND WorkflowProceso.tipoWF = 1
			INNER JOIN rl_Proveedores ON rl_Proveedores.RutProveedor = CDV.rlRutProveedor
	' + @nl

	SET @sqlString += N' WHERE  
			C.Eliminado = 0 AND 
			tipousuarioid = @ptipousuarioid	 
		' + @nl
		IF (@rlTipoDocumento != '')
		BEGIN
			SET @sqlString += ' AND CDV.rlTipoDocumento LIKE @rlTipoDocumentoLIKE'
		END
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
			SET @sqlString += ' AND C.idEstado IN (2,3,10) ' + @nl
		END
		IF (@pidDocumento != 0)
		BEGIN
			SET @sqlString += ' AND C.idDocumento = @pidDocumento ' + @nl
		END
		IF (@pRutFirmante != '')
		BEGIN
			SET @sqlString += ' AND  ContratoFirmantes.RutFirmante = @pRutFirmante ' + @nl
		END	
		IF (@pidtipodocumento != 0)
		BEGIN
			SET @sqlString += ' AND PL.idTipoDoc = @pidtipodocumento' + @nl
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
		IF ( @empresaid != '' AND @empresaid != '0' )  
		BEGIN
			SET @sqlString += ' AND C.RutEmpresa = @empresaid' + @nl
		END	
		IF ( @lugarpagoid != '' AND  @lugarpagoid != '0')  
		BEGIN
			SET @sqlString += ' AND CDV.LugarPagoid = @lugarpagoid' + @nl
		END		
		IF ( @centrocostoid != '' AND @centrocostoid != '0')  
		BEGIN
			SET @sqlString += ' AND CDV.CentroCosto LIKE @centrocostoLIKE' + @nl
		END	
		IF ( @idPlantilla != '' AND @idPlantilla != '0')  
		BEGIN
			SET @sqlString += ' AND PL.idPlantilla = @idPlantilla' + @nl
		END	
		IF ( @rutproveedor != '' AND @rutproveedor != '0' )  
		BEGIN
			SET @sqlString += ' AND rl_Proveedores.RutProveedor = @rutproveedor' + @nl
		END	

	SET @sqlString += N' GROUP BY
			C.idDocumento, PL.idPlantilla, PL.idTipoDoc,idproceso, C.idestado, 
			C.idtipofirma,FD.fichaid,C.FechaCreacion, CDV.Rut, C.RutEmpresa, 
			C.idWf, FechaUltimaFirma,CDV.LugarPagoid, CDV.CentroCosto
			, CDV.rlTipoDocumento
			, rl_Proveedores.RutProveedor
			, rl_Proveedores.NombreProveedor
		' + @nl

	SET @sqlString += N') SELECT
			@totalorig = count(DT.idDocumento)
		FROM DocumentosTabla DT'
	
	DECLARE @Parametros nvarchar(max)
			
	SET @Parametros =  N'@ptipousuarioid INT, @Pinicio INT,
						@Pfin INT, @PidDocumento INT, @pidtipodocumento INT,
						@pidEstadoContrato INT, @pidTipoFirma INT,
						@pidProceso INT, @pRutFirmante VARCHAR(100),
						@pusuarioid VARCHAR(50), @FirmanteLIKE VARCHAR(100), 
						@lmensaje VARCHAR(100), @rolid INT, @pfichaid INT, @fechaInicio DATE,
						@fechaFin DATE,@empresaid nvarchar(10), @lugarpagoid nvarchar(14), 
						@centrocostoid  nvarchar(14),@centrocostoLIKE VARCHAR(100), @idPlantilla INT, @rlTipoDocumento VARCHAR(200),
						@rlTipoDocumentoLIKE  VARCHAR(202),@rutproveedor nvarchar(10)
						,@totalorig INT OUTPUT'
				
	IF (@debug = 1)
	BEGIN
		PRINT @sqlString
	END

	EXECUTE sp_executesql @sqlString, @Parametros, 
						@ptipousuarioid , @Pinicio , @Pfin, @PidDocumento, @pidtipodocumento,
						@pidEstadoContrato,@pidTipoFirma,@pidProceso, @pRutFirmante,@pusuarioid, 
						@FirmanteLIKE, @lmensaje, @rolid,@pfichaid, @fechaInicio, @fechaFin,
						@empresaid, @lugarpagoid, @centrocostoid, @centrocostoLIKE, @idPlantilla,@rlTipoDocumento,@rlTipoDocumentoLIKE
						,@rutproveedor ,@totalorig = @totalorig OUTPUT									
				
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
