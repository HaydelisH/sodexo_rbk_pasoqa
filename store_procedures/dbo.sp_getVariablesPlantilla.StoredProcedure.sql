USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_getVariablesPlantilla]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Obtiene los datos del Contrato
-- Ejemplo:exec [sp_getVariablesPlantilla] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_getVariablesPlantilla]
	@idPlantilla INT,
    @PREFIJO_VAR VARCHAR(4),
    @SUFIJO_VAR VARCHAR(4),
    @SEPARADOR VARCHAR(2),
    @VAR_FECHA_LARGA VARCHAR(4),
    @VAR_FECHA_CORTA VARCHAR(4),
    @VAR_FECHA_LARGA_ST VARCHAR(4),
    @VAR_FECHA_CORTA_ST VARCHAR(4),
    @VAR_FECHA_INDEFINIDA VARCHAR(4),
    @VAR_NUM_A_LETRAS_MAYUS VARCHAR(4),
    @VAR_NUM_A_LETRAS_MINUS VARCHAR(4),
    @VAR_NUM_A_LETRAS_MIXTO VARCHAR(4),
    @VAR_NUM_SEPARADOR_MILES VARCHAR(4),
    @VAR_VACIA VARCHAR(4),
    @SOLO VARCHAR(15)
AS
BEGIN
    SET NOCOUNT ON

    DECLARE @LineaMin INT
    DECLARE @LineaMax INT
    DECLARE @Clausula VARCHAR(max)
    DECLARE @inicio INT
    DECLARE @fin INT
    DECLARE @var VARCHAR(150)
    DECLARE @SOLO_LIKE VARCHAR(20)

    SET @SOLO_LIKE = '%' + @SOLO + '%'

    CREATE TABLE #Clausulas (Linea int, texto varchar(max))
    CREATE TABLE #Variables (Variable varchar(50) COLLATE database_default)

    INSERT INTO #Clausulas (Linea,texto)
        SELECT 
            ROW_NUMBER() over (order by Clausulas.Texto asc) as Linea,
            Clausulas.Texto
        FROM Clausulas
        INNER JOIN PlantillasClausulas 
            ON PlantillasClausulas.idClausula = Clausulas.idClausula 
        WHERE PlantillasClausulas.idPlantilla = @idPlantilla

    SELECT 
        @LineaMin = min( Linea ),
        @LineaMax = Max( Linea )
    FROM #Clausulas

    WHILE @LineaMin <= @LineaMax
        BEGIN
            SELECT @Clausula = texto FROM #Clausulas WHERE Linea = @LineaMin
            SET @inicio = 0
            SET @fin = 0
            WHILE @inicio <> 1
                BEGIN
                    SET @inicio = @inicio + @fin
                    SET @inicio = CHARINDEX('[', @Clausula, @inicio) + 1
                    IF (@inicio != 1)
                    BEGIN
                        SET @fin = CHARINDEX(']', @Clausula, @inicio) - @inicio + 2
                        SET @var = SUBSTRING(@Clausula, @inicio - 1, @fin)
                        SET @var = REPLACE(@var, @VAR_VACIA, '')
                        SET @var = REPLACE(@var, @VAR_NUM_SEPARADOR_MILES, '')
                        SET @var = REPLACE(@var, @VAR_FECHA_LARGA_ST, '')
                        SET @var = REPLACE(@var, @VAR_NUM_A_LETRAS_MIXTO, '')
                        SET @var = REPLACE(@var, @VAR_FECHA_INDEFINIDA, '')
                        SET @var = REPLACE(@var, @VAR_FECHA_CORTA_ST, '')
                        SET @var = REPLACE(@var, @VAR_NUM_A_LETRAS_MINUS, '')
                        SET @var = REPLACE(@var, @VAR_NUM_A_LETRAS_MAYUS, '')
                        SET @var = REPLACE(@var, @VAR_FECHA_CORTA, '')
                        SET @var = REPLACE(@var, @VAR_FECHA_LARGA, '')
                        IF CHARINDEX(@SEPARADOR, @var, 0) > 0
                        BEGIN
                            SET @var = SUBSTRING(@var, 0, CHARINDEX(@SEPARADOR, @var, 0)) + @SUFIJO_VAR
                        END
                    END
                    INSERT INTO #Variables (Variable) SELECT @var;
                END
                SET @LineaMin = @LineaMin + 1
        END

    SELECT DISTINCT 
        REPLACE(REPLACE(REPLACE(Variable, @PREFIJO_VAR, ''), @SUFIJO_VAR, ''), '.', '_') AS Variable,
        ConfimpArchivoDet.Orden,
        ConfimpArchivoDet.Nombre,
        ConfimpArchivoDet.TipoDato,
        ConfimpArchivoDet.Ancho,
        ConfimpArchivoDet.Obligatorio,
        ConfimpArchivoDet.GuardaEnSql,
        ConfimpArchivoDet.NombreExterno--,
        --ConfimpArchivoDet.etiqueta
    FROM ConfimpArchivoDet
    LEFT JOIN #Variables ON Variable = ConfimpArchivoDet.NombreExterno
    WHERE 
        Variable IS NOT NULL
        AND Variable LIKE @SOLO_LIKE
    ORDER BY ConfimpArchivoDet.Orden

    DROP TABLE #Clausulas
    DROP TABLE #Variables
END
GO
