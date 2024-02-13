USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichasDatosImportacion_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Haydelis Hernández
-- Create date: 05-07-2019
-- Description:	Listado de fichasDatosImportacion
-- Ejemplo: sp_fichasDatosImportacion_listado 0,0,'',1,10,'26131316-2'
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichasDatosImportacion_listado]
	@idficha			INT,
	@idestado			INT,
	@buscar             VARCHAR(50),	-- Palabra a buscar( nombre o rut de persona)
	@pagina             INT,            -- numero de pagina
	@decuantos          INT,            -- total pagina
	@pusuarioid			VARCHAR(10),
	@pempresaid			VARCHAR(10),
	@pdivision			VARCHAR(10),
	@debug				tinyint	= 0		-- debug 1 = imprime la consulta
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @buscarLike VARCHAR(100)
	DECLARE @empresaLike VARCHAR(100)
	DECLARE @divisionLike VARCHAR(100)
	DECLARE @Pinicio int 
	DECLARE @Pfin int
	DECLARE @nl   char(2) = char(13) + char(10);
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos	

	IF ( @buscar != '' )
		BEGIN 
			SET @buscarLike = '%' + @buscar + '%'
		END
		
	IF( @pempresaid != '' )
		BEGIN 
			SET @empresaLike = '%' + @pempresaid + '%'
		END
		
	IF( @pdivision != '' )
		BEGIN 
			SET @divisionLike = '%' + @pdivision + '%'
		END
		
	DECLARE @sqlString nvarchar(max)
	SET @sqlString = N'	
		With DocumentosTabla
				as (
				
					SELECT 
						 f.fichaid
						,f.idEstado
						,CONVERT(VARCHAR(10),f.FechaCreacion,105) AS FechaCreacion
						,f.CodDivPersonal
						,f.CodCargo
						,f.RutTrabajador
						,f.NombreTrabajador as nombre_trabajador
						,f.ApPatTrabajador as appaterno_trabajador
						,f.ApMatTrabajador as apmaterno_trabajador
						,e.nombreestado As Estado
						,Em.RutEmpresa
						,Em.RazonSocial
						,cc.nombrecentrocosto
						,ROW_NUMBER()Over(Order by f.fichaid DESC) As RowNum
					FROM
						fichasDatosImportacion f
					INNER JOIN EstadosFichas e ON f.idEstado = e.estadoid
					INNER JOIN centroscosto cc ON f.CodDivPersonal = cc.centrocostoid
					INNER JOIN accesoxusuarioccosto ACC	ON ACC.empresaid = cc.empresaid AND ACC.centrocostoid = f.CodDivPersonal AND ACC.usuarioid = @pusuarioid 
					INNER JOIN Empresas Em ON cc.empresaid = Em.RutEmpresa
					WHERE 1 = 1 ' + @nl
				
	IF (@buscar != '')
	BEGIN
		SET @sqlString += ' AND (  f.RutTrabajador LIKE @buscarLike OR 
								   f.NombreTrabajador LIKE @buscarLike OR
								   f.ApPatTrabajador LIKE @buscarLike OR
								   f.ApMatTrabajador LIKE @buscarLike 
								 ) ' + @nl
	END	

	IF ( @idficha > 0 )
	BEGIN 
		SET @sqlString += ' AND f.fichaid = @idficha ' + @nl
	END 
	
	IF ( @idestado > 0 ) 
	BEGIN 
		SET @sqlString += ' AND f.idEstado = @idestado ' + @nl
	END	
	
	IF( @pempresaid != '' )
	BEGIN 
		SET @sqlString += ' AND ( Em.RutEmpresa LIKE @empresaLike OR Em.RazonSocial LIKE @empresaLike ) ' + @nl
	END

	IF( @pdivision != '' )
	BEGIN 
		SET @sqlString += ' AND ( cc.centrocostoid LIKE @divisionLike OR cc.nombrecentrocosto LIKE @divisionLike ) ' + @nl
	END
	
	SET @sqlString += N') 
					  SELECT 
							fichaid 
							,idEstado
							,FechaCreacion
							,CodDivPersonal
							,CodCargo
							,RutTrabajador
							,nombre_trabajador
							,appaterno_trabajador
							,apmaterno_trabajador					
							,Estado
							,RutEmpresa
							,RazonSocial
							,nombrecentrocosto
							,RowNum 
					  FROM DocumentosTabla
					  WHERE	RowNum BETWEEN @Pinicio AND @Pfin 
					 '                                    

	DECLARE @Parametros nvarchar(max)
	
	SET @Parametros =  N'@buscarLike VARCHAR(100),@buscar VARCHAR(50), @Pinicio INT, @Pfin INT, @idficha INT, @idestado INT, @pusuarioid VARCHAR(10), @empresaLike VARCHAR(100), @divisionLike VARCHAR(100)'
	
	IF (@debug = 1)
	BEGIN
		PRINT @sqlString
	END

	EXECUTE sp_executesql @sqlString, @Parametros, @buscarLike, @buscar, @Pinicio , @Pfin, @idficha, @idestado, @pusuarioid, @empresaLike, @divisionLike
               
	RETURN;
END
GO
