USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_deducibles_agregar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/08/2018
-- Descripcion: Agrega deducible
-- Ejemplo:exec sp_deducibles_agregar 'agregar','ejemplo 7' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_deducibles_agregar]
	@pAccion CHAR(60),
	@Descripcion VARCHAR (100) 
	 
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	DECLARE @eliminado  BIT;
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		IF NOT EXISTS (SELECT IdDeducibles FROM Deducibles WHERE Descripcion = @Descripcion )
			BEGIN 	
				INSERT INTO Deducibles(Descripcion, Eliminado) VALUES 
				(@Descripcion,0) 
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @eliminado = Eliminado FROM Deducibles WHERE Descripcion = @Descripcion
				IF @eliminado = 0
					BEGIN
						SELECT @lmensaje = 'ESTE DEDUCIBLE YA EXISTE'
						SELECT @error = 1
				   END 
				ELSE 
					BEGIN 
						UPDATE Deducibles SET Eliminado = 0 WHERE Descripcion = @Descripcion
					    SELECT @lmensaje = ''
				        SELECT @error = 0
					END   
			END 
	END
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
