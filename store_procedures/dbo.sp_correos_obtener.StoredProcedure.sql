USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_correos_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Obtener correo
-- Ejemplo:exec sp_correos_obtener 16
-- =============================================
CREATE PROCEDURE [dbo].[sp_correos_obtener]
	@idCorreo INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
			
    -- Insert statements for procedure here
   
    BEGIN
		IF EXISTS (SELECT CodCorreo FROM Correo WHERE CodCorreo = @idCorreo )
			BEGIN
				SELECT CodCorreo, Descripcion, CC, CCo,Asunto, Cuerpo FROM Correo 
				WHERE CodCorreo = @idCorreo
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
