USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_categorias_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/28/2018
-- Descripcion:  Obtiene los datos de una Categoria
-- Ejemplo:exec sp_categorias_obtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_categorias_obtener]
	@idCategoria INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
   
    BEGIN
		IF EXISTS (SELECT idCategoria FROM Categorias WHERE idCategoria = @idCategoria AND Eliminado = 0)
			BEGIN
				SELECT idCategoria, Descripcion, Titulo FROM Categorias 
				WHERE idCategoria = @idCategoria
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA CATEGORIA NO EXISTE'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
