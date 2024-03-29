USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_eliminardoc]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 08/10/2018
-- Descripcion:	Eliminar Documento, con la variante que elimina el registo a que ficha pertenece 
-- Ejemplo:exec sp_documentos_eliminardoc 111,'11111111-1,1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_eliminardoc]
	@pdocumentoid		NUMERIC (18,0),	-- identificador del documento
	@pusuarioid			NVARCHAR(10),	-- usuario que esta eliminando el documento
	@fichaid			INT,			-- id de ficha 
	@pOrigen			INT,
	@pidTipoSubida		INT
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @cant		INT
	
	--Validar si es el ultimo Documento de la Ficha 
	SELECT @cant = COUNT(*) FROM fichasdocumentos where fichaid = @fichaid

	--IF ( @cant = 1 )
	--	BEGIN 
	--		SET @error = 1
	--		SET @mensaje = 'No se pueden eliminar todos los documentos de la Ficha '
			
	--		SELECT @error AS error, @mensaje AS mensaje; 
	--		RETURN;
	--	END
	
	BEGIN TRANSACTION 
	BEGIN TRY
	
		IF EXISTS(SELECT documentoid FROM [Smu_Gestor].[dbo].[documentos] WHERE documentoid = @pdocumentoid)
			BEGIN
				
				--Documentos 
				INSERT INTO [Smu_Gestor].[dbo].[documentoselim](documentoid, documento, nombrearchivo)
				SELECT documentoid, documento, nombrearchivo  FROM [Smu_Gestor].[dbo].[Documentos] WHERE documentoid = @pdocumentoid
				
				--Documentosinfo
				INSERT INTO [Smu_Gestor].[dbo].[documentosinfoelim](documentoid, tipodocumentoid, empleadoid, empresaid, centrocostoid, fechadocumento, fechacreacion, fechatermino, NumeroContrato, Origen, idDocEnviado, usuarioidelim, fechacreacionelim)
				SELECT documentoid, tipodocumentoid, empleadoid, empresaid, centrocostoid, fechadocumento, fechacreacion, fechatermino, @pdocumentoid, Origen, idDocEnviado,@pusuarioid, GETDATE() FROM [Smu_Gestor].[dbo].[documentosinfo] 
				WHERE documentoid = @pdocumentoid
				
				--Eliminar de documentos
				DELETE FROM [Smu_Gestor].[dbo].[Documentos] WHERE documentoid = @pdocumentoid
				DELETE FROM [Smu_Gestor].[dbo].[documentosinfo] WHERE documentoid = @pdocumentoid
				
			END
		ELSE
			BEGIN
				SET @error = 1
				SET @mensaje = 'El Documento no existe'
			END
	
		IF EXISTS ( SELECT fichaid FROM fichasdocumentos WHERE fichaid = @fichaid AND documentoid = @pdocumentoid AND idFichaOrigen = @pOrigen AND idTipoSubida = @pidTipoSubida ) 
			BEGIN 
				DELETE FROM fichasdocumentos WHERE fichaid = @fichaid AND documentoid = @pdocumentoid AND idFichaOrigen = @pOrigen AND idTipoSubida = @pidTipoSubida
			END
		
	COMMIT TRANSACTION
	END TRY

	BEGIN CATCH
	ROLLBACK TRANSACTION 
		
		SET @error		= ERROR_NUMBER()
		SET @mensaje	= ERROR_MESSAGE()
			
	END CATCH
	
	SELECT @error AS error, @mensaje AS mensaje; 
	
END
GO
