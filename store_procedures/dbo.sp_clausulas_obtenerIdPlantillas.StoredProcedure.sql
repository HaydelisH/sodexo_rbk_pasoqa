USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_obtenerIdPlantillas]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 007-05-2019
-- Descripcion: Obtiene las Plantillas a las que pertenece esta Clausula
-- Ejemplo:exec sp_clausulas_obtenerIdPlantillas 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_obtenerIdPlantillas]
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
		SELECT 
			idPlantilla
		FROM 
			PlantillasClausulas
		WHERE 
			idClausula = @idClausula
	END
END
GO
