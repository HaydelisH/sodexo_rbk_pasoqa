USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtenerFlujo]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 17/12/2018
-- Descripcion: Obtinee los datos de una Plantilla
-- Ejemplo:exec sp_plantillas_obtenerFlujo 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_obtenerFlujo]
	@idPlantilla INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
   
    BEGIN
		IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE (idPlantilla=@idPlantilla AND Eliminado=0))
			BEGIN
				SELECT 
					EW.idEstado,
					EW.Descripcion As Nombre
				FROM Plantillas P
				INNER JOIN WorkflowEstadoProcesos WP ON P.idWF = WP.idWorkflow
				INNER JOIN ContratosEstados      EW ON EW.idEstado = WP.idEstadoWF
				WHERE P.idPlantilla = @idPlantilla
				SELECT @error = 0
				SELECT @mensaje = ''	
				  
			END 
		ELSE
			BEGIN
				SELECT @error = 1
				SELECT @mensaje = 'ESTA PLANTILLA NO EXISTE'		
			END
	END
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
