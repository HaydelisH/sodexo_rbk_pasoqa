USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosvigentes_rechazarPendienteTerminado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 12-11-2018
-- Descripcion: rechazo de contrato a estado pendiente terminado
-- Ejemplo:exec sp_documentosvigentes_rechazarPendienteTerminado 501,'observaciones',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosvigentes_rechazarPendienteTerminado]
@pidcontrato		INT,			-- id del contrato
@pobservacion		VARCHAR(200)

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	
	IF EXISTS(SELECT idEstado FROM Contratos WHERE idDocumento= @pidcontrato) 
		BEGIN 
			UPDATE  Contratos 
			SET idEstado = 7, Observacion = @pobservacion
			WHERE idDocumento = @pidcontrato
			
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
