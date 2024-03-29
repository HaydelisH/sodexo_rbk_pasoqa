USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosvigentes_modifica_estado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 28/08/2018
-- Descripcion: modifica estado documento
-- Ejemplo:exec sp_documentosvigentes_modifica_estado 501,4
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosvigentes_modifica_estado]
@pidcontrato		INT,	-- id del contrato
@pidestado			INT	-- id del estado del contrato

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	
	IF EXISTS(SELECT idEstado FROM Contratos WHERE idDocumento= @pidcontrato) 
		BEGIN 
			UPDATE  Contratos SET idEstado = @pidestado 
			WHERE idDocumento = @pidcontrato
			AND idTipoFirma = 1		
			
			--Enviar notificacion al correo
			INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) SELECT @pidcontrato,@pidestado,RutFirmante FROM ContratoFirmantes WHERE idDocumento = @pidcontrato AND idEstado = @pidestado
			
			SELECT @error= 0
			SELECT @mensaje = ''	
		END
	ELSE
		BEGIN
			SELECT @mensaje = 'Contrato no existe'
			SELECT @error = 1			
		END			
		
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
