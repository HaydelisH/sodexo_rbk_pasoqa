USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_correos_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Elimina correo
-- Ejemplo:exec sp_correos_eliminar 'eliminar',116
-- =============================================
CREATE PROCEDURE [dbo].[sp_correos_eliminar]
	@pAccion CHAR(60),
	@idCorreo INT
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
		IF EXISTS (SELECT CodCorreo FROM Correo WHERE CodCorreo = @idCorreo )
			BEGIN
				DELETE FROM Correo WHERE CodCorreo = @idCorreo
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTE CORREO NO EXISTE'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
