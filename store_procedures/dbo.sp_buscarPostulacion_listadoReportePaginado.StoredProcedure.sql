USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_buscarPostulacion_listadoReportePaginado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_buscarPostulacion_listadoReportePaginado] '', 2, 1, 10  
-- =============================================
CREATE PROCEDURE [dbo].[sp_buscarPostulacion_listadoReportePaginado]

	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@rutPostulante		    varchar(10),	-- Nombre o rut del postulante
	@nombrePostulante		    varchar(100),	-- Nombre o rut del postulante
	@RutEmpresa		varchar(14),	-- Rut empresa que pertenece al holding
	@idCargoEmpleado	varchar(14),	-- Identificador de cargo
	@centrocostoid		varchar(14),	-- Centro de costo
    @fechaInicio         date,
    @fechaFin            date,
    @estadoPostulacionid    INT,
    @discapacidadid    INT,
    @disponibilidadid    INT,
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	--DECLARE @rutPostulanteLIKE	VARCHAR(100)
	DECLARE @nombrePostulanteLIKE	VARCHAR(100)
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
	--SET @rutPostulanteLIKE = '%' + @rutPostulante + '%'; 
	SET @nombrePostulanteLIKE = '%' + @nombrePostulante + '%'; 
	
	DECLARE @sqlString nvarchar(max)
	
	DECLARE @lmensaje		VARCHAR(100)
	
	DECLARE @estadoPostulacionNoApto VARCHAR(15)

	SELECT @estadoPostulacionNoApto = EstadosPostulacion.nombre FROM EstadosPostulacion WHERE estadopostulacionid = 3

	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
			CONVERT(char(10), Postulaciones.fechaPostulacion, 105) AS fechaPostulacion,
			CargosEmpleado.Descripcion AS nombreCargo,
			centroscosto.nombrecentrocosto,
			CASE 
				WHEN Postulantes.blackList = 1 AND Postulaciones.estadopostulacionid = 2 THEN @estadoPostulacionNoApto
				ELSE EstadosPostulacion.nombre
			END AS ResultadoPostulacion,
            Postulantes.rut,
            Postulantes.nombre,
            Postulantes.telefono,
            Postulantes.email,
			Postulantes.observacion,
			Postulantes.discapacidad,
			CASE 
				WHEN Postulantes.contratado IS NULL THEN ''No''
				WHEN Postulantes.contratado = 0 THEN ''No''
				WHEN Postulantes.contratado = 1 THEN ''SI'' 
			END AS contratado,
			Disponibilidad.nombre AS disponibilidadNombre,
            ROW_NUMBER()Over(Order by Postulaciones.postulacionid DESC) As RowNum
		FROM Postulaciones
		INNER JOIN Postulantes  			ON Postulantes.postulanteid = Postulaciones.postulanteid
		INNER JOIN Disponibilidad			ON Disponibilidad.disponibilidadid = Postulantes.disponibilidadid
		INNER JOIN EstadosPostulacion		ON EstadosPostulacion.estadoPostulacionid = Postulaciones.estadoPostulacionid
		INNER JOIN centroscosto     	    ON centroscosto.centrocostoid = Postulaciones.centrocostoid
		INNER JOIN CargosEmpleado			ON CargosEmpleado.idCargoEmpleado = Postulaciones.idCargoEmpleado ' + @nl
		
    SET @sqlString += N' WHERE 1 = 1 ' + @nl

	IF (@rutPostulante != '')
	BEGIN
		SET @sqlString += ' AND ( Postulantes.rut = @rutPostulante) ' + @nl
	END
				
	IF (@nombrePostulante != '')
	BEGIN
		SET @sqlString += ' AND ( Postulantes.nombre LIKE @nombrePostulanteLIKE) ' + @nl
	END
				
    IF (@RutEmpresa != '')
	BEGIN
        SET @sqlString += ' AND Postulaciones.RutEmpresa = @RutEmpresa ' + @nl
	END

    IF (@idCargoEmpleado != '0' and @idCargoEmpleado != '')
    BEGIN
        SET @sqlString += ' AND Postulaciones.idCargoEmpleado = @idCargoEmpleado ' + @nl
    END

    IF (@centrocostoid != '0' and @centrocostoid != '')
    BEGIN
        SET @sqlString += ' AND Postulaciones.centrocostoid = @centrocostoid ' + @nl
    END

    IF ( @fechaInicio IS NOT NULL AND @fechaFin IS NULL)  
    BEGIN
        SET @fechaFin = DATEADD (DAY, 1,@fechaInicio)
        SET @sqlString += ' AND Postulaciones.fechaPostulacion BETWEEN @fechaInicio AND @fechaFin' + @nl
    END
    
    IF ( @fechaInicio IS NOT NULL AND @fechaFin IS NOT NULL)  
    BEGIN
        SET @sqlString += ' AND Postulaciones.fechaPostulacion BETWEEN @fechaInicio AND @fechaFin' + @nl
    END
    
    IF ( @fechaInicio IS NULL AND @fechaFin IS NOT NULL)  
    BEGIN
        SET @sqlString += ' AND Postulaciones.fechaPostulacion <= @fechaFin' + @nl
    END

    IF (@estadoPostulacionid = 3) -- No aptos
		BEGIN
			SET @sqlString += ' AND (Postulaciones.estadoPostulacionid = @estadoPostulacionid 
			OR (Postulaciones.estadoPostulacionid = 2 AND Postulantes.blackList = 1))' + @nl
		END
	ELSE IF (@estadoPostulacionid = 2) -- Apto's
		BEGIN
			SET @sqlString += ' AND Postulaciones.estadoPostulacionid = @estadoPostulacionid 
			AND Postulantes.blackList IS NULL' + @nl
		END
	ELSE IF (@estadoPostulacionid != 0) 
		BEGIN
			SET @sqlString += ' AND Postulaciones.estadoPostulacionid = @estadoPostulacionid ' + @nl
		END

    IF (@discapacidadid = 1)
		BEGIN
			SET @sqlString += ' AND Postulantes.discapacitado = @discapacidadid ' + @nl
		END
	ELSE IF (@discapacidadid = 2)
		BEGIN
			SET @sqlString += ' AND Postulantes.discapacitado IS NULL ' + @nl
		END

    IF (@disponibilidadid != '0' and @disponibilidadid != '')
    BEGIN
        SET @sqlString += ' AND Postulantes.disponibilidadid = @disponibilidadid ' + @nl
    END

	SET @sqlString += N') 
				  SELECT 
						 fechaPostulacion
						,nombreCargo
						,nombrecentrocosto
						,ResultadoPostulacion
                        ,rut
                        ,nombre
                        ,telefono
                        ,email
						,observacion
						,discapacidad
						,contratado
						,disponibilidadNombre
				  FROM DocumentosTabla 
				  WHERE	RowNum BETWEEN @Pinicio AND @Pfin '        
				  
		DECLARE @Parametros nvarchar(max)

		SET @Parametros =  N'@RutEmpresa varchar(14), @Pinicio INT, 
                             @Pfin INT, @rutPostulante VARCHAR(10),
                             @nombrePostulante VARCHAR(100), @nombrePostulanteLIKE VARCHAR(100), 
                             @idCargoEmpleado VARCHAR(14), @centrocostoid VARCHAR(14), @fechaInicio DATE,
							 @fechaFin DATE, @estadoPostulacionid INT, @discapacidadid INT,
							 @lmensaje VARCHAR(100),@disponibilidadid INT, @estadoPostulacionNoApto VARCHAR(15)'
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @RutEmpresa, @Pinicio, 
                              @Pfin, @rutPostulante, @nombrePostulante,
							  @nombrePostulanteLIKE, @idCargoEmpleado,
                              @centrocostoid, @fechaInicio, @fechaFin, @estadoPostulacionid, @discapacidadid, @lmensaje,@disponibilidadid,
							  @estadoPostulacionNoApto
                       	
    RETURN                                                             

END
GO
