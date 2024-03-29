USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosxperfil_graba]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 18/10/2016
-- Descripcion: crea documentos por perfil 
-- Ejemplo:exec sp_documentosxperfil_graba 'agregar',1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosxperfil_graba]
@ptipousuarioid		INT,			-- id del tipo de usuario
@ptipodocumentoid	INT				-- id del tipo de documento
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
		

	IF NOT EXISTS(SELECT tipousuarioid FROM tiposdocumentosxperfil WHERE tipousuarioid= @ptipousuarioid AND idtipodoc= @ptipodocumentoid) 
		BEGIN 
			INSERT INTO tiposdocumentosxperfil
			(tipousuarioid,idtipodoc)
			VALUES
			(@ptipousuarioid,@ptipodocumentoid);
			
			SELECT @error= 0
			SELECT @mensaje = ''				
		END
	ELSE
		BEGIN
			SELECT @mensaje = 'los datos ingresados ya fueron creados anteriormente'
			SELECT @error = 1			
		END			


	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
