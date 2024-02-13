USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_cargos_eliminar ]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernanddez
-- Creado el: 29-03-2019
-- Descripcion: Eliminar logicamente un cargo 
-- Ejemplo: sp_cargos_eliminar 
-- =============================================
CREATE PROCEDURE [dbo].[sp_cargos_eliminar ]

	@idCargo INT 
	 
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
			
    -- Insert statements for procedure here
   
	IF NOT EXISTS (SELECT idCargo FROM Cargos WHERE idCargo = @idCargo )
		BEGIN 	
			SELECT @lmensaje = 'El cargo seleccionado no existe'
			SELECT @error = 1
		END 
	ELSE
		BEGIN
			IF EXISTS ( SELECT idCargo FROM Cargos WHERE idCargo = @idCargo AND Eliminado = 1 )
				BEGIN 
					SELECT @lmensaje = 'El cargo seleccionado no existe'
					SELECT @error = 1
				END
			ELSE
				BEGIN 
					UPDATE Cargos SET 
						Eliminado = 1
					WHERE 
						idCargo = @idCargo
						
					SELECT @lmensaje = ''
					SELECT @error = 0
				END
		END 

    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
