USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_subclausulas_agregar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
CREATE PROCEDURE [dbo].[sp_subclausulas_agregar]
	@pidsubclausula VARCHAR(50),
	@pidtiposubclausula INT,
	@ptitulo VARCHAR(200),
	@pdescripcion VARCHAR(200),
	@psubclausula VARCHAR(MAX)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
			
	IF NOT EXISTS ( SELECT idSubClausula FROM SubClausulas WHERE idSubClausula = @pidsubclausula AND idTipoSubClausula = @pidtiposubclausula )
		BEGIN 
			INSERT INTO SubClausulas (idSubClausula,idTipoSubClausula, Titulo, Descripcion,SubClausula, Eliminado )
				VALUES( @pidsubclausula, @pidtiposubclausula, @ptitulo, @pdescripcion,@psubclausula, 0) 
		END 
	ELSE
		BEGIN 
			UPDATE SubClausulas SET 
				Titulo = @ptitulo,
				Descripcion = @pdescripcion,
				SubClausula = @psubclausula,
				Eliminado = 0
			WHERE 
				idSubClausula = @pidsubclausula AND idTipoSubClausula = @pidtiposubclausula
		END
    
END
GO
