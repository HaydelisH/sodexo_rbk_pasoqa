USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_comentarios_agregar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29/08/2018
-- Descripcion: Agrega un Comentario a un Documento
-- Ejemplo:exec sp_comentarios_agregar 'agregar',1,'12123123-1',2,'Texto','2018-08-29'
-- =============================================
CREATE PROCEDURE [dbo].[sp_comentarios_agregar]
	@pAccion CHAR(60),
	@idContrato INT,
	@RutUusario VARCHAR (10), 
	@idEstado INT,
	@Comentario VARCHAR (MAX)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN	

		INSERT INTO Comentarios(idContrato, RutUsuario, idEstado, Comentario, fecha)
		VALUES
		(@idContrato, @RutUusario, @idEstado, @Comentario, GETDATE())
    END 
END
GO
