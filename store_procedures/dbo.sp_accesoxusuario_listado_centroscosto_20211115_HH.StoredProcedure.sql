USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_accesoxusuario_listado_centroscosto_20211115_HH]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- Ejemplo:exec sp_accesoxusuario_listado_centroscosto '11111111-1','33333333-3','014','xx',1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_accesoxusuario_listado_centroscosto_20211115_HH]
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
	
	SET @nombrelike = '%' + UPPER(RTRIM(@pcentrocosto)) + '%';	

	SELECT *
	FROM 
		(		
		SELECT 
		centroscosto.centrocostoid,
		UPPER(centroscosto.nombrecentrocosto) AS nombrecentrocosto,
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
		WHERE RowNum BETWEEN (@pagina - 1) * @decuantos + 1 
		AND @pagina * @decuantos	
			
	RETURN;
END
GO
