USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_buscarPostulacion_listadoTotal]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_buscarPostulacion_listadoTotal] '', 2, 1, 10  
-- =============================================
CREATE PROCEDURE [dbo].[sp_buscarPostulacion_listadoTotal]

	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@rutPostulante		varchar(10),	-- Nombre o rut del postulante
	@RutEmpresa		varchar(14),	-- Rut empresa que pertenece al holding
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta

AS
BEGIN
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	DECLARE @nl   char(2) = char(13) + char(10)
              
	DECLARE @sqlString nvarchar(max)
	
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
                            @totalorig = count(postulacionid)
					  FROM DocumentosTabla
					  '                              

			DECLARE @Parametros nvarchar(max)

			SET @Parametros =  N'@RutEmpresa varchar(14),@rutPostulante varchar(10), 
							 @lmensaje VARCHAR(100), @totalorig INT OUTPUT'
						
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

			EXECUTE sp_executesql @sqlString, @Parametros, 
								  @RutEmpresa, @rutPostulante,@lmensaje,
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
