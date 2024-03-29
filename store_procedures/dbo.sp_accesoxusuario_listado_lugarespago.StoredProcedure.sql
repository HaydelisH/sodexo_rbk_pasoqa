USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_listado_lugarespago]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 05/07/2017
-- Descripcion:	Lista lugares de pago para relación con el perfil de usuario
-- Ejemplo:exec sp_accesoxusuario_listado_lugarespago  1,'55555555-5','N',1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_listado_lugarespago]

	@pusuarioid			VARCHAR (10),	-- id del usuario
	@pempresaid			VARCHAR (50),	-- id de la empresa
	@plugarpago			NVARCHAR (50),	-- texto de busqueda 
	@pagina				INT,			-- numero de pagina
	@decuantos			DECIMAL			-- total pagina
	
AS	
BEGIN
	SET NOCOUNT ON;
	DECLARE @nombrelike		NVARCHAR(50)
	
	SET @nombrelike = '%' + UPPER(RTRIM(@plugarpago)) + '%';	

	SELECT 
		lugarpagoid,
		nombrelugarpago,
		checkconsulta,
		RowNum
	FROM 
		(	
		SELECT 
		lugarespago.lugarpagoid,
		lugarespago.nombrelugarpago,
		CASE 
		WHEN EXISTS 
		(
			SELECT 1 FROM accesoxusuariolugarespago
			WHERE accesoxusuariolugarespago.usuarioid = @pusuarioid 
			AND accesoxusuariolugarespago.empresaid		= @pempresaid
			AND accesoxusuariolugarespago.lugarpagoid		= lugarespago.lugarpagoid
		)
		THEN 'checked'
		ELSE '' END AS checkconsulta,
		ROW_NUMBER()Over(Order by lugarespago.lugarpagoid) As RowNum
		FROM lugarespago
		WHERE ((UPPER(RTRIM(lugarespago.nombrelugarpago)) LIKE @nombrelike) 
		OR 	  (UPPER(RTRIM(lugarespago.lugarpagoid)) LIKE @nombrelike)
		OR    (@plugarpago = '') ) AND (empresaid = @pempresaid)
		)  ResultadoPaginado
		WHERE RowNum BETWEEN (@pagina - 1) * @decuantos + 1 
		AND @pagina * @decuantos	
			
	RETURN;
END
GO
