USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_acctodo_empresa]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 17/06/2019
-- Descripcion: crea los permisos para todos los centros de costo y lugares de pago de una empresa
-- Ejemplo:exec sp_accesoxusuario_acctodo_empresa '11111111-1','33333333-3'
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_acctodo_empresa]
@pusuarioid			VARCHAR(10),	-- id del usuario
@pempresaid			VARCHAR (50)	-- id de la empresa
	
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
		
	INSERT accesoxusuariolugarespago (usuarioid,empresaid,lugarpagoid)
	SELECT @pusuarioid, @pempresaid, LP.lugarpagoid
	FROM lugarespago as LP
	LEFT JOIN accesoxusuariolugarespago AS ACCLP	ON ACCLP.usuarioid = @pusuarioid
													AND ACCLP.empresaid = @pempresaid
													AND ACCLP.lugarpagoid = LP.lugarpagoid
	WHERE ACCLP.lugarpagoid IS NULL and LP.empresaid = @pempresaid

	
	
	/*INSERT accesoxusuariodepartamentos (usuarioid,empresaid,lugarpagoid,departamentoid)
	SELECT @pusuarioid,@pempresaid , DP.lugarpagoid, DP.departamentoid
	FROM lugarespago AS LP
	INNER JOIN departamentos DP ON LP.lugarpagoid = DP.lugarpagoid 
    AND LP.empresaid = DP.empresaid
	LEFT JOIN accesoxusuariodepartamentos AS ACDP	on  
											ACDP.usuarioid		= @pusuarioid
											AND ACDP.empresaid		= @pempresaid										
											AND ACDP.lugarpagoid	= LP.lugarpagoid
											AND ACDP.departamentoid	= DP.departamentoid	
	WHERE ACDP.departamentoid IS NULL
	AND LP.empresaid = @pempresaid*/

	
	INSERT accesoxusuarioccosto (usuarioid,empresaid,lugarpagoid,centrocostoid)
	SELECT @pusuarioid,@pempresaid, LP.lugarpagoid, CC.centrocostoid 
	FROM lugarespago AS LP
	INNER JOIN centroscosto CC ON CC.lugarpagoid = LP.lugarpagoid
    AND CC.empresaid = LP.empresaid
	LEFT JOIN accesoxusuarioccosto AS ACC	on  
											ACC.usuarioid = @pusuarioid
											AND ACC.empresaid = @pempresaid										
											AND ACC.lugarpagoid		= LP.lugarpagoid
											AND ACC.centrocostoid	= CC.centrocostoid
	WHERE ACC.centrocostoid IS NULL
	AND LP.empresaid = @pempresaid

	SELECT @error= 0
	SELECT @mensaje = ''			
			
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
