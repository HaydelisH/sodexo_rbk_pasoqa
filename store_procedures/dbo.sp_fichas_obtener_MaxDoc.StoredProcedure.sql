USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_obtener_MaxDoc]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 13/11/2018
-- Descripcion:	Obtener el ultimo documento creado para una ficha
-- Ejemplo:exec sp_fichas_obtener 
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_obtener_MaxDoc]
	@fichaid            INT
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
		MAX(fd.documentoid) As documentoid
	FROM fichas f
		INNER JOIN fichasdocumentos fd ON f.fichaid = fd.fichaid
	WHERE  f.fichaid = @fichaid
               
	RETURN;
END
GO
