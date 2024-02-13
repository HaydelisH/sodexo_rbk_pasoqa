USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_modifica_estado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 17/07/2018
-- Descripcion: modifica estado documento
-- Ejemplo:exec sp_documentos_modifica_estado 'agregar',1,1,'xxxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_modifica_estado]
@pidDocumento		VARCHAR(10)	-- id del contrato

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @idWF INT
	DECLARE @min INT;
		

	IF EXISTS(SELECT idWF FROM Contratos WHERE idDocumento = @pidDocumento) 
		BEGIN 
			--Consulto el minimo estado del flujo de firma 
			SELECT @idWF = idWF FROM Contratos WHERE idDocumento=@pidDocumento
			
			--Consultar el estado cuando el orden es 1
			SELECT @min = idEstadoWF FROM WorkflowEstadoProcesos WHERE idWorkflow = @idWF AND Orden = 1
			
			UPDATE Contratos SET 
			idEstado= @min
			WHERE idDocumento = @pidDocumento
			
			--Enviar notificacion al correo
			INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) SELECT @pidDocumento,@min,RutFirmante FROM ContratoFirmantes WHERE idDocumento = @pidDocumento AND idEstado = @min
				
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
