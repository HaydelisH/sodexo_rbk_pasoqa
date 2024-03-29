USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_modificarNombre]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Ejemplo:exec sp_documentos_modificarDocCode 1,'CD0000000215515'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_modificarNombre]
	@idDocumento INT,
	@NombreArchivo VARCHAR(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	
	
	IF EXISTS ( SELECT D.idDocumento FROM Documentos D INNER JOIN Contratos C ON D.idDocumento = C.idDocumento where  C.idDocumento = @idDocumento AND C.Eliminado = 0 )
		BEGIN
			
			UPDATE Documentos SET 
				NombreArchivo = @NombreArchivo
			WHERE
				idDocumento = @idDocumento
			
			SELECT @error= 0
			SELECT @mensaje = ''		
		END 
	ELSE
		BEGIN 
			SELECT @mensaje = 'Este Documento no exite'
			SELECT @error = 1			
		END

END
GO
