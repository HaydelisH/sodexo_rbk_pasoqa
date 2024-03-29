USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_listado_lugarespago_total]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 07/07/2017
-- Descripcion:	Total de registros para consulta listado de lugares de pago según consulta
-- Ejemplo:exec sp_accesoxusuario_listado_lugarespago_total  1,'55555555-5','N',1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_listado_lugarespago_total]
@pusuarioid			VARCHAR(10),	-- id del usuario
@pempresaid			VARCHAR (50),	-- id de la empresa
@plugarpago			NVARCHAR (50),	-- texto de busqueda 
@pagina				INT,			-- numero de pagina
@decuantos			DECIMAL			-- total pagina
	
AS	
BEGIN
	SET NOCOUNT ON;
	DECLARE @nombrelike		NVARCHAR(50)
	DECLARE @error			INT
	DECLARE @mensaje		VARCHAR(100)
	DECLARE @totalreg		DECIMAL (9,2)
	DECLARE @vdecimal		DECIMAL (9,2)
	DECLARE @total			INT
		
	SET @nombrelike = '%' + UPPER(RTRIM(@plugarpago)) + '%';	

	SELECT @totalreg = (COUNT(*) /@decuantos)
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
		ELSE '' END AS checkconsulta
		FROM lugarespago
		WHERE ((UPPER(RTRIM(lugarespago.nombrelugarpago)) LIKE @nombrelike) 
		OR 	  (UPPER(RTRIM(lugarespago.lugarpagoid)) LIKE @nombrelike)
		OR    (@plugarpago = '') ) AND (empresaid = @pempresaid)
		) as comosifuerauntabla
	
		SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
		
		IF @vdecimal > 0 
			SELECT @total = @totalreg + 1
		ELSE
			SELECT @total = @totalreg

        SET @totalreg = @totalreg * @decuantos

		select @total as total, @totalreg as totalreg			
			
	RETURN;
END

GO
