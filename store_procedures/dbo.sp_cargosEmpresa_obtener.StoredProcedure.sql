USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_cargosEmpresa_obtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_cargosEmpresa_obtener]
    @RutEmpresa VARCHAR(10),
    @dias INT,
    @ahora DATE,
    @proximidadCaducidadId INT,
    @dias2 INT,
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	SET NOCOUNT ON;
    DECLARE @nl				char(2) = char(13) + char(10)
    DECLARE @sqlString nvarchar(max)

    SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT
            CargosEmpresa.RutEmpresa,
            CargosEmpresa.link,
            CONVERT(char(10), CargosEmpresa.fechaCaducidadLink, 105) AS fechaCaducidadLink,
            CargosEmpleado.idCargoEmpleado,
            CargosEmpleado.Titulo,
            Empresas.RazonSocial
        FROM CargosEmpleado
        INNER JOIN CargosEmpresa
            ON CargosEmpresa.idCargoEmpleado = CargosEmpleado.idCargoEmpleado
        INNER JOIN Empresas
            ON Empresas.RutEmpresa = CargosEmpresa.RutEmpresa
            AND Empresas.RutEmpresa = @RutEmpresa ' + @nl
    SET @sqlString += N' WHERE 1 = 1 ' + @nl

    IF (@proximidadCaducidadId = 1) -- 'Sin Link'
        BEGIN
            SET @sqlString += ' AND CargosEmpresa.fechaCaducidadLink IS NULL OR CargosEmpresa.fechaCaducidadLink = '''' ' + @nl
        END
    IF (@proximidadCaducidadId = 6) -- 'Link caducado'
        BEGIN
            SET @sqlString += ' AND CargosEmpresa.fechaCaducidadLink < @ahora ' + @nl
        END
    IF (@proximidadCaducidadId = 4 OR @proximidadCaducidadId = 3 OR @proximidadCaducidadId = 2) -- 'Menos de ' . DIAS_[COLOR] . ' dias'
        BEGIN
            SET @sqlString += ' AND DATEDIFF(day, @ahora, CargosEmpresa.fechaCaducidadLink) < @dias AND CargosEmpresa.fechaCaducidadLink >= @ahora ' + @nl
        END
    IF (@proximidadCaducidadId = 5) -- 'Sobre ' . DIAS_VERDE . ' dias'
        BEGIN
            SET @sqlString += ' AND DATEDIFF(day, @ahora, CargosEmpresa.fechaCaducidadLink) >= @dias AND CargosEmpresa.fechaCaducidadLink >= @ahora ' + @nl
        END
    IF (@proximidadCaducidadId = 7 OR @proximidadCaducidadId = 8) -- 'Entre ' . DIAS_[COLOR INFERIOR] . ' y ' . DIAS_[COLOR SUPERIOR] . ' dias'
        BEGIN
            SET @sqlString += ' AND DATEDIFF(day, @ahora, CargosEmpresa.fechaCaducidadLink) >= @dias AND DATEDIFF(day, @ahora, CargosEmpresa.fechaCaducidadLink) < @dias2 AND CargosEmpresa.fechaCaducidadLink >= @ahora ' + @nl
        END



	SET @sqlString += N') 
				  SELECT 
						 RutEmpresa
						,link
						,fechaCaducidadLink
						,idCargoEmpleado
						,Titulo
						,RazonSocial
				  FROM DocumentosTabla '        
    DECLARE @Parametros nvarchar(max)

    SET @Parametros =  N'@RutEmpresa varchar(14), @ahora DATE,@dias INT,@dias2 INT'
    IF (@debug = 1)
    BEGIN
        PRINT @sqlString
    END

    EXECUTE sp_executesql @sqlString, @Parametros, 
                            @RutEmpresa, @ahora, @dias, @dias2
                       	
    RETURN                                                             


END;
GO
