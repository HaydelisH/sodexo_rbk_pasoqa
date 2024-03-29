USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_clonar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Clonar una Plantilla
-- Ejemplo:exec sp_plantillas_clonar
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_clonar]
	@idPlantilla INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	DECLARE @idPlan INT;
	
	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(200)	
	
	BEGIN TRANSACTION 
	BEGIN TRY		
	
		--Tomar los datos de la Plantilla y generar la Plantilla nueva  
		INSERT INTO Plantillas(Descripcion_Pl, Titulo_Pl, idWF, Aprobado, idTipoDoc, RutModificador, RutAprobador,  idCategoria,idTipoGestor, Eliminado)
		SELECT Descripcion_Pl + '(Copia)' , Titulo_Pl + '(Copia)' , idWF, 0, idTipoDoc, RutModificador, RutAprobador,  idCategoria, idTipoGestor, 0 FROM Plantillas WHERE idPlantilla = @idPlantilla
			
		SET @idPlan = @@IDENTITY
		
		--Copiar todas las clausulas de la Plantilla con sus atributos 
		INSERT INTO PlantillasClausulas (idPlantilla, idClausula, Orden, Encabezado, Titulo)
		SELECT @idPlan, idClausula, Orden, Encabezado, Titulo FROM PlantillasClausulas WHERE idPlantilla = @idPlantilla 
	COMMIT TRANSACTION
	END TRY

	BEGIN CATCH
	ROLLBACK TRANSACTION 
	
		SET @error		= ERROR_NUMBER()
		SET @mensaje	= ERROR_MESSAGE()
		
		SET @idPlan = 0
			
	END CATCH
	
	SELECT @error as error, @mensaje as mensaje,@idPlan As idPlantilla
		
END
GO
