USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Obtiene los datos de una Clausula
-- Ejemplo:exec sp_clausulas_obtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_obtener]
	@idClausula INT
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
		IF EXISTS (SELECT idClausula FROM Clausulas WHERE (idClausula=@idClausula AND Eliminado=0))
			BEGIN
				SELECT Clausulas.idClausula, Clausulas.Titulo_Cl, Clausulas.Descripcion_Cl, 
				Clausulas.Texto, Categorias.idCategoria, Categorias.Titulo, 
				Clausulas.RutModificador, Clausulas.Aprobado  
				FROM Clausulas
				INNER JOIN Categorias ON Categorias.idCategoria = Clausulas.idCategoria
				WHERE Clausulas.Eliminado = 0 AND Clausulas.idClausula=@idClausula
				
			END 
	END
END
GO
