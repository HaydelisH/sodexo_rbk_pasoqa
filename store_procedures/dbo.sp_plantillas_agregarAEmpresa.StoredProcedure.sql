USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_agregarAEmpresa]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_plantillas_agregarAEmpresa]
	@pRutEmpresa VARCHAR(10),
	@pidPlantilla INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here

	IF NOT EXISTS ( SELECT idPlantilla FROM PlantillasEmpresa WHERE RutEmpresa = @pRutEmpresa AND idPlantilla = @pidPlantilla )
		BEGIN 
			INSERT INTO PlantillasEmpresa (idPlantilla, RutEmpresa)
			VALUES(@pidPlantilla, @pRutEmpresa )
			
			SELECT @lmensaje = ''
			SELECT @error = 0
		END
	
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
