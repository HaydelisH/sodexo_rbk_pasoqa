USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_agregarDocumento]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/07/2018
-- Descripcion: Agrega un Documento nuevo
-- Ejemplo:exec sp_documentos_agregarDocumento 'nombre','pdf'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_agregarDocumento]
	@idDocumento INT,
	@NombreArchivo VARCHAR(50),
	@Extension VARCHAR(10),
	@B64 VARCHAR(MAX)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @Archivo VARBINARY(MAX);
	
	SELECT @Archivo= CONVERT(varbinary(max), @B64)
	
	INSERT INTO Documentos (idDocumento,NombreArchivo, Extension, documento) 
	VALUES (@idDocumento, @NombreArchivo, @Extension, @Archivo)
 
	--Buscar el estado de notificación del documento 
	DECLARE @idEstado INT
	DECLARE @Rut VARCHAR(14);

	DECLARE @RutFirmante VARCHAR(10)
	DECLARE @notifnuevousuario INT

	--Buscar el estado en el que se creó el documento
	SELECT @idEstado = idestado FROM Contratos WHERE idDocumento = @idDocumento
	
    --Si es el empleado 
	IF( @idEstado = 3 ) 
		BEGIN 
			/*SELECT @Rut = Rut FROM ContratoDatosVariables WHERE idDocumento = @idDocumento
			INSERT INTO EnvioCorreos(documentoid, CodCorreo, RutUsuario) VALUES(@idDocumento ,@idEstado, @Rut)*/
			--obtenenos el rut del firmante
			SELECT @RutFirmante = RutFirmante FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @idEstado ORDER BY Orden ASC
			--csb 26-05-2021 para rescatar marca si notificamos como nuevo usuario
			--si es 1 notificamos para crear nuevo usuario
			SET @notifnuevousuario = 0
			SELECT @notifnuevousuario = notifnuevousuario FROM usuarios WHERE usuarioid = @RutFirmante
			--Enviar notificacion al correo
			--csb 26-05-2021 si es espera de firma empleado y no hemos notificado como nuevo usuario
			--se marca en enviacorreo para que notifique que tiene un documento pendiente de firma y que se cree como nuevo usuario 
			--y marcamos para que no notifique como nuevo usuario
			IF (@idEstado = 3 AND @notifnuevousuario  = 1 )
				BEGIN
					INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) VALUES (@idDocumento,17,@RutFirmante)
					--INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) SELECT idDocumento,@idEstado,RutFirmante FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @idEstado ORDER BY Orden ASC
					UPDATE usuarios SET notifnuevousuario = 2 WHERE usuarioid = @RutFirmante
				END
			ELSE
				BEGIN
					--csb 26-05-2021 para notificar que tiene un documento por firmar
					INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) VALUES (@idDocumento,@idEstado,@RutFirmante)
				END
		END

	--Si es el otro firmante
	IF( @idEstado IN (2,9,10) ) 
		BEGIN 
			SELECT @Rut = RutFirmante FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @idEstado
			INSERT INTO EnvioCorreos(documentoid, CodCorreo, RutUsuario) VALUES(@idDocumento ,@idEstado, @Rut)
		END
END

GO
