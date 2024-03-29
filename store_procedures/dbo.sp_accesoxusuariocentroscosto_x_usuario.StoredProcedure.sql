USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuariocentroscosto_x_usuario]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 11/07/2017
-- Descripcion:	Lista centros de costo según usuario
-- Ejemplo:exec sp_accesoxusuariocentroscosto_x_usuario '11111111-1','55555555-5','cc1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuariocentroscosto_x_usuario]
@pusuarioid			NVARCHAR(50),	-- id del usuario
@pempresaid			NVARCHAR(50),	-- id de la empresa
@pcentrocosto		NVARCHAR(50)	-- texto de busqueda 

AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @nombrelike		NVARCHAR(50)
	
	SET @nombrelike = '%' + UPPER(RTRIM(@pcentrocosto)) + '%';	
	
	SELECT 
	usuarioid,
	accesoxusuarioccosto.empresaid,
	centroscosto.centrocostoid,
	UPPER(centroscosto.nombrecentrocosto) AS nombrecentrocosto
	FROM accesoxusuarioccosto
	LEFT JOIN centroscosto ON accesoxusuarioccosto.centrocostoid = centroscosto.centrocostoid
	WHERE accesoxusuarioccosto.usuarioid	= @pusuarioid
	AND accesoxusuarioccosto.empresaid		= @pempresaid
	AND ((UPPER(RTRIM(centroscosto.nombrecentrocosto))	LIKE @nombrelike) 
	OR 	(UPPER(RTRIM(centroscosto.centrocostoid))		LIKE @nombrelike)
	OR  (@pcentrocosto = ''))
	AND centroscosto.empresaid = @pempresaid
	
	RETURN;
END
GO
