USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipogestor_agregar]    Script Date: 1/22/2024 7:21:15 PM ******/
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
CREATE PROCEDURE [dbo].[sp_tipogestor_agregar]
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
   IF NOT EXISTS (SELECT idTipoGestor FROM TipoGestor WHERE Nombre = @pNombre ) 
		BEGIN 
			INSERT INTO TipoGestor ( Nombre ) VALUES ( @pNombre )
			SELECT @@IDENTITY AS idTipoGestor
			
			SET @lmensaje = '' 
			SET @error = 0
		END 
   
END
GO
