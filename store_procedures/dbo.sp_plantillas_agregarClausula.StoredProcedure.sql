USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_agregarClausula]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_plantillas_agregarClausula]
	@idPlantilla INT,
	@idClausula INT,
	@Encabezado INT,
	@Titulo INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here

	IF NOT EXISTS ( SELECT idPlantilla FROM PlantillasClausulas  WHERE idPlantilla = @idPlantilla AND idClausula = @idClausula)
		BEGIN
			--Contamos cuantas clausulas tiene una plantilla
			SELECT @total = COUNT(idClausula) FROM PlantillasClausulas WHERE idPlantilla = @idPlantilla			
			
			--Si es cero, no tiene clausulas asignadas 
			IF (@total = 0)
				BEGIN
					INSERT INTO plantillasClausulas (idPlantilla,idClausula, Orden, Encabezado, Titulo )
					VALUES(@idPlantilla, @idClausula, 1, @Encabezado, @Titulo) 
					
					UPDATE Plantillas SET Aprobado = 0 WHERE idPlantilla = @idPlantilla
					
					SELECT @lmensaje = ''
					SELECT @error = 0
				END
			--Si es mayor a cero, vamos a registrar la clausula en el ultimo valor 
			ELSE
				BEGIN
					--Aumentamos en uno el valor del total 
					SET @total += 1
					INSERT INTO plantillasClausulas (idPlantilla,idClausula, Orden, Encabezado, Titulo )
					VALUES(@idPlantilla, @idClausula, @total, @Encabezado, @Titulo)
					
					UPDATE Plantillas SET Aprobado = 0 WHERE idPlantilla = @idPlantilla
					
					SELECT @lmensaje = ''
					SELECT @error = 0
				END
		END 
	ELSE
		BEGIN
			SELECT @lmensaje = 'ESTA PLANTILLA YA CONTIENE ESTA CLAUSULA'
			SELECT @error = 1
		END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
