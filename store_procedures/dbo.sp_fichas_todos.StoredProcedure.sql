USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_todos]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 02/10/2018
-- Descripcion:	Lista todas las fichas 
-- Ejemplo:exec sp_fichas_todos 
-- =============================================

CREATE PROCEDURE [dbo].[sp_fichas_todos]
	@idficha			INT,
	@idestado			INT,
	@buscar             VARCHAR(50),	-- Palabra a buscar( nombre o rut de persona)
	@pagina             INT,            -- numero de pagina
	@decuantos          INT,            -- total pagina
	@codigo             INT,            --[0]= Todos,[1]= Pendientes [2]=Pendientes y Con contratos generado [3]=Confirmados
	@ptipousuarioid		INT,
	@pusuarioid			VARCHAR(10),
	@debug				tinyint	= 0		-- debug 1 = imprime la consulta
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @buscarLike VARCHAR(100)
	DECLARE @Pinicio int 
	DECLARE @Pfin int
	DECLARE @nl   char(2) = char(13) + char(10);
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos	

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
					f.fichaid,
					f.empleadoid,
					p.nombre,
					f.empresaid,
					f.centrocostoid,
					f.estadoid,
					ef.nombreestado,
				    CONVERT(VARCHAR(10),f.fechasolicitud,105)AS fechasolicitud, 
					ROW_NUMBER()Over(Order by f.empleadoid) As RowNum
				FROM fichas f
					INNER JOIN personas p ON f.empleadoid = p.personaid
					INNER JOIN accesoxusuarioccosto ACC ON ACC.empresaid = f.empresaid AND ACC.usuarioid = @pusuarioid AND ACC.centrocostoid = f.centrocostoid
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
					
					IF (@buscar != '')
					BEGIN
						SET @sqlString += ' AND (p.personaid LIKE @buscarlike OR p.nombre LIKE @buscarLike )' + @nl
					END	
					
					IF ( @idficha > 0 )
					BEGIN 
						SET @sqlString += ' AND f.fichaid= @idficha ' + @nl
					END 
					
					IF ( @idestado > 0 ) 
					BEGIN 
						SET @sqlString += ' AND f.estadoid = @idestado ' + @nl
					END	
					
					
										
					SET @sqlString += N') 
					  SELECT 
							fichaid 
							,empleadoid
							,nombre
							,empresaid
							,centrocostoid
							,estadoid
							,nombreestado
							,fechasolicitud
							,RowNum 
					  FROM DocumentosTabla
					  WHERE	RowNum BETWEEN @Pinicio AND @Pfin '                                    

			DECLARE @Parametros nvarchar(max)
			
			SET @Parametros =  N'@buscarLike VARCHAR(100), @Pinicio INT, @Pfin INT, @ptipousuarioid INT, @idficha INT, @idestado INT, @pusuarioid VARCHAR(10)'
			
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END
	
			EXECUTE sp_executesql @sqlString, @Parametros, @buscarLike, @Pinicio , @Pfin, @ptipousuarioid, @idficha, @idestado, @pusuarioid
               
	RETURN;
END
GO
