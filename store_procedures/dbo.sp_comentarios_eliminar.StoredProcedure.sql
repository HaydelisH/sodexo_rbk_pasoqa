USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_comentarios_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29/08/2018
-- Descripcion: Eliminar un Comentario de un Documento
-- Ejemplo:exec sp_comentarios_eliminar 'eliminar',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_comentarios_eliminar]
	@pAccion CHAR(60),
	@idComentario INT
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

		DELETE FROM Comentarios 
		WHERE
			idComentario = @idComentario 
	 END 
END
GO
