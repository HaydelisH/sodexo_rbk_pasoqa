USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_modifica_estadoDocumento]    Script Date: 1/22/2024 7:21:13 PM ******/
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
CREATE PROCEDURE [dbo].[sp_documentos_modifica_estadoDocumento]
	@pidDocumento		VARCHAR(10),	-- id del contrato
	@pidEstado			INT,
	@Observacion		VARCHAR(100)
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @idWF INT
	DECLARE @min INT;
		

	IF EXISTS(SELECT idWF FROM Contratos WHERE idDocumento = @pidDocumento) 
		BEGIN 
			UPDATE Contratos SET 
				idEstado = @pidEstado,
				Observacion = @Observacion
			WHERE 
				idDocumento = @pidDocumento
				
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
