USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosvigentes_rechazo]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 29/08/2018
-- Descripcion: rechazo de contrato
-- Ejemplo:exec sp_documentosvigentes_rechazo 501,'observaciones'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosvigentes_rechazo]
@pidcontrato		INT,			-- id del contrato
@pobservacion		VARCHAR(200)	-- observaciones

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	
	IF EXISTS(SELECT idEstado FROM Contratos WHERE idDocumento= @pidcontrato) 
		BEGIN 
			UPDATE  Contratos 
			SET idEstado = 8, Observacion = @pobservacion
			WHERE idDocumento = @pidcontrato
			AND idEstado <> 6	--6=firmado
			
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
