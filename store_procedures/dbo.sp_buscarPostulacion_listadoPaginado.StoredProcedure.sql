USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_buscarPostulacion_listadoPaginado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_buscarPostulacion_listadoPaginado] '', 2, 1, 10  
-- =============================================
CREATE PROCEDURE [dbo].[sp_buscarPostulacion_listadoPaginado]

	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@rutPostulante		varchar(10),	-- Nombre o rut del postulante
	@RutEmpresa		varchar(14),	-- Rut empresa que pertenece al holding
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
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
			Postulantes.nombre AS nombrePostulante,
			CASE 
				WHEN Postulantes.contratado IS NULL THEN ''No''
				WHEN Postulantes.contratado = 0 THEN ''No''
				WHEN Postulantes.contratado = 1 THEN ''SI'' 
			END AS contratado,
            ROW_NUMBER()Over(Order by Postulaciones.postulacionid DESC) As RowNum
		FROM Postulaciones
		INNER JOIN Postulantes  			ON Postulantes.postulanteid = Postulaciones.postulanteid
		INNER JOIN EstadosPostulacion		ON EstadosPostulacion.estadoPostulacionid = Postulaciones.estadoPostulacionid
		INNER JOIN centroscosto     	    ON centroscosto.centrocostoid = Postulaciones.centrocostoid
		INNER JOIN CargosEmpleado			ON CargosEmpleado.idCargoEmpleado = Postulaciones.idCargoEmpleado ' + @nl
		
    SET @sqlString += N' WHERE 1 = 1 ' + @nl

    IF (@rutPostulante != '')
    BEGIN
        SET @sqlString += ' AND ( Postulantes.rut = @rutPostulante) ' + @nl
    END
                
    IF (@RutEmpresa != '')
	BEGIN
        SET @sqlString += ' AND Postulaciones.RutEmpresa = @RutEmpresa ' + @nl
	END

	SET @sqlString += N') 
				  SELECT 
						 fechaPostulacion
						,nombreCargo
						,nombrecentrocosto
						,ResultadoPostulacion
						,nombrePostulante
						,contratado
				  FROM DocumentosTabla 
				  WHERE	RowNum BETWEEN @Pinicio AND @Pfin '        
				  
		DECLARE @Parametros nvarchar(max)

		SET @Parametros =  N'@RutEmpresa varchar(14),@rutPostulante varchar(10), @Pinicio INT, 
                             @Pfin INT, 
							 @lmensaje VARCHAR(100), @estadoPostulacionNoApto VARCHAR(15)'
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @RutEmpresa,@rutPostulante, @Pinicio, 
                              @Pfin, @lmensaje, @estadoPostulacionNoApto
                       	
    RETURN                                                             

END
GO
