USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_deducibles_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/08/2018
-- Descripcion: Desactivar deducibles
-- Ejemplo:exec sp_deducibles_eliminar 'eliminar','124' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_deducibles_eliminar]
	@pAccion CHAR(60),
	@idDeducibles INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='eliminar') 
    BEGIN
		IF EXISTS (SELECT idDeducibles FROM Deducibles WHERE idDeducibles = @idDeducibles AND Eliminado=0)
			BEGIN
				UPDATE Deducibles SET Eliminado = 1
				WHERE idDeducibles = @idDeducibles 
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTE DEDUCIBLE FUE ELIMINADO'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
