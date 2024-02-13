USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_centroscosto_totalPaginado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 29-04-2019
-- Descripcion: Listar todos los centros de costo 
-- Ejemplo:exec sp_centroscosto_totalPaginado 1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_centroscosto_totalPaginado]
	@pagina					INT,
	@decuantos				DECIMAL,
	@pidCentroCosto			NVARCHAR(14),
	@pnombreCentroCosto		VARCHAR(50),
	@plugarpagoid			NVARCHAR(14),
	@pnombrelugarpagoid		VARCHAR(50),
	@pempresaid				NVARCHAR(10),
	@debug					TINYINT	= 0		-- DEBUG 1= imprime consulta
AS	
BEGIN
	
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	
	DECLARE @pnombreCentroCostoLIKE VARCHAR(50)
	DECLARE @pnombrelugarpagoidLIKE VARCHAR(50)
	
	DECLARE @vdecimal DECIMAL (9,2)
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos		
	
	SET @pnombreCentroCostoLIKE = '%' + @pnombreCentroCosto + '%';
	SET @pnombrelugarpagoidLIKE = '%' + @pnombrelugarpagoid + '%';
	
	DECLARE @sqlString nvarchar(max)

	SET @sqlString = N'									  
	With DocumentosTabla as 
	(
		SELECT 
			cc.centrocostoid
		FROM 
			centroscosto cc
		INNER JOIN lugarespago lp ON cc.lugarpagoid = lp.lugarpagoid AND cc.empresaid = lp.empresaid
		INNER JOIN Empresas ON lp.empresaid = Empresas.RutEmpresa
		WHERE 1 = 1' + @nl
		
	IF ( @pidCentroCosto != '' )
	BEGIN
		SET @sqlString += ' AND cc.centrocostoid = @pidCentroCosto ' + @nl
	END
	
	IF ( @pnombreCentroCosto != '' )
	BEGIN
		SET @sqlString += ' AND cc.nombrecentrocosto LIKE @pnombreCentroCostoLIKE ' + @nl
	END
	
	IF ( @plugarpagoid != '' )
	BEGIN
		SET @sqlString += ' AND cc.lugarpagoid = @plugarpagoid ' + @nl
	END
	
	IF ( @pnombrelugarpagoid != '' )
	BEGIN
		SET @sqlString += ' AND lp.nombrelugarpago LIKE @pnombrelugarpagoidLIKE ' + @nl
	END
	
	IF ( @pempresaid != '' )
	BEGIN
		SET @sqlString += ' AND RutEmpresa = @pempresaid' + @nl
	END
		
	SET @sqlString += N') 
		SELECT 
				 @totalorig = count(centrocostoid)
		FROM  DocumentosTabla'
		
	DECLARE @Parametros nvarchar(max)

	SET @Parametros =  N'@Pinicio INT, @Pfin INT, @pidCentroCosto NVARCHAR(14), @pnombreCentroCosto VARCHAR(50),
						 @plugarpagoid NVARCHAR(14), @pnombrelugarpagoid VARCHAR(50), @pnombreCentroCostoLIKE VARCHAR(50),
						 @pnombrelugarpagoidLIKE VARCHAR(50),@pempresaid NVARCHAR(10), 
						 @totalorig INT OUTPUT'
	IF (@debug = 1)
	BEGIN
		PRINT @sqlString
	END

	EXECUTE sp_executesql @sqlString, @Parametros, 
						  @Pinicio, @Pfin, @pidCentroCosto, @pnombreCentroCosto, @plugarpagoid, @pnombrelugarpagoid, @pnombreCentroCostoLIKE, @pnombrelugarpagoidLIKE,
						  @pempresaid,@totalorig = @totalorig OUTPUT
			
	
--	SELECT (cast(@dividendo as decimal(18,6))/cast(@divisor as decimal(18,6)))
--GO
	--	SELECT @totalreg = ( @totalorig/@decuantos )			  
	SELECT @totalreg = cast(@totalorig as decimal(18,6))/cast(@decuantos  as decimal(18,6))
		
	SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
        
	IF @vdecimal > 0 
		SELECT @total = @totalreg + 1
	ELSE
		SELECT @total = @totalreg
		
	SET @totalreg = @totalreg * @decuantos
 
	SELECT  @total as total, @totalreg as totalreg
		                                                                      
	 RETURN  
	
END
GO
