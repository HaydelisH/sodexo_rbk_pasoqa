USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_descargas_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/11/2018
-- Descripcion:  Obtener los datos de un archivo
-- Ejemplo:exec sp_descargas_obtener 'idDescarga'
-- =============================================
CREATE PROCEDURE [dbo].[sp_descargas_obtener]
	@idDescarga		INT
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
		Ruta,
		Tipo,
		CONVERT(CHAR(10), Fecha,105) As Fecha,
		B64, 
		Descripcion
	FROM
		Descargas
	WHERE
		idDescarga = @idDescarga
		
   
END
GO
