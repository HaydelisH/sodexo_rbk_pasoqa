USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_listado_centroscosto_total]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 17/06/2019
-- Descripcion:	Lista lugares de centros de costo para relación con el perfil de usuario
-- Ejemplo:exec sp_accesoxusuario_listado_centroscosto_total 1,'33333333-3','014','xx',1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_listado_centroscosto_total]
	
	@pusuarioid			VARCHAR(10),	-- id del usuario
	@pempresaid			VARCHAR (50),	-- id de la empresa
	@plugarpagoid		VARCHAR (14),	-- id lugar pago
	--@pdepartamentoid	VARCHAR (14),	-- id departamento
	@pcentrocosto		NVARCHAR (50),	-- texto de busqueda 
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
		
	SET @nombrelike = '%' + UPPER(RTRIM(@pcentrocosto)) + '%';	

	SELECT @totalreg = (COUNT(*) /@decuantos)
	FROM 
		(
	SELECT 
		centrocostoid,
		nombrecentrocosto,
		checkconsulta,
		RowNum
	FROM 
		(		
		SELECT 
		centroscosto.centrocostoid,
		centroscosto.nombrecentrocosto,
		CASE 
		WHEN EXISTS (SELECT 1 FROM accesoxusuarioccosto 
		WHERE accesoxusuarioccosto.usuarioid = @pusuarioid 
		AND accesoxusuarioccosto.empresaid = @pempresaid
		AND accesoxusuarioccosto.lugarpagoid = @plugarpagoid
		--AND accesoxusuarioccosto.departamentoid = @pdepartamentoid
		AND accesoxusuarioccosto.centrocostoid = centroscosto.centrocostoid)
		THEN 'checked'
		ELSE '' END AS checkconsulta,
		ROW_NUMBER()Over(Order by centroscosto.centrocostoid) As RowNum
		FROM centroscosto
		WHERE 
		((UPPER(RTRIM(centroscosto.nombrecentrocosto)) LIKE @nombrelike) 
		OR 	  (UPPER(RTRIM(centroscosto.centrocostoid)) LIKE @nombrelike)
		OR    (@pcentrocosto = ''))
		--and (departamentoid = @pdepartamentoid ) 
		and (lugarpagoid = @plugarpagoid ) 
		and (empresaid = @pempresaid ) 
		)  ResultadoPaginado
		) as comosifuerauntabla
	
		SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
		
		IF @vdecimal > 0 
			SELECT @total = @totalreg + 1
		ELSE
			SELECT @total = @totalreg
						
		select @total as total			
			
	RETURN;
END
GO
