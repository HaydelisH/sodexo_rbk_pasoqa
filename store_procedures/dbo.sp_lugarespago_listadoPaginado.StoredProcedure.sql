USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_lugarespago_listadoPaginado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO

-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 16-01-2020
-- Descripcion:	Listado paginado de lugares de pago
-- Ejemplo:exec sp_lugarespago_listadoPaginado 1,10,'','',''
-- =============================================
CREATE PROCEDURE [dbo].[sp_lugarespago_listadoPaginado]
	@pagina					INT,
	@decuantos				INT,
	@plugarpagoid			NVARCHAR(14),
	@pnombrelugarpago		VARCHAR(50),
	@pempresaid				NVARCHAR(10),
	@debug					TINYINT	= 0		-- DEBUG 1= imprime consulta
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @pnombrelugarpagoLIKE VARCHAR(50)
	DECLARE @pdescripcionlugarpagoLIKE VARCHAR(50)
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos
	SET @pnombrelugarpagoLIKE = '%' + @pnombrelugarpago + '%';
	
	DECLARE @sqlString nvarchar(max)

	SET @sqlString = N'									  
		With DocumentosTabla as 
		(
			SELECT 
				lugarpagoid,
				nombrelugarpago,
				E.RutEmpresa,
				E.RutEmpresa As empresaid,
				E.RazonSocial,
				ROW_NUMBER()Over(Order by nombrelugarpago) As RowNum
			FROM lugarespago 
			INNER JOIN Empresas E ON E.RutEmpresa = empresaid
			WHERE 1 = 1' + @nl
	
	IF ( @plugarpagoid != '' )
	BEGIN
		SET @sqlString += ' AND lugarpagoid = @plugarpagoid' + @nl
	END
	
	IF ( @pnombrelugarpago != '' )
	BEGIN
		SET @sqlString += ' AND nombrelugarpago LIKE  @pnombrelugarpagoLIKE' + @nl
	END
		
	IF ( @pempresaid != '' )
	BEGIN
		SET @sqlString += ' AND E.RutEmpresa = @pempresaid' + @nl
	END
		
	SET @sqlString += N') 
		SELECT 
				lugarpagoid,
				nombrelugarpago,
				RutEmpresa,
				empresaid,
				RazonSocial,
				RowNum
		FROM  DocumentosTabla
		WHERE RowNum BETWEEN @Pinicio AND @Pfin'
	
	DECLARE @Parametros nvarchar(max)
		
	SET @Parametros =  N'@Pinicio INT, @Pfin INT, @plugarpagoid NVARCHAR(14), @pnombrelugarpago VARCHAR(50), @pnombrelugarpagoLIKE VARCHAR(50),
						 @pempresaid NVARCHAR(10)'
	IF (@debug = 1)
	BEGIN
		PRINT @sqlString
	END

	EXECUTE sp_executesql @sqlString, @Parametros, 
						  @Pinicio, @Pfin,@plugarpagoid, @pnombrelugarpago, @pnombrelugarpagoLIKE, 
						  @pempresaid
		
	RETURN;
END
GO
