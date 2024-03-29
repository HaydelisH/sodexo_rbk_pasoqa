USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_descargas_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/11/2018
-- Descripcion:  Muestra todos los archivos
-- Ejemplo:exec sp_descargas_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_descargas_listado]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	SELECT 
		idDescarga,
		Nombre,
		Tipo,
		Ruta,
		CONVERT(CHAR(10), Fecha,105) As Fecha,
		Descripcion
	FROM
		Descargas
   
END
GO
