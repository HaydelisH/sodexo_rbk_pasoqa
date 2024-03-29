USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_total]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernández 
-- Creado el: 11/10/2019
-- Modificado por: Gdiaz 11/01/2021
-- Descripcion: Listado Cliente
-- Ejemplo:exec sp_rl_proveedores_total '',1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_total]
	@buscar			VARCHAR(50),
	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@RutEmpresa		VARCHAR(10),    -- rut de empresa a la que pertenece 
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	
    --Variables
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @sqlString		nvarchar(max)
	
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	DECLARE @vdecimal DECIMAL (9,2)

	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos
	
	SET @sqlString = N'	
		With DocumentosTabla as 
		(							
			SELECT 
				RutProveedor
			FROM 
				rl_Proveedores
			WHERE Eliminado=0 --and RutEmpresa = @RutEmpresa' + @nl
		
	SET @sqlString += N')	
			SELECT
				@totalorig = count(RutProveedor)
			FROM 
				DocumentosTabla'

	DECLARE @Parametros nvarchar(max)
		
	SET @Parametros =  N'@Pinicio INT, @Pfin INT, @RutEmpresa VARCHAR(10), @totalorig INT OUTPUT'
	
	IF (@debug = 1)
	BEGIN
		PRINT @sqlString
	END

	EXECUTE sp_executesql @sqlString, @Parametros, 
						  @Pinicio, @Pfin, @RutEmpresa, @totalorig = @totalorig OUTPUT
						  
	SELECT @totalreg = (@totalorig/@decuantos)
	
	SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
        
	IF @vdecimal > 0 
		SELECT @total = @totalreg + 1
	ELSE
		SELECT @total = @totalreg
		
	SET @totalreg = @totalreg * @decuantos
 
	SELECT  @total as total, @totalreg as totalreg	
							  
END
GO
