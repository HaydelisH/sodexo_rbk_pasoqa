USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosxperfil_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 19/10/2016
-- Descripcion:	elimina tipo de documento según perfil
-- Ejemplo:exec sp_documentosxperfil_eliminar 1,2
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosxperfil_eliminar]
@ptipousuarioid		INT,			-- id del perfil
@ptipodocumentoid	INT				-- id del tipo de documento
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
		

	IF EXISTS(SELECT tipousuarioid FROM tiposdocumentosxperfil WHERE tipousuarioid= @ptipousuarioid AND idtipodoc = @ptipodocumentoid) 	
		BEGIN
			DELETE FROM tiposdocumentosxperfil
			WHERE tipousuarioid	= @ptipousuarioid
			AND idtipodoc	= @ptipodocumentoid
			
			SELECT @mensaje = ''
			SELECT @error = 0	
		END
	ELSE
		BEGIN
			SELECT @mensaje = 'Información a eliminar ya no existe'
			SELECT @error = 1
		END		
	

			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
