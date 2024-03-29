USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerDocumento]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 28/08/2018
-- Descripcion: Obtiene los datos del Contrato
-- Ejemplo:exec [sp_documentos_obtener] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerDocumento]
	@idDocumento INT
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
		IF EXISTS (SELECT idDocumento FROM Contratos WHERE (idDocumento=@idDocumento) )
			BEGIN
				SELECT	
					C.idDocumento,
					CONVERT(VARCHAR(10),C.FechaCreacion,105) AS FechaCreacion		
				FROM
					Contratos C
				WHERE C.idDocumento = @idDocumento
				
			END 
	END
END
GO
