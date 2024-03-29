USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_elimina_empresa]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 17/06/2019
-- Descripcion: quita permiso para empresa según id del usuario
-- Ejemplo:exec sp_accesoxusuario_graba_empresa 1,'33333333-3'
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_elimina_empresa]
@pusuarioid			VARCHAR(10),	-- id del usuario
@pempresaid			VARCHAR (50)	-- id de la empresa
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
		

	IF EXISTS(SELECT usuarioid FROM accesoxusuarioempresas WHERE usuarioid= @pusuarioid AND empresaid= @pempresaid) 
		BEGIN 
			
			IF EXISTS(SELECT usuarioid FROM accesoxusuariolugarespago WHERE usuarioid= @pusuarioid AND empresaid= @pempresaid) 
				BEGIN
					DELETE FROM accesoxusuariolugarespago
					WHERE usuarioid	= @pusuarioid 
					AND empresaid		= @pempresaid	
				END		
				
			/*IF EXISTS(SELECT usuarioid FROM accesoxusuariodepartamentos WHERE usuarioid= @pusuarioid AND empresaid= @pempresaid) 
				BEGIN
					DELETE FROM accesoxusuariodepartamentos
					WHERE usuarioid	= @pusuarioid 
					AND empresaid		= @pempresaid	
				END	*/	
				
			IF EXISTS(SELECT usuarioid FROM accesoxusuarioccosto WHERE usuarioid= @pusuarioid AND empresaid= @pempresaid) 
				BEGIN
					DELETE FROM accesoxusuarioccosto
					WHERE usuarioid	= @pusuarioid 
					AND empresaid		= @pempresaid	
				END		
				
			DELETE FROM accesoxusuarioempresas
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid
				
			SELECT @error= 0
			SELECT @mensaje = ''				
		END
	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
