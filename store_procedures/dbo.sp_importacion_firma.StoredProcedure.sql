USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_importacion_firma]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
CREATE PROCEDURE [dbo].[sp_importacion_firma]
@pAccion 			CHAR(60),		-- accion a realizar
@pusuarioid			NVARCHAR(10),	-- codigo usuario
@ppagina			INT,			-- nro pagina procesada
@ppaginainicio		INT,			-- nro pagina inicio
@ppaginafin			INT,			-- nro pagina fin
@pempleadoid		VARCHAR(10),	-- codigo del empleado 
@pempresaid			VARCHAR(10),	-- codigo empresa
@pestado			INT,			-- estado
@pobservacion		NVARCHAR(200),	-- codigo empresa
@prutrepresentantes	NVARCHAR(100),	-- rut representantes
@ptipodocumentoid	INT,			-- tipo docuemnto en firma RBK
@pprocesofirma		INT,			-- proceso de firma RBK
@pfechadocumento	DATE,			-- fecha de documento
@ptotalpaginas		INT,			-- total paginas pdf
@preprocesado		INT				-- total paginas pdf	

	
AS	
BEGIN
	SET NOCOUNT ON;
		
 	DECLARE @error			INT
	DECLARE @mensaje		VARCHAR(100)
	DECLARE @basegestor		VARCHAR(50)
	DECLARE @sqlstring		nvarchar(1500)
	DECLARE @parametros		nvarchar(150)

	SET @basegestor = (select parametro from parametros where [idparametro] = 'gestor')
		
	IF (@pAccion='Grabar') 
	BEGIN
		IF NOT EXISTS(SELECT usuarioid FROM importacion_firma WHERE usuarioid = @pusuarioid AND pagina = @ppagina ) 
			BEGIN 
				INSERT INTO importacion_firma
				(usuarioid,pagina,paginainicio, paginafin,empleadoid,empresaid,estado,observacion,rutrepresentantes,tipocontrato,procesofirma,fechadocumento,totalpaginas)
				VALUES
				(@pusuarioid,@ppagina,@ppaginainicio,@ppaginafin,@pempleadoid,@pempresaid,@pestado,@pobservacion,@prutrepresentantes,@ptipodocumentoid,@pprocesofirma,@pfechadocumento,@ptotalpaginas);
				
			END
		ELSE
			BEGIN
				UPDATE importacion_firma 
				SET
				empleadoid = @pempleadoid, 
				empresaid = @pempresaid,
				estado = @pestado,
				observacion = @pobservacion,
				tipocontrato = @ptipodocumentoid,
				procesofirma = @pprocesofirma,
				fechadocumento = @pfechadocumento,
				reprocesado  = @preprocesado
				WHERE usuarioid = @pusuarioid 
				AND pagina = @ppagina 
			END		
			
			
		SELECT @error= 0
		SELECT @mensaje = ''		

	END	
	
	IF (@pAccion='Listado') 
	BEGIN
		
 		SET @sqlstring = N'SELECT IMP.usuarioid,
		IMP.usuarioid,
		IMP.pagina,
		IMP.paginainicio,
		IMP.paginafin,
		IMP.empleadoid,
		ISNULL(PER.nombre,'''') + '' '' + ISNULL(PER.appaterno,'''') + '' '' + ISNULL(PER.apmaterno,'''') as nombre,
		CASE IMP.estado
			WHEN 0 THEN + ''No enviado''
			WHEN 1 THEN + ''Enviado''
		END as estado,
		IMP.observacion,
		CASE IMP.estado
			WHEN 0 THEN ''checked''
			WHEN 1 THEN ''''
		END as checked_sn,
		CASE IMP.estado
			WHEN 0 THEN ''''
			WHEN 1 THEN ''disabled''
		END as disabled
		
		FROM importacion_firma AS IMP
		LEFT JOIN dbo.empleados AS EMPL ON IMP.empleadoid = EMPL.empleadoid
		LEFT JOIN personas  AS PER  ON EMPL.empleadoid = PER.personaid
		WHERE IMP.usuarioid = ' + '''' + @pusuarioid + '''' +  ' ORDER BY IMP.pagina'

		exec sp_executesql @sqlstring
			
		RETURN
	END
	
	IF (@pAccion='ObtenerEmpleado') 
	BEGIN
		
		SET @sqlstring = 'SELECT empleadoid,
		--ISNULL(PER.nombre,'''') + '' '' + ISNULL(PER.appaterno,'''') + ISNULL(PER.apMaterno,'''') as nombre,
		PER.nombre,
		PER.appaterno,
		PER.apmaterno,
		EMPL.centrocostoid,
		EMPL.lugarpagoid,
		EMPL.RutEmpresa,
		CONVERT(VARCHAR(10),PER.fechanacimiento,105) AS fechanacimiento,
		PER.correo,
		PER.nacionalidad,
		PER.estadocivil,
		PER.estadocivil As idEstadoCivil,
		PER.direccion,
		PER.comuna,
		PER.ciudad
		FROM [dbo].[personas] AS PER
		INNER JOIN [dbo].[empleados] EMPL ON EMPL.empleadoid = PER.personaid 
		WHERE PER.personaid = ' + '''' + @pempleadoid + ''''
											
		exec sp_executesql @sqlstring
		
		RETURN
	END
	   	
	IF (@pAccion='Eliminar') 
	BEGIN
		DELETE FROM importacion_firma
		WHERE usuarioid	= @pusuarioid
		
		SELECT @error= 0
		SELECT @mensaje = ''	

	END
	
	IF (@pAccion='ObtenerProceso') 
	BEGIN
		SELECT top 1
		empresaid,
		rutrepresentantes,
		tipocontrato ,
		procesofirma,
		CONVERT(VARCHAR(10),fechadocumento,105) AS fechadocumento,
		procesofirma,
		totalpaginas
		FROM importacion_firma
		WHERE usuarioid	= @pusuarioid	
				
		RETURN
	END
	
	IF (@pAccion='ObtenerPagina') 
	BEGIN
		SELECT 
		pagina,	
		empresaid,
		rutrepresentantes,
		tipocontrato ,
		CONVERT(VARCHAR(10),fechadocumento,105) AS fechadocumento,
		procesofirma,
		empleadoid,
		totalpaginas
		FROM importacion_firma
		WHERE usuarioid	= @pusuarioid	
		AND pagina = @ppagina		
		RETURN
	END
	
	IF (@pAccion='GrabarEstado') 
	BEGIN
		UPDATE importacion_firma
		SET 
		estado		= @pestado,
		observacion = @pobservacion
		WHERE usuarioid = @pusuarioid
		AND pagina = @ppagina
		
		SELECT @error= 0
		SELECT @mensaje = ''			
	END
	
	IF (@pAccion='ObtenerUltimaPagina') 
	BEGIN
		SELECT TOP 1
		usuarioid,
		pagina,
		paginainicio,
		paginafin,
		empleadoid,
		empresaid,
		rutrepresentantes,
		tipocontrato ,
		procesofirma,
		CONVERT(VARCHAR(10),fechadocumento,105) AS fechadocumento,
		procesofirma,
		empleadoid,
		totalpaginas
		FROM importacion_firma
		WHERE usuarioid = @pusuarioid
		ORDER BY pagina DESC
		
		return
	END

	IF (@pAccion='ObtenerConfiguracion') 
	BEGIN
		SELECT 
		Tipo,
		paginasxdocumento,
		rutadescartar,
		frasevalidacion
		FROM importacion_firma_configuracion
		WHERE Tipo = @ptipodocumentoid
		
		RETURN
	END
	
	IF (@pAccion='DesmarcarReproceso') 
	BEGIN
		UPDATE importacion_firma SET reprocesado = 0 
		WHERE usuarioid = @pusuarioid
		
		SELECT @error= 0
		SELECT @mensaje = ''		
	END
	
	IF (@pAccion='ObtenerNoEnviado') 
	BEGIN
		SELECT 
		pagina,	
		empresaid,
		rutrepresentantes,
		tipocontrato ,
		CONVERT(VARCHAR(10),fechadocumento,105) AS fechadocumento,
		procesofirma,
		empleadoid,
		totalpaginas
		FROM importacion_firma
		WHERE usuarioid	= @pusuarioid	
		AND estado = 0
		AND pagina > @ppagina	
		ORDER BY pagina
			
		RETURN
	END
	
	IF (@pAccion='ObtenerReprocesados') 
	BEGIN
		SELECT COUNT(*) as reprocesados
		FROM importacion_firma
		WHERE usuarioid = @pusuarioid
		AND reprocesado = 1		
		
		RETURN
	END
	
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
