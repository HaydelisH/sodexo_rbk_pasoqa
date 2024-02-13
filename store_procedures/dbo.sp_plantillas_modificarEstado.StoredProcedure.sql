USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_modificarEstado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 01/08/2018
-- Descripcion: Actualiza el estado de una Plantilla 
-- Ejemplo:exec sp_plantillas_modificarEstado 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_modificarEstado]
	@idPlantilla INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    UPDATE 
		Plantillas
	SET
		Aprobado = 0
	WHERE
		idPlantilla = @idPlantilla
END
GO
