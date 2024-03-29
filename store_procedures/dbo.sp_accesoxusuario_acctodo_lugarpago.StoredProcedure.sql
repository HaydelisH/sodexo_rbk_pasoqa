USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_acctodo_lugarpago]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 10/07/2017
-- Modificado 01/08/2017 RC
-- Descripcion: crea los permisos para todos los centros de costo de un lugar de pago
-- Ejemplo:exec sp_accesoxusuario_acctodo_lugarpago 1,'33333333-3','lugarpago1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_acctodo_lugarpago]
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

	/*INSERT accesoxusuariodepartamentos (usuarioid,empresaid,lugarpagoid,departamentoid)
	SELECT @pusuarioid,@pempresaid , @plugarpagoid, DP.departamentoid
	FROM lugarespago AS LP
	INNER JOIN departamentos DP ON LP.lugarpagoid = DP.lugarpagoid 
    AND LP.empresaid = DP.empresaid
	LEFT JOIN accesoxusuariodepartamentos AS ACDP	on  
											ACDP.usuarioid		= @pusuarioid
											AND ACDP.empresaid		= @pempresaid										
											AND ACDP.lugarpagoid	= @plugarpagoid
											AND ACDP.departamentoid	= DP.departamentoid	
	WHERE ACDP.departamentoid IS NULL
	AND LP.empresaid = @pempresaid
	AND DP.lugarpagoid = @plugarpagoid*/

	INSERT accesoxusuarioccosto (usuarioid,empresaid,lugarpagoid,centrocostoid)
	SELECT @pusuarioid,@pempresaid, @plugarpagoid, CC.centrocostoid 
	FROM lugarespago AS LP
	INNER JOIN centroscosto CC ON CC.lugarpagoid = LP.lugarpagoid
    AND CC.empresaid = LP.empresaid
	LEFT JOIN accesoxusuarioccosto AS ACC	on  
											ACC.usuarioid = @pusuarioid
											AND ACC.empresaid = @pempresaid										
											AND ACC.lugarpagoid		= @plugarpagoid
											AND ACC.centrocostoid	= CC.centrocostoid
	WHERE ACC.centrocostoid IS NULL
	AND LP.empresaid = @pempresaid
	AND LP.lugarpagoid = @plugarpagoid

	SELECT @error= 0
	SELECT @mensaje = ''			
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
