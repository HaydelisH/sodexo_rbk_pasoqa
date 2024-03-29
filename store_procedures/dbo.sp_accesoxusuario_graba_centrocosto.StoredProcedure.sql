USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_graba_centrocosto]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 17/06/2019
-- Descripcion: crea permiso para empresa y centro de costo según id del usuario
-- Ejemplo:exec sp_accesoxusuario_graba_centrocosto '11111111-1','33333333-3','ccosto1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_graba_centrocosto]
@pusuarioid			VARCHAR(10),	-- id del usuario
@pempresaid			VARCHAR (50),	-- id de la empresa
@plugarpagoid		VARCHAR (14),	-- id lugar de pago
--@pdepartamentoid	VARCHAR (14),	-- id departamento
@pcentrocostoid		VARCHAR (14)	-- id del centro de costo
	
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
		
	/*IF NOT EXISTS
		(
			SELECT usuarioid 
			FROM accesoxusuariodepartamentos
			WHERE usuarioid		= @pusuarioid 
			AND empresaid			= @pempresaid 
			AND lugarpagoid			= @plugarpagoid
			AND departamentoid		= @pdepartamentoid
		) 
		BEGIN 
			INSERT INTO accesoxusuariodepartamentos
			(usuarioid,empresaid,lugarpagoid,departamentoid)
			VALUES
			(@pusuarioid,@pempresaid,@plugarpagoid,@pdepartamentoid);
		
		END*/
		
	IF NOT EXISTS
		(
			SELECT usuarioid 
			FROM accesoxusuarioccosto
			WHERE usuarioid	= @pusuarioid 
			AND empresaid		= @pempresaid 
			AND lugarpagoid		= @plugarpagoid
			--AND departamentoid	= @pdepartamentoid
			AND centrocostoid	= @pcentrocostoid
		) 
		BEGIN 
			INSERT INTO accesoxusuarioccosto
			(usuarioid,empresaid,lugarpagoid,centrocostoid)
			VALUES
			(@pusuarioid,@pempresaid,@plugarpagoid,@pcentrocostoid);
		
		END
	
	SELECT @error= 0
	SELECT @mensaje = ''		    

			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
