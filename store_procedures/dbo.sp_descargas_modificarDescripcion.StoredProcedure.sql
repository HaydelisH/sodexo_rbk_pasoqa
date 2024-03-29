USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_descargas_modificarDescripcion]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/11/2018
-- Descripcion: Modificar solo la descripcion de un archivo
-- Ejemplo:exec sp_descargas_modidficar 1,'Descripcion'
-- =============================================
CREATE PROCEDURE [dbo].[sp_descargas_modificarDescripcion]
	@idDescarga INT,
	@Descripcion VARCHAR(50)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
	UPDATE Descargas SET 
		Descripcion = @Descripcion
	WHERE idDescarga = @idDescarga
END
GO
