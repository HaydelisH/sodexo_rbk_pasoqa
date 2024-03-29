USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_comentarios_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Descripcion: Listar todos los comentarios por Documento 
-- Ejemplo:exec sp_comentarios_listado 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_comentarios_obtener]
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
		Com.idComentario,
		Com.idEstado,
		Com.RutUsuario,
		CONVERT(VARCHAR(10),Com.fecha,105) AS fecha,
		CONVERT(CHAR(8), Com.fecha, 108) As hora,
		Com.idContrato,
		Com.Comentario,
		p.nombre + ' ' + p.appaterno + ' ' + p.apmaterno As NombreUsuario,
		EC.Descripcion
	FROM
		Comentarios Com
	INNER JOIN personas p ON p.personaid = Com.RutUsuario
	INNER JOIN EstadoContratos EC on ec.idEstado = Com.idEstado
	WHERE
		idContrato = @idContrato
END
GO
