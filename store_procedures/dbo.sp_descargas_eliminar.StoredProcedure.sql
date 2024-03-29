USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_descargas_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/11/2018
-- Descripcion: Eliminar el registro de un archivo
-- Ejemplo:exec sp_descargas_eliminar 'idDescarga'
-- =============================================
CREATE PROCEDURE [dbo].[sp_descargas_eliminar]
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
	DELETE 
	FROM
		Descargas
	WHERE
		idDescarga = @idDescarga
		
   
END
GO
