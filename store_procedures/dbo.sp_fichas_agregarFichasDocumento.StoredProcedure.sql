USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_agregarFichasDocumento]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 12-07-2019
-- Descripcion:Agregar el resultado de la generacion de un documento
-- Ejemplo:exec sp_fichas_agregarFichaDocumento 
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_agregarFichasDocumento]
	@pfichaid INT,
	@pdocumentoid INT,
	@pidFichaOrigen INT
AS	
BEGIN
	SET NOCOUNT ON;
	
	INSERT INTO fichasdocumentos(fichaid,documentoid, idFichaOrigen )
	VALUES( @pfichaid, @pdocumentoid, @pidFichaOrigen )
	 
	RETURN;
END
GO
