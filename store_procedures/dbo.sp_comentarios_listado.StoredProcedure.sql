USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_comentarios_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29/08/2018
-- Descripcion: Listar todos los comentarios por Documento 
-- Ejemplo:exec sp_comentarios_listado 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_comentarios_listado]
	@idContrato INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	SELECT 
		idComentario,
		idEstado,
		RutUsuario,
		fecha,
		idContrato
	FROM
		Comentarios
	WHERE
		idContrato = @idContrato
END
GO
