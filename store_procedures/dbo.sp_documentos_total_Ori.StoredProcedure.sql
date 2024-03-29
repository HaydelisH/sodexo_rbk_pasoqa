USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_total_Ori]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_documentos_reportes] '', 2, 1, 10  
-- STRING ''
-- FECHA NULL	  
-- INT 0
--		exec [sp_documentos_total] '', 1, 1, 10, 0, '' , '' , '' , '',1  -- TODOS
--		exec [sp_documentos_total] '', 2, 1, 10, 66, '', '' , '' , 1  -- X Contrato			                       
--		exec [sp_documentos_total] '', 2, 1, 10, 0, 'Contrato', '', '' , 1 -- X TipoDocumento
--		exec [sp_documentos_total] '', 2, 1, 10, 0, '', 'Gama ', '' , 1 -- X Empresa
--		exec [sp_documentos_total] '', 2, 1, 10, 0, '', '', 'Empori' , 1 -- X CLIENTE
-- =============================================
Create PROCEDURE [dbo].[sp_documentos_total_Ori]
@pidProyecto		NVARCHAR(50),           -- nombre del tipo de usuario
@ptipousuarioid     INT,                        -- id del tipo de usuario o perfil
@pagina             INT,                        -- numero de pagina
@decuantos          DECIMAL,                    -- total pagina
@pidContrato		INT,						-- Id Contrato
@ptipodocumento		VARCHAR(50),				-- TipoDocumento
@pRazonSocialGama   VARCHAR(50),				-- RazonSocial Gama
@pRazonSocialCliente   VARCHAR(50),				-- RazonSocial Cliente
@pidusuario			varchar(10),
@debug				tinyint	= 0					-- DEBUG 1= imprime consulta
AS
BEGIN
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	
	DECLARE @idProyectolike NVARCHAR(50),@pRazonSocialGamaLike NVARCHAR(50),
			@pRazonSocialClienteLike NVARCHAR(50)

	DECLARE @nl   char(2) = char(13) + char(10)									  
               
    SET @idProyectolike = '%' + @pidProyecto + '%'; 
	SET @pRazonSocialGamaLike = '%' + @pRazonSocialGama + '%'; 
	SET @pRazonSocialClienteLike = '%' + @pRazonSocialCliente + '%'; 
	DECLARE @sqlString nvarchar(4000)
	
    DECLARE @vdecimal DECIMAL (9,2)
	SET @sqlString = N'	
With DocumentosTabla
as 
(
SELECT	C.idContrato		    
		--,P.idProyecto
		--,PL.Descripcion_Pl
		--,TD.NombreTipoDoc
		--,EM.RazonSocial		AS RazonSocialGama
		--,EmCli.RazonSocial  AS RazonSocialCliente			
		--,EW.Nombre
		--,1 as Semaforo
		--,ROW_NUMBER()Over(Order by C.idContrato) As RowNum
  FROM [Contratos] C
		INNER JOIN Proyectos P			ON C.idProyecto = P.idProyecto
		INNER JOIN Plantillas PL		ON C.idPlantilla = PL.idPlantilla
		INNER JOIN Empresas EM			ON PL.RutEmpresa = EM.RutEmpresa
		INNER JOIN TipoDocumentos TD	ON PL.idTipoDoc = TD.idTipoDoc
		INNER JOIN EstadosWorkflow EW	ON C.idEstado = EW.idEstado		
		INNER JOIN ProyectosCliente PC ON C.idProyecto = PC.idProyecto
		INNER JOIN Empresas EmCli			ON PC.RutEmpresa = EmCli.RutEmpresa			
		INNER JOIN accesodocxperfillugarespago AccLP ON		EmCli.RutEmpresa = AccLP.lugarpagoid 
														AND EM.RutEmpresa = AccLP.empresaid
														AND AccLP.tipousuarioid = @ptipousuarioid
		INNER JOIN tiposdocumentosxperfil TDP ON TD.idTipoDoc = TDP.idtipodoc 
												AND TDP.tipousuarioid = @ptipousuarioid'  + @nl
		
		IF (@pidusuario != '')
		BEGIN
			SET @sqlString += ' INNER JOIN ContratoFirmantes CF ON C.idContrato = CF.idContrato AND CF.RutFirmante = @pidusuario' + @nl
		END
																																					
	SET @sqlString += N' WHERE 1 = 1 ' + @nl
						
		IF (@pidContrato != 0)
		BEGIN
			SET @sqlString += ' AND C.idContrato = @PidContrato ' + @nl
		END

		IF (@pidProyecto != '')
		BEGIN
			SET @sqlString += ' AND P.idProyecto LIKE @idProyectolike' + @nl
		END
					  
		IF (@ptipodocumento != '')
		BEGIN
			SET @sqlString += ' AND TD.NombreTipoDoc = @ptipodocumento' + @nl
		END					  

		IF (@pRazonSocialGama != '')
		BEGIN
			SET @sqlString += ' AND EM.RazonSocial LIKE @pRazonSocialGamaLike' + @nl
		END				
				  
		IF (@pRazonSocialCliente != '')
		BEGIN
			SET @sqlString += ' AND EmCli.RazonSocial LIKE @pRazonSocialClienteLike' + @nl
		END				
	

		SET @sqlString += N') 
					  SELECT 
							 @totalorig = count(idContrato)
							--,idProyecto
							--,Descripcion_Pl
							--,NombreTipoDoc
							--,RazonSocialCliente
							--,RazonSocialGama
							--,Nombre
							--,Semaforo
							--,RowNum 
					  FROM DocumentosTabla
					  '                              

			--SET @sqlString += ' AND   idContrato = 66'

			DECLARE @Parametros nvarchar(300)
			
			SET @Parametros =  N'@ptipousuarioid INT, @idProyectolike NVARCHAR(50), 
								 @PidContrato INT, @ptipodocumento VARCHAR(50),
								 @pRazonSocialGamaLike Varchar(50), @pRazonSocialClienteLike NVARCHAR(50), @pidusuario Varchar(10), @totalorig INT OUTPUT'
			
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END
	
			
			EXECUTE sp_executesql @sqlString, @Parametros, 
									@ptipousuarioid , @idProyectolike ,  @PidContrato, @ptipodocumento, @pRazonSocialGamaLike,
									 @pRazonSocialClienteLike, @pidusuario,  @totalorig = @totalorig OUTPUT
									

		
			SELECT @totalreg = (@totalorig/@decuantos)
			
			SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
                
			 IF @vdecimal > 0 
				SELECT @total = @totalreg + 1
			 ELSE
				SELECT @total = @totalreg
				
			select @total as total
	                                                                              
	 RETURN                   
END
GO
