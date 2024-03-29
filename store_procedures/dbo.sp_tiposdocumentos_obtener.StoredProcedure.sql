USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposdocumentos_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05-04-2019
-- Descripcion:  Obtiene los datos de un Tipo de documento
-- Ejemplo:exec sp_tiposdocumentos_obtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposdocumentos_obtener]
	@idTipoDoc INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
   
    BEGIN
		IF EXISTS (SELECT idTipoDoc FROM TipoDocumentos WHERE idTipoDoc = @idTipoDoc AND Eliminado = 0)
			BEGIN
				SELECT idTipoDoc, NombreTipoDoc FROM TipoDocumentos
				WHERE idTipoDoc = @idTipoDoc
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA TIPO DE DOCUMENTO NO EXISTE'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
