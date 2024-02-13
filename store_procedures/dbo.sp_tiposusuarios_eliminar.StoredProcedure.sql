USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_eliminar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- Autor: Cristian Soto
-- Creado el: 2/09/2016
-- Descripcion:	elimina tipo de usuario y sus opciones
-- Ejemplo:exec sp_tiposusuarios_elimina 1

-- Autor: Cristian Soto
-- Modificado el: 18/01/2017
-- Descripcion:	envia mensaje de error si existen usuarios asociados al perfil 
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_eliminar]
@ptipousuarioid		INT				-- id del tipo de usuario
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(200)
		

	IF EXISTS(SELECT tipousuarioid FROM tiposusuarios WHERE tipousuarioid= @ptipousuarioid) 	
		BEGIN
			IF NOT EXISTS(SELECT usuarioid FROM usuarios WHERE tipousuarioid= @ptipousuarioid) 	
				BEGIN
					IF EXISTS(SELECT tipousuarioid FROM opcionesxtipousuario WHERE tipousuarioid= @ptipousuarioid) 
						BEGIN
							DELETE FROM opcionesxtipousuario 
							WHERE tipousuarioid = @ptipousuarioid	
						END			
					/*
					IF EXISTS(SELECT tipousuarioid FROM accesodocxperfilccosto WHERE tipousuarioid= @ptipousuarioid) 
						BEGIN
							DELETE FROM accesodocxperfilccosto 
							WHERE tipousuarioid = @ptipousuarioid	
						END		
						*/
					/*IF EXISTS(SELECT tipousuarioid FROM accesodocxperfilempresas WHERE tipousuarioid= @ptipousuarioid) 
						BEGIN
							DELETE FROM accesodocxperfilempresas 
							WHERE tipousuarioid = @ptipousuarioid	
						END	
						*/
					IF EXISTS(SELECT tipousuarioid FROM tiposdocumentosxperfil WHERE tipousuarioid= @ptipousuarioid) 
						BEGIN
							DELETE FROM tiposdocumentosxperfil 
							WHERE tipousuarioid = @ptipousuarioid	
						END		
									
					DELETE FROM tiposusuarios 
					WHERE tipousuarioid= @ptipousuarioid
				
					SELECT @mensaje = ''
					SELECT @error = 0	
				END
			ELSE
				BEGIN
					SELECT @mensaje = 'El tipo de perfil no puede ser eliminado, tiene usuarios asociados, para eliminar el perfil debe borrar los usuarios asociados.'
					SELECT @error = 1			
				END
		END
	ELSE
		BEGIN
			SELECT @mensaje = 'Información a eliminar ya no existe'
			SELECT @error = 1
		END		
	

			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
