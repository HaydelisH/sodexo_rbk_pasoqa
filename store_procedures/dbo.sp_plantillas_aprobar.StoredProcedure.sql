USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_aprobar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Apribar una Plantilla
-- Ejemplo:exec sp_plantillas_aprobar
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_aprobar]
	@idPlantilla INT, 
	@RutAprobador VARCHAR(10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		BIT
	DECLARE @cant_cla	INT
	DECLARE @cant_apr	INT;
	
	BEGIN 		
		IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE idPlantilla = @idPlantilla AND Eliminado=0)
			BEGIN
			
			--Contar cuantas clausulas tiene la plantilla
			select @cant_cla = COUNT(*) from PlantillasClausulas where idPlantilla = @idPlantilla
			
			--Contar cuantas clausulas estan aprobadas 
			select 
				@cant_apr = COUNT(*)
			from Plantillas pl
				inner join PlantillasClausulas pc on pc.idPlantilla = pl.idPlantilla 
				inner join Clausulas c on c.idClausula = pc.idClausula 
			where pl.idPlantilla = @idPlantilla and c.Aprobado = 1
			
			IF( @cant_cla = @cant_apr ) 
				BEGIN 
					IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE idPlantilla = @idPlantilla AND Aprobado=0)
						BEGIN
							UPDATE Plantillas SET  Aprobado = 1, RutAprobador = @RutAprobador WHERE idPlantilla = @idPlantilla 
							SELECT @lmensaje = ''
							SELECT @error = 0
						END
					ELSE
						BEGIN
							SELECT @lmensaje = 'ESTA PLANTILLA YA ESTA APROBADA'
							SELECT @error = 1
						END
				END
			ELSE
				BEGIN
					SELECT @lmensaje = 'ESTA PLANTLLA TIENE CLAUSULAS SIN APROBAR'
					SELECT @error = 1
				END	 
			
			END	
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA CATEGORIA NO EXISTE'
				SELECT @error = 1
			END	 
	END
	SELECT @error AS error, @lmensaje AS mensaje 
END
GO
