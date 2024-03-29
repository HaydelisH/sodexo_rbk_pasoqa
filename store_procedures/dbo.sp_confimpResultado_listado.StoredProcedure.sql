USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_confimpResultado_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Lista correo
-- Ejemplo:exec sp_correos_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_confimpResultado_listado]
    @usuarioid varchar(10),
    @IdArchivo  		INT,
    @pagina					INT,	-- numero de pagina
    @decuantos          DECIMAL,	-- total pagina
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)

	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  

    DECLARE @sqlString nvarchar(max)

	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
        SELECT 
            usuarioid,
            fila,
            resultado,
            observaciones,
            tipotransaccion,
            ROW_NUMBER()Over(Order by fila ASC) As RowNum
        FROM ConfimpResultado 
        WHERE usuarioid=@usuarioid 
            AND IdArchivo = @IdArchivo 
        ' + @nl
    SET @sqlString += N') 
        SELECT 
            usuarioid,
            fila,
            resultado,
            observaciones,
            tipotransaccion,
            RowNum
        FROM DocumentosTabla
        WHERE RowNum BETWEEN @Pinicio AND @Pfin
        ORDER BY fila ASC
    '      
                
    DECLARE @Parametros nvarchar(max)
    
    SET @Parametros =  N'   @IdArchivo INT, @Pinicio INT, @Pfin INT,
                            @usuarioid VARCHAR(10)'
    IF (@debug = 1)
    BEGIN
        PRINT @sqlString
    END

    EXECUTE sp_executesql   @sqlString, @Parametros, 
                            @IdArchivo , @Pinicio , @Pfin,
                            @usuarioid
    RETURN                                                             

END
GO
