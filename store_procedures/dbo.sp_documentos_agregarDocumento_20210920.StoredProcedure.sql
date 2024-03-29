USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_agregarDocumento_20210920]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/07/2018
-- Descripcion: Agrega un Documento nuevo
-- Ejemplo:exec sp_documentos_agregarDocumento 'nombre','pdf'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_agregarDocumento_20210920]
	@idDocumento INT,
	@NombreArchivo VARCHAR(50),
	@Extension VARCHAR(10),
	@B64 VARCHAR(MAX)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @Archivo VARBINARY(MAX);
	
	SELECT @Archivo= CONVERT(varbinary(max), @B64)
	
	INSERT INTO Documentos (idDocumento,NombreArchivo, Extension, documento) 
	VALUES (@idDocumento, @NombreArchivo, @Extension, @Archivo)
 
END
GO
