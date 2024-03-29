USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_subclausulas_listadoPorTipo ]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 10-04-2019
-- Descripcion: Listado de subclausulas por tipo
-- Ejemplo:exec sp_subclausulas_listadoPorTipo
-- =============================================
CREATE PROCEDURE [dbo].[sp_subclausulas_listadoPorTipo ]
	@pidtiposubclausulas INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
			
	SELECT 
		idSubClausula,
		idTipoSubClausula,
		Titulo,
		Descripcion,
		SubClausula
	FROM 
		SubClausulas 
		
	WHERE	
		idTipoSubClausula = @pidtiposubclausulas AND Eliminado = 0
	
END
GO
