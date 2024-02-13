USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_cargos_agregar ]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernanddez
-- Creado el: 29-03-2019
-- Descripcion: Agregar un cargo 
-- Ejemplo: sp_cargos_agregar 
-- =============================================
CREATE PROCEDURE [dbo].[sp_cargos_agregar ]

	@pAccion CHAR(60),
	@Descripcion VARCHAR(50) 
	 
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @idCargo_Resultado INT;
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		IF NOT EXISTS (SELECT idCargo FROM Cargos WHERE Descripcion = @Descripcion )
			BEGIN 	
				INSERT INTO Cargos(Descripcion, Eliminado) 
				VALUES 
					(@Descripcion,0) 
				
				SELECT @idCargo_Resultado = @@IDENTITY
				
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				IF EXISTS (SELECT idCargo FROM Cargos WHERE Descripcion = @Descripcion AND Eliminado = 1 )
					BEGIN 
						UPDATE Cargos SET 
							Eliminado = 0,
							Descripcion = @Descripcion
						WHERE 
							Descripcion = @Descripcion
							
						SELECT @lmensaje = ''
						SELECT @error = 0
						
						SELECT @idCargo_Resultado = idCargo FROM Cargos WHERE Descripcion = @Descripcion 
					END					
			END 
	END
    SELECT @error AS error, @lmensaje AS mensaje, @idCargo_Resultado As idCargo
END
GO
