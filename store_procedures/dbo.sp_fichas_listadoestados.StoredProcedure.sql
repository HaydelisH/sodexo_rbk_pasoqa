USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_listadoestados]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 02/10/2018
-- Descripcion:	Lista los estados de las fichas disponibles
-- Ejemplo:exec sp_fichas_listadoestados 
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_listadoestados]
	@idficha			INT,
	@idestado			INT,
	@buscar             VARCHAR(50),	-- Palabra a buscar( nombre o rut de persona)
	@pagina             INT,            -- numero de pagina
	@decuantos          INT,            -- total pagina
	@codigo             INT,            --[0]= Todos,[1]= Pendientes [2]=Pendientes y Con contratos generado [3]=Confirmados
	@ptipousuarioid		INT,
	@pusuarioid			varchar(50),	-- id usuario
	@debug				tinyint	= 0		-- debug 1 = imprime la consulta
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @buscarLike VARCHAR(100)
	DECLARE @Pinicio int 
	DECLARE @Pfin int
	DECLARE @nl   char(2) = char(13) + char(10)
	DECLARE @empresaid VARCHAR(10)
	DECLARE @centrocostoid NVARCHAR(14);
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos
	
	--Buscar los datos necesarios 
	SELECT @centrocostoid = CodDivPersonal FROM fichasDatosImportacion WHERE fichaid = @idficha
	SELECT @empresaid = empresaid FROM centroscosto WHERE centrocostoid = @centrocostoid	

	IF ( @buscar != '' )
		BEGIN 
			SET @buscarLike = '%' + @buscar + '%'
		END
		
	DECLARE @sqlString nvarchar(max)
	SET @sqlString = N'	
	With DocumentosTabla
			as 
			(
				SELECT 
					ef.estadoid as idEstado,
					ef.nombreestado as Descripcion
				FROM fichasDatosImportacion f
					INNER JOIN personas p ON f.empleadoid = p.personaid
					INNER JOIN accesoxusuarioccosto     ACC	ON ACC.empresaid = @empresaid AND ACC.centrocostoid = f.CodDivPersonal AND ACC.usuarioid = @pusuarioid 
					INNER JOIN EstadosFichas ef ON f.estadoid = ef.estadoid
				WHERE 1 = 1 ' + @nl
		
					IF (@codigo = 1 )
					BEGIN
						SET @sqlString += ' AND f.estadoid = 1' + @nl
					END
					
					IF (@codigo = 2 )
					BEGIN
						SET @sqlString += 'AND f.estadoid IN ( 1,2 ) ' + @nl
					END	
					
					IF (@codigo = 3 )
					BEGIN
						SET @sqlString += ' AND f.estadoid = 3 ' + @nl
					END	
					
					SET @sqlString += N' GROUP BY ef.estadoid, ef.nombreestado ' + @nl
										
					SET @sqlString += N') 
					  SELECT 
							idEstado,
							Descripcion
					  FROM DocumentosTabla'                                    

			DECLARE @Parametros nvarchar(max)
			
			SET @Parametros =  N'@buscarLike VARCHAR(100), @Pinicio INT, @Pfin INT, @ptipousuarioid INT, @pusuarioid VARCHAR(50)'
			
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END
	
			EXECUTE sp_executesql @sqlString, @Parametros, @buscarLike, @Pinicio , @Pfin, @ptipousuarioid, @pusuarioid
               
	RETURN;
END
GO
