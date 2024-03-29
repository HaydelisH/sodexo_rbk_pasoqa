USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_modifica_aprobador_20210920]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 21/09/2018
-- Descripcion: Actualiza los datos del aprobador del documento 
-- Ejemplo:exec sp_documentos_modifica_aprobador 'xxxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_modifica_aprobador_20210920]
@pidDocumento		VARCHAR(10)	-- id del contrato

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @nuevo_estado INT
	DECLARE @idWorkFlow INT;
    DECLARE @idTipoFirma INT;


	IF EXISTS(SELECT idDocumento FROM Contratos WHERE idDocumento= @pidDocumento) 
		BEGIN 
            SELECT @idTipoFirma = idTipoFirma FROM Contratos WHERE idDocumento= @pidDocumento
            IF (@idTipoFirma = 2)
                BEGIN 
                    --Buscar el flujo del documento 
                    SELECT @idWorkFlow = idWF FROM Contratos WHERE idDocumento = @pidDocumento
                    
                    --Buscar el primer estado del flujo
                    SELECT @nuevo_estado = idEstadoWF FROM WorkflowEstadoProcesos WHERE idWorkflow = @idWorkFlow AND Orden = 1
                    
                    UPDATE Contratos SET 
                    idEstado = @nuevo_estado
                    WHERE idDocumento = @pidDocumento
                    
                    --Enviar notificacion al correo
                    INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) SELECT idDocumento,@nuevo_estado,RutFirmante FROM ContratoFirmantes WHERE idDocumento = @pidDocumento AND idEstado = @nuevo_estado ORDER BY Orden ASC
                        
                    SELECT @error= 0
                    SELECT @mensaje = ''				
                END
            ELSE
                BEGIN
                    UPDATE Contratos SET 
                    idEstado = 4 -- Generado Manualmente
                    WHERE idDocumento = @pidDocumento
                END
		END
	ELSE
		BEGIN
			SELECT @mensaje = 'Contrato no existe'
			SELECT @error = 1			
		END			
		
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
