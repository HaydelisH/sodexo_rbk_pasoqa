USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_modificarDocCode]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 13/07/2018
-- Descripcion: Actualizar Codigo del Documento que se subio
-- Ejemplo:exec sp_documentos_modificarDocCode 1,'CD0000000215515'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_modificarDocCode]
    @pAccion CHAR(60),
	@idContrato INT,
	@DocCode VARCHAR(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	IF (@pAccion='modificar') 
	BEGIN
		IF EXISTS ( SELECT idDocumento FROM Contratos WHERE idDocumento = @idContrato AND Eliminado = 0 )
			BEGIN
				UPDATE Contratos
				SET 
				DocCode = @DocCode
				WHERE
				idDocumento = @idContrato
			END 
	END
END
GO
