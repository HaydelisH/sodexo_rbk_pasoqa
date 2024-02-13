USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_categorias_modificar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/28/2018
-- Descripcion:  Actualiza los datos de una Categoria
-- Ejemplo:exec sp_categorias_modificar
-- =============================================
CREATE PROCEDURE [dbo].[sp_categorias_modificar]
	@pAccion CHAR(60),
	@idCategoria INT,
	@Descripcion VARCHAR (50),
	@Titulo VARCHAR (50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='modificar') 
    BEGIN
		IF EXISTS (SELECT idCategoria FROM Categorias WHERE idCategoria = @idCategoria AND Eliminado=0)
			BEGIN
				UPDATE Categorias SET Descripcion= @Descripcion, Titulo = @Titulo 
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
