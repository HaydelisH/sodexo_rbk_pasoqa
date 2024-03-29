USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_elimina_lugarpago]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 28/10/2016
-- Descripcion: elimina permisos para centrocosto según tipo de perfil y empresa
-- Ejemplo:exec sp_accesoxusuario_elimina_lugarpago 1,'33333333-3','lugarpago1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_elimina_lugarpago]
@pusuarioid				VARCHAR(10),	-- id del usuario
@pempresaid				NVARCHAR (50),		-- id de la empresa
@plugarpagoid			NVARCHAR (14)		-- id lugar de pago
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
		

	IF EXISTS
		(
			SELECT usuarioid FROM accesoxusuariolugarespago 
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid 
			AND lugarpagoid		= @plugarpagoid
		) 
		BEGIN 
			DELETE FROM accesoxusuariolugarespago 
			WHERE  usuarioid= @pusuarioid 
			AND empresaid= @pempresaid 
			AND lugarpagoid= @plugarpagoid
		END
		
	/*IF EXISTS
		(
			SELECT usuarioid FROM accesoxusuariodepartamentos 
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid
			AND lugarpagoid		= @plugarpagoid
		) 
		BEGIN
			DELETE FROM accesoxusuariodepartamentos
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid	
			AND lugarpagoid		= @plugarpagoid
		END		*/
			
	IF EXISTS
		(
			SELECT usuarioid FROM accesoxusuarioccosto 
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid
			AND lugarpagoid		= @plugarpagoid
		) 
		BEGIN
			DELETE FROM accesoxusuarioccosto
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid	
			AND lugarpagoid		= @plugarpagoid
		END		
	
	SELECT @error= 0
	SELECT @mensaje = ''	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
