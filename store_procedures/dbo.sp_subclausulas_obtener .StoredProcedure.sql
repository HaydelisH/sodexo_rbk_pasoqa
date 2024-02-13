USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_subclausulas_obtener ]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 10-04-2019
-- Descripcion: Obtener una SubClausula
-- Ejemplo:exec sp_subclausulas_obtener '161-1',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_subclausulas_obtener ]
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
		
			IF EXISTS ( SELECT idSubClausula FROM SubClausulas WHERE idSubClausula = @pidsubclausula AND idTipoSubClausula = @pidtiposubclausula AND Eliminado = 1 )
				BEGIN 
					SET @lmensaje = 'Esta subclausula no existe'
					SET @error = 1
				END 
			ELSE
				BEGIN 
					SELECT 
						SC.idSubClausula,
						SC.idTipoSubClausula,
						SC.Titulo,
						SC.Descripcion,
						SC.SubClausula,
						TSC.Descripcion As TipoSubClausula
					FROM 
						SubClausulas SC
						INNER JOIN TipoSubClausulas TSC ON TSC.idTipoSubClausula = SC.idTipoSubClausula
					WHERE 
						SC.idSubClausula = @pidsubclausula AND SC.idTipoSubClausula = @pidtiposubclausula AND SC.Eliminado = 0
						
					SET @lmensaje = ''
					SET @error = 0
				END
		END
    
END
GO
