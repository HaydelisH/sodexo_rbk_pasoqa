USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_cambiarOrdenClausulas]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Actualizar el orden de la Clausula de una Plantilla
-- Ejemplo:exec sp_plantillas_cambiarOrdenClausulas 1,1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_cambiarOrdenClausulas]
	@idPlantilla INT,
	@idClausula INT,
	@Orden INT
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
		IF EXISTS (SELECT idPlantilla FROM PlantillasClausulas WHERE (idPlantilla=@idPlantilla AND idClausula=@idClausula))
			BEGIN
 				UPDATE PlantillasClausulas SET 
 				Orden = @Orden
 				WHERE 
 				idPlantilla=@idPlantilla AND idClausula=@idClausula 
 				SELECT @lmensaje = ''
				SELECT @error = 0 
			END 
		ELSE
			BEGIN 
				SELECT @lmensaje = 'ESTA CLAUSULA NO PERTENECE A ESTA PLANTILLA'
				SELECT @error = 0
			END	
	 END
END
GO
