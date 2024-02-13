USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerB64]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Obtiene los Documento en Base 64
-- Ejemplo:exec [sp_documentos_obtenerB64] 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerB64]
	@idDocumento INT
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		IF EXISTS (SELECT idDocumento FROM Contratos WHERE idDocumento=@idDocumento AND Eliminado=0 )
			BEGIN
				SELECT [idDocumento]
					  ,[NombreArchivo]
					  ,[Extension]
					  ,[documento]
			  FROM [Documentos]
			  WHERE idDocumento = @idDocumento
  
  
				
			END 
	END
END
GO
