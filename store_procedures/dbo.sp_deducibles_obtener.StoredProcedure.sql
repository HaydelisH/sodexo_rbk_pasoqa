USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_deducibles_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/08/2018
-- Descripcion: Obtiene los datos solicitados
-- =============================================
CREATE PROCEDURE [dbo].[sp_deducibles_obtener]
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
   
    BEGIN
		IF EXISTS (SELECT idDeducibles FROM Deducibles WHERE idDeducibles = @idDeducibles)
			BEGIN
				SELECT idDeducibles, Descripcion FROM Deducibles 
				WHERE idDeducibles = @idDeducibles
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTE DEDUCIBLE NO EXISTE'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
