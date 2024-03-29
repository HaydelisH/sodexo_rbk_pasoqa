USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernández 
-- Creado el: 11/10/2019
-- Modificado por: Gdiaz 11/01/2021
-- Descripcion: Listado 
-- Ejemplo:exec [sp_rl_proveedores_listado] '',1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_listado]
	@buscar			VARCHAR(50),
	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@RutEmpresa		VARCHAR(10),    -- rut de empresa
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	
    --Variables
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @sqlString		nvarchar(max)

	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos
	
	SET @sqlString = N'	
		With DocumentosTabla as 
		(							
			SELECT 
				RutProveedor,
				NombreProveedor,
				--RutEmpresa,
				ROW_NUMBER()Over(Order by RutProveedor) As RowNum,
				(
					SELECT 
						COUNT(C.idDocumento) As cantidad
					FROM
						Contratos C 
					INNER JOIN ContratoDatosVariables CDV ON C.idDocumento = CDV.idDocumento
					WHERE 
						--C.RutEmpresa = @RutEmpresa AND 
						CDV.rlRutProveedor = rl_Proveedores.RutProveedor
				) As Cantidad
			FROM 
				 rl_Proveedores
			WHERE Eliminado=0 --AND RutEmpresa = @RutEmpresa' + @nl
		
	SET @sqlString += N')	
			SELECT
				RutProveedor,
				NombreProveedor,
				--RutEmpresa,
				RowNum, 
				Cantidad
			FROM 
				DocumentosTabla
			WHERE RowNum BETWEEN @Pinicio AND @Pfin'

	DECLARE @Parametros nvarchar(max)
		
	SET @Parametros =  N'@Pinicio INT, @Pfin INT, @RutEmpresa VARCHAR(10)'
	
	IF (@debug = 1)
	BEGIN
		PRINT @sqlString
	END

	EXECUTE sp_executesql @sqlString, @Parametros, 
						  @Pinicio, @Pfin,@RutEmpresa
							  
END
GO
