USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_buscarPostulacion_listadoPaginado2]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_buscarPostulacion_listadoPaginado2] '', 2, 1, 10  
-- =============================================
CREATE PROCEDURE [dbo].[sp_buscarPostulacion_listadoPaginado2]

	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@nombrePostulante		varchar(100),	-- Nombre o rut del postulante
	@RutEmpresa		varchar(14),	-- Rut empresa que pertenece al holding
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @nombrePostulanteLIKE	VARCHAR(100)
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
    SET @nombrePostulanteLIKE = '%' + @nombrePostulante + '%'; 
	
	DECLARE @sqlString nvarchar(max)
	
	DECLARE @lmensaje		VARCHAR(100)
	
	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
            Postulantes.postulanteid,
            Postulantes.rut,
            Postulantes.nombre,
            ROW_NUMBER()Over(Order by Postulantes.postulanteid DESC) As RowNum
		FROM Postulantes
		INNER JOIN Postulaciones  			ON Postulaciones.postulanteid = Postulantes.postulanteid ' + @nl
		
    SET @sqlString += N' WHERE 1 = 1 ' + @nl

    IF (@nombrePostulante != '')
    BEGIN
        SET @sqlString += ' AND ( Postulantes.nombre LIKE @nombrePostulanteLIKE) ' + @nl
    END
				
    IF (@RutEmpresa != '')
	BEGIN
        SET @sqlString += ' AND Postulaciones.RutEmpresa = @RutEmpresa ' + @nl
	END

    SET @sqlString += ' GROUP BY Postulantes.postulanteid, Postulantes.rut, Postulantes.nombre ' + @nl

	SET @sqlString += N') 
				  SELECT 
						 postulanteid
						,rut
						,nombre
				  FROM DocumentosTabla 
				  WHERE	RowNum BETWEEN @Pinicio AND @Pfin '        
				  
		DECLARE @Parametros nvarchar(max)

		SET @Parametros =  N'@RutEmpresa varchar(14),@Pinicio INT, 
                             @Pfin INT, @nombrePostulante VARCHAR(100), @nombrePostulanteLIKE VARCHAR(100), 
							 @lmensaje VARCHAR(100)'
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @RutEmpresa,@Pinicio, 
                              @Pfin, @nombrePostulante,
							  @nombrePostulanteLIKE, @lmensaje
                       	
    RETURN                                                             

END
GO
