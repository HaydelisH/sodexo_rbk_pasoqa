USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_confimpResultado_listadoTotal]    Script Date: 1/22/2024 7:21:13 PM ******/
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
CREATE PROCEDURE [dbo].[sp_confimpResultado_listadoTotal]
    @usuarioid varchar(10),
    @IdArchivo  		INT,
    @pagina					INT,	-- numero de pagina
    @decuantos          DECIMAL,	-- total pagina
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	DECLARE @nl				char(2) = char(13) + char(10)
    DECLARE @vdecimal DECIMAL (9,2)

    DECLARE @sqlString nvarchar(max)

	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
        SELECT 
            fila
        FROM ConfimpResultado 
        WHERE usuarioid=@usuarioid 
            AND IdArchivo = @IdArchivo 
        ' + @nl
    SET @sqlString += N') 
        SELECT 
            @totalorig = count(fila)
        FROM DocumentosTabla
    '      

    DECLARE @Parametros nvarchar(max)
    
    SET @Parametros =  N'   @IdArchivo INT,
                            @usuarioid VARCHAR(10),
                            @totalorig INT OUTPUT'
    IF (@debug = 1)
    BEGIN
        PRINT @sqlString
    END

    EXECUTE sp_executesql   @sqlString, @Parametros, 
                            @IdArchivo,
                            @usuarioid,
                            @totalorig = @totalorig OUTPUT

    SELECT @totalreg = (@totalorig/@decuantos)
    
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
