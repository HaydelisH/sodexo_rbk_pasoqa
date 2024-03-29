USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_idMax]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/07/2018
-- Descripcion: Obtiene el numero del ultimo documento realizado
-- Ejemplo:exec sp_documentos_idMax
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_idMax]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @idDocumento INT;
	
	SELECT @idDocumento = MAX(idDocumento) FROM Documentos 
	
	SELECT @idDocumento AS idDocumento
 
END
GO
