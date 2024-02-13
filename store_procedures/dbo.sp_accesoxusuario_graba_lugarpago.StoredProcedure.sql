USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_graba_lugarpago]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 30/06/2017
-- Descripcion: crea permiso para empresa y lugar de pago según tipo de perfil
-- Ejemplo:exec sp_accesoxusuario_graba_lugarpago 1,'33333333-3','lugarpago1'
CREATE PROCEDURE [dbo].[sp_accesoxusuario_graba_lugarpago]
@pusuarioid			VARCHAR(10),	-- id del usuario
@pempresaid			VARCHAR (50),	-- id de la empresa
@plugarpagoid		VARCHAR (14)	-- id lugar pago
	
AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	
	IF NOT EXISTS
		(
			SELECT usuarioid 
			FROM accesoxusuarioempresas
			WHERE usuarioid= @pusuarioid 
			AND empresaid= @pempresaid 
		) 
		BEGIN 
			INSERT INTO accesoxusuarioempresas
			(usuarioid,empresaid)
			VALUES
			(@pusuarioid,@pempresaid);
		
		END
		

	IF NOT EXISTS
		(
			SELECT usuarioid 
			FROM accesoxusuariolugarespago
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid 
			AND lugarpagoid		= @plugarpagoid
		) 
		BEGIN 
			INSERT INTO accesoxusuariolugarespago
			(usuarioid,empresaid,lugarpagoid)
			VALUES
			(@pusuarioid,@pempresaid,@plugarpagoid);
			
		END

	SELECT @error= 0
	SELECT @mensaje = ''			
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
