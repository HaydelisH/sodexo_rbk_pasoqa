USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_procesos_obtenerProcesoFlujo]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 12-07-2019
-- Descripcion:  Obtener el proceso que este activo 
-- Ejemplo: exec sp_procesos_obtenerProcesoFlujo
--sp_procesos_obtenerProcesoFlujo 1,'Contratacion '
-- =============================================
CREATE PROCEDURE [dbo].[sp_procesos_obtenerProcesoFlujo]
	@pidTipoMovimiento INT,
	@pNombreProceso VARCHAR(100)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT
	DECLARE @idProceso	INT
	DECLARE @maxProceso INT
	DECLARE @fechaCreacion DATETIME
	DECLARE @nombre VARCHAR(100);
			
    -- Insert statements for procedure here
	SET LANGUAGE Spanish;

	SET  @nombre = @pNombreProceso + ' ' + DATENAME(MONTH,GETDATE()) + ' ' + CONVERT(VARCHAR(20),YEAR(GETDATE()))

	--Si Movimiento viene en blanco
	IF ( @pidTipoMovimiento = 0 ) 
		BEGIN 
			SET @lmensaje = 'El Tipo de Movimiento no debe ser vacio'
			SET @error = 1

			SELECT @lmensaje as mensaje, @error as error, @idProceso as idProceso

			RETURN;
		END

	--Si no existe un proceso con un tipo de movimiento
	IF NOT EXISTS( SELECT idProceso FROM Procesos WHERE idTipoMovimiento = @pidTipoMovimiento ) 
		BEGIN 
			INSERT INTO Procesos(Descripcion, idTipoMovimiento, Activo, fechaCreacion, Eliminado )
			VALUES( @nombre, @pidTipoMovimiento, 1,GETDATE(), 0)
			SET @idProceso = @@IDENTITY
		END
	ELSE
		BEGIN 
			--Si no existe un proceso activo
			IF NOT EXISTS( SELECT idProceso FROM Procesos WHERE idTipoMovimiento = @pidTipoMovimiento AND Activo = 1 )
				BEGIN 
					--Buscamos en que se creo recientemente 
					SELECT @fechaCreacion = MAX(fechaCreacion) FROM Procesos WHERE idTipoMovimiento = @pidTipoMovimiento
					SELECT @idProceso = idProceso FROM Procesos WHERE fechaCreacion =  @fechaCreacion AND idTipoMovimiento = @pidTipoMovimiento
								
					--Si el mes y el año coiciden con el del día
					IF ( MONTH(GETDATE()) = MONTH(@fechaCreacion) AND YEAR(GETDATE()) = YEAR(@fechaCreacion) )
						BEGIN
							--Ativamos ese proceso 
							UPDATE Procesos SET Descripcion = @nombre, Activo = 1 WHERE idProceso = @idProceso
							--UPDATE Procesos SET Activo = 0 WHERE idProceso <> @idProceso
						END
					ELSE
						BEGIN
							INSERT INTO Procesos(Descripcion, idTipoMovimiento, Activo, fechaCreacion, Eliminado )
							VALUES(@nombre, @pidTipoMovimiento, 1,GETDATE(), 0)  
							SET @idProceso = @@IDENTITY
						END
				END	
			ELSE
				BEGIN 
					--Buscamos en que se creo recientemente 
					--SELECT @fechaCreacion = MAX(fechaCreacion), @idProceso = idProceso FROM Procesos 
					--WHERE Activo = 1
					--GROUP BY idProceso 

					SELECT @fechaCreacion = MAX(fechaCreacion) FROM Procesos WHERE Activo = 1
					SELECT @idProceso = idProceso FROM Procesos WHERE fechaCreacion = @fechaCreacion AND Activo = 1
								
					--Si el mes y el año coiciden con el del día
					IF ( MONTH(GETDATE()) = MONTH(@fechaCreacion) AND YEAR(GETDATE()) = YEAR(@fechaCreacion) )
						BEGIN

							--UPDATE Procesos SET Descripcion = @nombre WHERE idProceso = @idProceso

							SELECT @idProceso as idProceso
							RETURN;
						END
					ELSE
						BEGIN 
							INSERT INTO Procesos(Descripcion, idTipoMovimiento, Activo, fechaCreacion,Eliminado )
							VALUES( @nombre, @pidTipoMovimiento, 1,GETDATE(), 0)  
							
							SET @maxProceso = @@IDENTITY
							
							UPDATE Procesos SET Activo = 0 WHERE idProceso < @maxProceso
							--UPDATE Procesos SET Descripcion = @nombre, Activo = 1 WHERE idProceso = @maxProceso
							
							SET @idProceso = @maxProceso
						END
				END			
		END 	
	
	SELECT @lmensaje as mensaje, @error as error, @idProceso as idProceso

	RETURN;
END
GO
