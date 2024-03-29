USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_elimina_centrocosto]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 17/06/2019
-- Descripcion: elimina permisos para centrocosto según tipo de perfil y empresa
-- Ejemplo:exec sp_accesoxusuario_elimina_centrocosto '11111111-1','33333333-3','centrocosto1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_elimina_centrocosto]
@pusuarioid				VARCHAR(10),	-- id del usuario
@pempresaid				NVARCHAR (50),		-- id de la empresa
@plugarpagoid			NVARCHAR (14),		-- id lugar de pago
--@pdepartamentoid		VARCHAR (14),		-- id departamento
@pcentrocostoid			NVARCHAR (14)		-- id de la centrocosto
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
		

	IF EXISTS
		(
			SELECT usuarioid FROM accesoxusuarioccosto 
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid 
			AND lugarpagoid		= @plugarpagoid
			--AND departamentoid	= @pdepartamentoid
			AND centrocostoid	= @pcentrocostoid
		) 
		BEGIN 
			DELETE FROM accesoxusuarioccosto 
			WHERE  usuarioid= @pusuarioid 
			AND empresaid		= @pempresaid 
			AND lugarpagoid		= @plugarpagoid
			--AND departamentoid	= @pdepartamentoid
			AND centrocostoid	= @pcentrocostoid
		END
	
	SELECT @error= 0
	SELECT @mensaje = ''	
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
