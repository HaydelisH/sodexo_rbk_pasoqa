USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_agregarDocumento]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 09/07/2019
-- Descripcion: Agregar un documento a una ficha 
-- Ejemplo:exec sp_fichas_agregarDocumento 10034,..
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_agregarDocumento] 
	@tipodocumentoid	INT,
	@documento			VARCHAR(MAX),
	@nombrearchivo      VARCHAR(100),
	@usuarioid			VARCHAR(50),
	@fichaid			INT,
	@pOrigen			INT,
	@pidTipoSubida		INT
AS	
BEGIN
	SET NOCOUNT ON;
	DECLARE @iddoc INT; 
	DECLARE @idfic INT; 
	DECLARE @archivo VARBINARY(MAX)
	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(200)
	DECLARE @cc	NVARCHAR(14)
	DECLARE @em VARCHAR(10)
	DECLARE @lp NVARCHAR(14)
	DECLARE @band INT
	DECLARE @empleadoid VARCHAR(10)
	DECLARE @empresaid VARCHAR(10)
	DECLARE @centrocostoid NVARCHAR(14);
	
	DECLARE @correo NVARCHAR (50);
	DECLARE @envios INT;
	
	--Convertir el documento en b64
	SELECT @archivo= CONVERT(varbinary(max), @documento)
	
	--Buscar los datos necesarios 
	SELECT @empleadoid = RutTrabajador, @centrocostoid = CodDivPersonal FROM fichasDatosImportacion WHERE fichaid = @fichaid
	SELECT @empresaid = empresaid FROM centroscosto WHERE centrocostoid = @centrocostoid
	
	--Validar empleado 
	SET @band = 0
	
	IF EXISTS ( SELECT empleadoid FROM [Smu_Gestor].[dbo].[empleados] WHERE empleadoid = @empleadoid)
		BEGIN 
					
			SELECT 
				@em = empresaid,
				@cc = centrocostoid
			FROM 
				[Smu_Gestor].[dbo].[empleados]
			WHERE 
				empleadoid = @empleadoid
				
			SET @band = 1
		END 
		
	BEGIN TRANSACTION 
	BEGIN TRY	
		
		IF ( @band = 0 ) 
			BEGIN 
				--Insertar en la tabla de DocumentosInfo
				INSERT INTO [Smu_Gestor].[dbo].[documentosinfo]( tipodocumentoid, empleadoid, empresaid, centrocostoid, fechadocumento, fechacreacion, fechatermino, NumeroContrato, Origen, idDocEnviado,documentoidorig, validacionpaginaid, usuarioid, validacionid, codigoempleado)
							VALUES( @tipodocumentoid,@empleadoid, @empresaid, @centrocostoid, NULL, GETDATE(),NULL,0,0,0,NULL,0,@usuarioid,0, NULL)
			END
		ELSE
			BEGIN
				--Insertar en la tabla de DocumentosInfo
				INSERT INTO [Smu_Gestor].[dbo].[documentosinfo]( tipodocumentoid, empleadoid, empresaid, centrocostoid, fechadocumento, fechacreacion, fechatermino, NumeroContrato, Origen, idDocEnviado, documentoidorig, validacionpaginaid, usuarioid, validacionid, codigoempleado)
							VALUES( @tipodocumentoid,@empleadoid, @em, @cc, NULL, GETDATE(),NULL,0,0,0,NULL,0,@usuarioid,0, NULL)
	
			END
			 
		SET @iddoc = @@IDENTITY
		
		--Guardar archivo de subida		    
		INSERT INTO [Smu_Gestor].[dbo].[documentos](documentoid,documento,nombrearchivo,tipoconversion)
		VALUES(@iddoc,@archivo,@nombrearchivo,1)
					    
		--Insertar relacion
		INSERT INTO fichasdocumentos (fichaid, documentoid, idFichaOrigen, idTipoSubida)
							   VALUES(@fichaid, @iddoc, @pOrigen , @pidTipoSubida)
							   
	--fin crea registro para notificar
	COMMIT TRANSACTION
	END TRY

	BEGIN CATCH
	ROLLBACK TRANSACTION 
	
		SET @error		= ERROR_NUMBER()
		SET @mensaje	= ERROR_MESSAGE()
			
	END CATCH
	
	SELECT @iddoc As documentoid;
	
	RETURN;
END
GO
