USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_modificarDocumento]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 19/07/2018
-- Descripcion: Actualizar el documento firmado
-- Ejemplo:exec sp_documentos_modificarDocumento 1,'zzxxxzxzasckqhBQHJ'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_modificarDocumento]
	@idDocumento INT,
	@B64 VARCHAR(MAX)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @Archivo VARBINARY(MAX);
	
	SELECT @Archivo= CONVERT(varbinary(max), @B64)
	
	UPDATE Documentos SET documento = @Archivo
	WHERE idDocumento = @idDocumento
 
END
GO
