USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_categorias_idmax]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/28/2018
-- Descripcion:  Obtiene el Id de maximo valor de Categorias
-- Ejemplo:exec sp_categorias_idmax
-- =============================================
CREATE PROCEDURE [dbo].[sp_categorias_idmax]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
   SELECT @total = MAX(idCategoria) FROM Categorias
   SET @total = @total + 1
  
   SELECT @total AS total
END
GO
