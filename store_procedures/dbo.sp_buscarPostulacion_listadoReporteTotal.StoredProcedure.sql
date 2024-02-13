USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_buscarPostulacion_listadoReporteTotal]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_buscarPostulacion_listadoReporteTotal] '', 2, 1, 10  
-- =============================================
CREATE PROCEDURE [dbo].[sp_buscarPostulacion_listadoReporteTotal]

	@pagina					INT,	    -- numero de pagina
	@decuantos          DECIMAL,	    -- total pagina
	@rutPostulante		    varchar(10),	-- Nombre o rut del postulante
	@nombrePostulante		    varchar(100),	-- Nombre o rut del postulante
	@RutEmpresa		    varchar(14),	-- Rut empresa que pertenece al holding
	@idCargoEmpleado	varchar(14),	-- Identificador de cargo
	@centrocostoid		varchar(14),	-- Centro de costo
    @fechaInicio         date,
    @fechaFin            date,
    @estadoPostulacionid    INT,
    @discapacidadid    INT,
    @disponibilidadid    INT,
	@debug			    tinyint	= 0		-- DEBUG 1= imprime consulta

AS
BEGIN
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	DECLARE @nl   char(2) = char(13) + char(10)
	--DECLARE @rutPostulanteLIKE	VARCHAR(100)
	DECLARE @nombrePostulanteLIKE	VARCHAR(100)
              
	DECLARE @sqlString nvarchar(max)
	
	--SET @rutPostulanteLIKE = '%' + @rutPostulante + '%'; 
	SET @nombrePostulanteLIKE = '%' + @nombrePostulante + '%'; 
	 
    DECLARE @vdecimal DECIMAL (9,2)
    
	DECLARE @lmensaje		VARCHAR(100)

	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
			Postulaciones.postulacionid
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

        IF (@estadoPostulacionid != 0)
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
                            @totalorig = count(postulacionid)
					  FROM DocumentosTabla
					  '                              

			DECLARE @Parametros nvarchar(max)

			SET @Parametros =  N'@RutEmpresa varchar(14), @rutPostulante VARCHAR(10),
                             @nombrePostulante VARCHAR(100), @nombrePostulanteLIKE VARCHAR(100), 
                             @idCargoEmpleado VARCHAR(14), @centrocostoid VARCHAR(14), @fechaInicio DATE,
							 @fechaFin DATE, @estadoPostulacionid INT, @discapacidadid INT,
							 @lmensaje VARCHAR(100),@disponibilidadid INT, @totalorig INT OUTPUT'
						
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

			EXECUTE sp_executesql @sqlString, @Parametros, 
								  @RutEmpresa, @rutPostulante, @nombrePostulante,
                                  @nombrePostulanteLIKE, @idCargoEmpleado,
                                  @centrocostoid, @fechaInicio, @fechaFin, @estadoPostulacionid, @discapacidadid,@lmensaje,@disponibilidadid,
								  @totalorig = @totalorig OUTPUT
										
						
			SELECT @totalreg = (@totalorig/@decuantos)
			
			SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
	            
			 IF @vdecimal > 0 
				SELECT @total = @totalreg + 1
			 ELSE
				SELECT @total = @totalreg
				
			SET @totalreg = @totalreg * @decuantos
		 
		select  @total as total, @totalreg as totalreg	                                                                       
	 RETURN                   
END
GO
