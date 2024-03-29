USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_obtenerClausulasPlantilla]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 07-08-2018
-- Descripcion: Obtiene los datos de una Clausula que ya pertenecea una Plantilla
-- Ejemplo:exec sp_clausulas_obtenerClausulasPlantilla
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_obtenerClausulasPlantilla]
	@idClausula INT,
	@idPlantilla INT
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
		IF EXISTS (SELECT idClausula FROM PlantillasClausulas WHERE (idClausula=@idClausula AND idPlantilla=@idPlantilla))
			BEGIN
				SELECT idClausula, idPlantilla, Orden, Encabezado, Titulo FROM PlantillasClausulas WHERE idClausula=@idClausula AND idPlantilla=@idPlantilla
			END 
	END
END
GO
