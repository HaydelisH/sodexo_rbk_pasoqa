USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_feriados_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Desactivar registros de equipamiento
-- Ejemplo:exec sp_feriados_eliminar 'eliminar','13' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_feriados_eliminar]
	@pAccion CHAR(60),
	@idFeriado INT
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
		IF EXISTS (SELECT idFeriado FROM Feriados WHERE idFeriado = @idFeriado)
			BEGIN
			 DELETE FROM Feriados WHERE idFeriado =@idFeriado
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA FICHA FUE ELIMINADA'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
