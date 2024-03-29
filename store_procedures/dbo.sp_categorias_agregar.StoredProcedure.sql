USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_categorias_agregar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/28/2018
-- Descripcion:  Agregar una Categoria
-- Ejemplo:exec sp_categorias_agregar 'xxxx',1,'descripcion','nombre'
-- =============================================
CREATE PROCEDURE [dbo].[sp_categorias_agregar]
	@pAccion CHAR(60),
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
    IF (@pAccion='agregar')  
    BEGIN
		INSERT INTO Categorias(Descripcion, Titulo, Eliminado) VALUES (@Descripcion, @Titulo, 0) 
		SELECT @@IDENTITY AS idCategoria
    END    
END
GO
