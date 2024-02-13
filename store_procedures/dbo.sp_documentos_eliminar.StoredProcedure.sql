USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/09/2018
-- Descripcion: Eliminar los registros de un Documento 
-- Ejemplo:exec sp_documentos_eliminar 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_eliminar]
	@idDocumento INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	
	IF EXISTS (SELECT idDocumento FROM Contratos WHERE idDocumento = @idDocumento )
		BEGIN
			UPDATE Contratos SET Eliminado = 1 WHERE idDocumento = @idDocumento
		END
END
GO
