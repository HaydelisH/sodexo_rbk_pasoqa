USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_PersonasInfoContacto_total]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_PersonasInfoContacto_total]

	@ptipousuarioid	INT,			-- id del tipo de usuario o perfil
	@pagina         INT,			-- numero de pagina
	@decuantos      DECIMAL,		-- total pagina
	@ppersonaid     varchar(10),    -- Id Persona
	@pnombre        varchar(50),    -- Nombre Persona
	@penvioinfo		INT,			
	@pnombreContacto	varchar(110),
	@prelacionContacto	varchar(100),
	@debug          tinyint   = 0   -- DEBUG 1= imprime consulta
AS
BEGIN
                
    DECLARE @Pinicio	INT 
    DECLARE @Pfin       INT
    DECLARE @nl         char(2) = char(13) + char(10)
    DECLARE @pnombreLike	VARCHAR(50)
    DECLARE @pnombreContactoLike	VARCHAR(110)
    DECLARE @prelacionContactoLike	VARCHAR(100)
    DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	DECLARE @vdecimal DECIMAL (9,2)

    SET @Pinicio = (@pagina - 1) * @decuantos + 1 
    SET @Pfin = @pagina * @decuantos     
    SET @pnombreLike = '%' + @pnombre + '%'
    SET @pnombreContactoLike = '%' + @pnombreContacto + '%'
    SET @prelacionContactoLike  = '%' + @prelacionContacto + '%'                                                                                                                                
    
    DECLARE @sqlString nvarchar(max)
    
    SET @sqlString = N'        
    With DocumentosTabla
    as 
    (
		SELECT    
			  PIC.[personaid]
			 ,P.nombre
			 ,PIC.[direccion]
			 ,PIC.[comuna]
			 ,PIC.[ciudad]
			 ,PIC.[celularContacto]
			 ,PIC.[celularPersonal]
			 ,PIC.[envioinfo]
			 ,PIC.[nombreContacto]
			 ,PIC.[relacionContacto]
			 ,ROW_NUMBER()Over(Order by PIC.[personaid]) As RowNum
		FROM [personaInfoContacto] PIC
		INNER JOIN Personas P on P.personaid = PIC.personaid
		WHERE 1= 1
		' + @nl
        	
		IF (@ppersonaid != '')
			BEGIN
			   SET @sqlString += ' AND PIC.personaid = @ppersonaid ' + @nl
			END                                                                                                                        
   
		IF (@pnombre != '')
			BEGIN
			   SET @sqlString += ' AND P.nombre LIKE @pnombreLike ' + @nl
			END
		
		IF (@penvioinfo >= 0)
			BEGIN
			   SET @sqlString += ' AND PIC.envioinfo = @penvioinfo ' + @nl
			END
			
		IF (@pnombreContacto != '')
			BEGIN
			   SET @sqlString += ' AND PIC.nombreContacto LIKE @pnombreContactoLike  ' + @nl
			END
		
		IF (@prelacionContacto != '')
			BEGIN
			   SET @sqlString += ' AND PIC.relacionContacto LIKE @prelacionContactoLike ' + @nl
			END
		                                                                                                                                                                                                                                                              
    	
       SET @sqlString += N') 
         SELECT 
			      @totalorig = count(personaid)
		FROM DocumentosTabla'        
         
	   DECLARE @Parametros nvarchar(max)
 
	   SET @Parametros =  N'@Pinicio INT, @Pfin INT, @ppersonaid varchar(10), @pnombre varchar(50), @penvioinfo	INT,		
							@pnombreContacto varchar(110), @prelacionContacto varchar(100),@pnombreLike varchar(50),
							@pnombreContactoLike varchar(110), @prelacionContactoLike varchar(100), @totalorig INT OUTPUT'
   
	   IF (@debug = 1)
	   BEGIN
		  PRINT @sqlString
	   END

	   EXECUTE sp_executesql @sqlString, @Parametros, @Pinicio, @Pfin, @ppersonaid, @pnombre, @penvioinfo,
							@pnombreContacto,  @prelacionContacto, @pnombreLike, @pnombreContactoLike, 
							@prelacionContactoLike, @totalorig = @totalorig OUTPUT
       
		SELECT @totalreg = (@totalorig/@decuantos)
		
		SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)

		IF @vdecimal > 0 
			SELECT @total = @totalreg + 1
		ELSE
			SELECT @total = @totalreg

		SET @totalreg = @totalreg * @decuantos

		SELECT  @total as total, @totalreg as totalreg	        
   RETURN                                                             

END


/****** Object:  StoredProcedure [dbo].[sp_PersonasInfoContacto_obtener]    Script Date: 06/30/2020 14:51:25 ******/
SET ANSI_NULLS ON
GO
