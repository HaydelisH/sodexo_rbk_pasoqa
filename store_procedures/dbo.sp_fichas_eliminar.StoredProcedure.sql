USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 08/10/2018
-- Descripcion: Eliminar una ficha 
-- Ejemplo:exec sp_fichas_eliminar
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_eliminar]
	@fichaid		INT 
AS	
BEGIN
	SET NOCOUNT ON;
	
	IF EXISTS ( SELECT fichaid FROM fichas WHERE fichaid = @fichaid) 
		BEGIN
			--Eliminar de la tabla de fichas
			DELETE FROM fichas WHERE fichaid = @fichaid
		END
		
	IF EXISTS ( SELECT fichaid FROM fichasdocumentos WHERE fichaid = @fichaid ) 
		BEGIN 
			--Eliminar de la tabla de fichasdocumentos
			DELETE FROM fichasdocumentos WHERE fichaid = @fichaid 
		END 
	RETURN;
END
GO
