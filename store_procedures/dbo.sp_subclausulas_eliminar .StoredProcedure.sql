USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_subclausulas_eliminar ]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 10-04-2019
-- Descripcion: Eliminar una SubClausula
-- Ejemplo:exec sp_subclausulas_eliminar '161-1',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_subclausulas_eliminar ]
	@pidsubclausula VARCHAR(50),
	@pidtiposubclausula INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
			
	IF NOT EXISTS ( SELECT idSubClausula FROM SubClausulas WHERE idSubClausula = @pidsubclausula AND idTipoSubClausula = @pidtiposubclausula )
		BEGIN 
			SET @lmensaje = 'Esta subclausula no existe'
			SET @error = 1
		END 
	ELSE
		BEGIN 	
			UPDATE SubClausulas SET 
				Eliminado = 1
			WHERE 
				idSubClausula = @pidsubclausula AND idTipoSubClausula = @pidtiposubclausula
				
			SET @lmensaje = ''
			SET @error = 0
		END
    
END
GO
