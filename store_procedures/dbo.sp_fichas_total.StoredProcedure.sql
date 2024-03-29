USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_total]    Script Date: 1/22/2024 7:21:14 PM ******/
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
----sp_fichas_total 2,0,'',1,10,0,1,1
CREATE PROCEDURE [dbo].[sp_fichas_total]
	@idficha			INT,
	@idestado			INT,
	@buscar             VARCHAR(50),	--Palabra a buscar( nombre o rut de persona)
	@pagina             INT,            -- numero de pagina
	@decuantos          INT,			-- total pagina
	@codigo             INT,            -- [0]= Todos, [1]= Pendientes [2]= Pendientes y Con contratos generado [3]=Confirmados
	@ptipousuarioid		INT,
	@pusuarioid			VARCHAR(10),
	@debug				tinyint	= 0		--debug 1 = imprime la consulta
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @totalorig INT
	DECLARE @error			INT
	DECLARE @mensaje		VARCHAR(100)
	DECLARE @totalreg		DECIMAL (9,2)
	DECLARE @vdecimal		DECIMAL (9,2)
	DECLARE @total			INT
	DECLARE @Pinicio int 
	DECLARE @Pfin int
	DECLARE @buscarLike VARCHAR(100);
	DECLARE @nl   char(2) = char(13) + char(10)
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
					f.fichaid
				FROM fichas f
					INNER JOIN personas p ON f.empleadoid = p.personaid
					--INNER JOIN accesodocxperfilccosto ACC ON ACC.empresaid = f.empresaid AND ACC.lugarpagoid = f.lugarpagoid AND ACC.tipousuarioid = @ptipousuarioid AND ACC.centrocostoid = f.centrocostoid
					INNER JOIN accesoxusuarioccosto ACC ON ACC.empresaid = f.empresaid AND ACC.usuarioid = @pusuarioid AND ACC.centrocostoid = f.centrocostoid
					INNER JOIN EstadosFichas ef ON f.estadoid = ef.estadoid
				WHERE 1 = 1 ' + @nl
					
					IF (@codigo = 1 )
					BEGIN
						SET @sqlString += ' AND f.estadoid = 1' + @nl
					END
					
					IF (@codigo = 2 )
					BEGIN
						SET @sqlString += ' AND f.estadoid IN ( 1,2 ) ' + @nl
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
						SET @sqlString += ' AND f.fichaid = @idficha ' + @nl
					END 
					
					IF ( @idestado > 0 ) 
					BEGIN 
						SET @sqlString += ' AND f.estadoid = @idestado ' + @nl
					END	
					
					SET @sqlString += N') 
					  SELECT 
							@totalorig = count(fichaid)
					  FROM DocumentosTabla  '                                    

			DECLARE @Parametros nvarchar(max)
			
			SET @Parametros =  N'@buscarLike VARCHAR(100), @Pinicio INT, @Pfin INT, @ptipousuarioid INT,@idficha INT, @idestado INT, @pusuarioid VARCHAR(10), @totalorig INT OUTPUT'
			
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END
	
			EXECUTE sp_executesql @sqlString, @Parametros, @buscarLike, @Pinicio, @Pfin, @ptipousuarioid , @idficha, @idestado, @pusuarioid,@totalorig = @totalorig OUTPUT
								
			SELECT @totalreg = (@totalorig/@decuantos)

			IF ( @totalreg < 0 OR @total = 1)
				BEGIN 
					SET @total = 1
				END
			ELSE
				BEGIN
					SET @total = convert(int, @totalreg) + 1
				END
		
	 
		SELECT  @total as total, @totalorig as totalreg	
		
	RETURN;
END
GO
