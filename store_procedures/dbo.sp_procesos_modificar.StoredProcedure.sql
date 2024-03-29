USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_procesos_modificar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04-04-2019
-- Descripcion:  Actualiza los datos de un Proceso
-- Ejemplo:exec sp_procesos_modificar 'modificar',1,'ejemplo'
-- =============================================
CREATE PROCEDURE [dbo].[sp_procesos_modificar]
	@pAccion CHAR(60),
	@idProceso INT,
	@Descripcion VARCHAR (50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='modificar') 
    BEGIN
		IF NOT EXISTS ( SELECT idProceso FROM Procesos WHERE idProceso = @idProceso )
			BEGIN
				SELECT @lmensaje = 'ESTE PROCESO NO EXISTE'
				SELECT @error = 1
			END 
		ELSE
			BEGIN
				IF EXISTS ( SELECT idProceso FROM Procesos WHERE idProceso = @idProceso AND Eliminado = 1 )
					BEGIN
						UPDATE Procesos SET 
							Descripcion = @Descripcion,
							Eliminado = 0
						WHERE idProceso = @idProceso
					END
				ELSE
					BEGIN
						UPDATE Procesos SET 
							Descripcion = @Descripcion
						 WHERE idProceso = @idProceso
					END	
				
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
