USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposdocumentos_eliminar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05-04-2019
-- Descripcion:  Elimina de forma logica un Tipo de documento
-- Ejemplo:exec ssp_tiposdocumentos_eliminar 'xxxx',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposdocumentos_eliminar]
	@pAccion CHAR(60),
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
	IF (@pAccion='eliminar') 
    BEGIN
		IF EXISTS (SELECT idTipoDoc FROM TipoDocumentos WHERE idTipoDoc = @idTipoDoc AND Eliminado=0)
			BEGIN
				UPDATE TipoDocumentos SET Eliminado = 1
				WHERE idTipoDoc = @idTipoDoc 
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTE TIPO DE DOCUMENTO NO EXISTE'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
