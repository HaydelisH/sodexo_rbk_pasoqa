USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_deducibles_modificar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/08/2018
-- Descripcion: Modifica los registros 
-- Ejemplo:exec sp_deducibles_modificar 'modificar','1245','ejemplo' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_deducibles_modificar]
	@pAccion CHAR(60),
	@idDeducibles INT,
	@Descripcion VARCHAR (100)
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
      IF  NOT EXISTS (SELECT idDeducibles FROM Deducibles WHERE idDeducibles = @idDeducibles)
        BEGIN
			SELECT @lmensaje = 'ESTE DEDUCIBLE NO EXISTE'
			SELECT @error = 1
			SELECT @error AS error, @lmensaje AS mensaje 
			RETURN
        END
        
	  IF EXISTS (SELECT idDeducibles FROM Deducibles WHERE idDeducibles = @idDeducibles AND Eliminado = 0)
	    BEGIN
			UPDATE Deducibles SET Descripcion = @Descripcion
			WHERE idDeducibles = @idDeducibles
		    SELECT @lmensaje = ''
			SELECT @error = 0
			SELECT @error AS error, @lmensaje AS mensaje 
			RETURN
	    END
	END	
	
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
