USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_envioCorreos_renotificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 1/09/2016
-- Descripcion:	Obtener tipo de usuario 
-- Ejemplo:exec sp_envioCorreos_renotificar 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_envioCorreos_renotificar]
@idDocumento	INT,				-- identificador del tipo de usuario
@rutUsuario     VARCHAR(10)
AS	
BEGIN
	SET NOCOUNT ON;
	IF @rutUsuario = ''
	BEGIN
		UPDATE EnvioCorreos SET
			EnviaCorreo = 0,
			FechaEnvio = NULL
		WHERE
			documentoid = @idDocumento
			AND FechaCreacion = (
				SELECT TOP 1 FechaCreacion
				FROM EnvioCorreos
				WHERE documentoid = @idDocumento
				ORDER BY FechaCreacion DESC
			)
			AND EnviaCorreo = 1
	END
	ELSE
	BEGIN
		UPDATE EnvioCorreos SET
			EnviaCorreo = 0,
			FechaEnvio = NULL
		WHERE
			documentoid = @idDocumento
			AND Correlativo = (
				SELECT TOP 1 Correlativo
					FROM EnvioCorreos
					INNER JOIN ContratoFirmantes 
						ON ContratoFirmantes.idDocumento = EnvioCorreos.documentoid
						AND ContratoFirmantes.idDocumento = @idDocumento
						AND ContratoFirmantes.RutFirmante = EnvioCorreos.RutUsuario
						AND ContratoFirmantes.RutFirmante = @rutUsuario
						AND ContratoFirmantes.Firmado = 0
					WHERE EnvioCorreos.EnviaCorreo = 1
					ORDER BY FechaCreacion DESC
			)
			AND EnviaCorreo = 1
	END
	RETURN
END
GO
