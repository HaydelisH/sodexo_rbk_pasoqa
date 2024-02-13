USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipogestor_modificar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 15-04-2019
-- Descripcion:  Agregar un Tipo de documento del gestor 
-- Ejemplo:exec sp_tipogestor_agregar 'xxxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_tipogestor_modificar]
	@pidTipoGestor INT,
	@pNombre VARCHAR (60) 
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
   IF EXISTS (SELECT idTipoGestor FROM TipoGestor WHERE idTipoGestor = @pidTipoGestor ) 
		BEGIN 
			UPDATE TipoGestor SET 
				Nombre = @pNombre
			WHERE 
				idTipoGestor = @pidTipoGestor
			
			SET @lmensaje = '' 
			SET @error = 0
		END 
	ELSE
		BEGIN 
			SET @lmensaje = 'El Tipo de gestor seleccionado no existe' 
			SET @error = 1
		END
   
END
GO
