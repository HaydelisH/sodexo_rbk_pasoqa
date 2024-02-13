USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_PersonasInfoContacto_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- [sp_PersonasInfoContacto_listado] 1,1,10,'','',1,'','',1
-- sp_PersonasInfoContacto_listado 1,1,10,'','',-1,'','',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_PersonasInfoContacto_listado]

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
			 ,CASE envioinfo WHEN 1 THEN ' + '''Si''' + ' ELSE ' + '''No'''  + 'END ei
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
			      personaid
				 ,nombre
				 ,direccion
				 ,comuna
				 ,ciudad
				 ,celularContacto
				 ,celularPersonal
				 ,envioinfo
				 ,ei
				 ,nombreContacto
				 ,relacionContacto
		FROM DocumentosTabla
		WHERE               
		RowNum BETWEEN @Pinicio AND @Pfin '        
         
	   DECLARE @Parametros nvarchar(max)
 
	   SET @Parametros =  N'@Pinicio INT, @Pfin INT, @ppersonaid varchar(10), @pnombre varchar(50), @penvioinfo	INT,		
							@pnombreContacto varchar(110), @prelacionContacto varchar(100),@pnombreLike varchar(50),
							@pnombreContactoLike varchar(110), @prelacionContactoLike varchar(100)'
   
	   IF (@debug = 1)
	   BEGIN
		  PRINT @sqlString
	   END

	   EXECUTE sp_executesql @sqlString, @Parametros, @Pinicio, @Pfin, @ppersonaid, @pnombre, @penvioinfo,
							@pnombreContacto,  @prelacionContacto, @pnombreLike, @pnombreContactoLike, 
							@prelacionContactoLike
                
   RETURN                                                             

END


/****** Object:  StoredProcedure [dbo].[sp_personas_obtenerTipoFirma]    Script Date: 06/30/2020 14:51:21 ******/
SET ANSI_NULLS ON
GO
