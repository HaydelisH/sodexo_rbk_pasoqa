USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_listadoPaginado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29-04-2019
-- Descripcion: Listar todos los centros de costo 
-- Ejemplo:exec sp_centroscosto_listadoPaginado 1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_listadoPaginado]
	@pagina					INT,
	@decuantos				INT,
	@pidCentroCosto			NVARCHAR(14),
	@pnombreCentroCosto		VARCHAR(50),
	@plugarpagoid			NVARCHAR(14),
	@pnombrelugarpagoid		VARCHAR(50),
	@pempresaid				NVARCHAR(10),
	@debug					TINYINT	= 0		-- DEBUG 1= imprime consulta
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @pnombreCentroCostoLIKE VARCHAR(50)
	DECLARE @pnombrelugarpagoidLIKE VARCHAR(50)
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos
	SET @pnombreCentroCostoLIKE = '%' + @pnombreCentroCosto + '%';
	SET @pnombrelugarpagoidLIKE = '%' + @pnombrelugarpagoid + '%';
	
	DECLARE @sqlString nvarchar(max)

	SET @sqlString = N'									  
	
	With DocumentosTabla as 
	(
		SELECT 
			centrocostoid As idCentroCosto,
			nombrecentrocosto As Descripcion,
			lugarespago.lugarpagoid,
			lugarespago.nombrelugarpago,
			RutEmpresa,
			RazonSocial,
			ROW_NUMBER()Over(Order by centrocostoid ASC) As RowNum
		FROM 
			centroscosto
		INNER JOIN lugarespago ON centroscosto.lugarpagoid = lugarespago.lugarpagoid AND centroscosto.empresaid = lugarespago.empresaid
		INNER JOIN Empresas ON lugarespago.empresaid = Empresas.RutEmpresa
		WHERE 1= 1' + @nl
		
	IF ( @pidCentroCosto != '' )
	BEGIN
		SET @sqlString += ' AND centrocostoid = @pidCentroCosto' + @nl
	END
	
	IF ( @pnombreCentroCosto != '' )
	BEGIN
		SET @sqlString += ' AND nombrecentrocosto LIKE @pnombreCentroCostoLIKE' + @nl
	END
	
	IF ( @plugarpagoid != '' )
	BEGIN
		SET @sqlString += ' AND lugarespago.lugarpagoid = @plugarpagoid' + @nl
	END
	
	IF ( @pnombrelugarpagoid != '' )
	BEGIN
		SET @sqlString += ' AND lugarespago.nombrelugarpago LIKE  @pnombrelugarpagoidLIKE' + @nl
	END
	
	IF ( @pempresaid != '' )
	BEGIN
		SET @sqlString += ' AND RutEmpresa = @pempresaid' + @nl
	END
		
	SET @sqlString += N') 
		SELECT 
				idCentroCosto,
				Descripcion,
				lugarpagoid,
				nombrelugarpago,
				RutEmpresa,
				RazonSocial,
				RowNum
		FROM  DocumentosTabla
		WHERE RowNum BETWEEN @Pinicio AND @Pfin'
		
	DECLARE @Parametros nvarchar(max)
		
	SET @Parametros =  N'@Pinicio INT, @Pfin INT, @pidCentroCosto NVARCHAR(14), @pnombreCentroCosto VARCHAR(50),
						 @plugarpagoid NVARCHAR(14), @pnombrelugarpagoid VARCHAR(50), @pnombreCentroCostoLIKE VARCHAR(50),
						 @pnombrelugarpagoidLIKE VARCHAR(50),@pempresaid NVARCHAR(10)'
	IF (@debug = 1)
	BEGIN
		PRINT @sqlString
	END

	EXECUTE sp_executesql @sqlString, @Parametros, 
						  @Pinicio, @Pfin, @pidCentroCosto, @pnombreCentroCosto, @plugarpagoid, @pnombrelugarpagoid, @pnombreCentroCostoLIKE, @pnombrelugarpagoidLIKE,
						  @pempresaid
			
END
GO
