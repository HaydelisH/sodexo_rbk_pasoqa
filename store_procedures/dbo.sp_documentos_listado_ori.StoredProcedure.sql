USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_listado_ori]    Script Date: 1/22/2024 7:21:13 PM ******/
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
--		exec [sp_documentos_listado] '', 1, 1, 10, 0, '' , '' , '' , '13559051-7', 'gen', 'a', 1  -- TODOS
--		exec [sp_documentos_listado] '', 2, 1, 10, 66, '', '' , '' , 1  -- X Contrato			                       
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, 'Contrato', '', '' , 1 -- X TipoDocumento
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, '', 'Gama ', '' , 1 -- X Empresa
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, '', '', 'Empori' , 1 -- X CLIENTE
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_listado_ori]
@pidProyecto			NVARCHAR(50),           -- nombre del tipo de usuario
@ptipousuarioid     INT,                        -- id del tipo de usuario o perfil
@pagina             INT,                        -- numero de pagina
@decuantos          DECIMAL,                    -- total pagina
@pidContrato		INT,						-- Id Contrato
@ptipodocumento		VARCHAR(50),				-- TipoDocumento
@pRazonSocialGama   VARCHAR(50),				-- RazonSocial Gama
@pRazonSocialCliente   VARCHAR(50),				-- RazonSocial Cliente
@pidusuario			varchar(10),
--@pEstadoContrato	Varchar(50),
--@pPlantilla			VARCHAR(50),
@debug				tinyint	= 0					-- DEBUG 1= imprime consulta
AS
BEGIN
	
	DECLARE @idProyectolike NVARCHAR(50),
			@pRazonSocialGamaLike NVARCHAR(50),
			@pRazonSocialClienteLike NVARCHAR(50)
			--,@EstadoContratoLike NVARCHAR(50),
			--@PlantillaLike NVARCHAR(50)
			
	DECLARE @Pinicio int 
	DECLARE @Pfin int
	DECLARE @nl   char(2) = char(13) + char(10)
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
    SET @idProyectolike = '%' + @pidProyecto + '%'; 
	SET @pRazonSocialGamaLike = '%' + @pRazonSocialGama + '%'; 
	SET @pRazonSocialClienteLike = '%' + @pRazonSocialCliente + '%'; 
	--SET @EstadoContratoLike = '%' + @pEstadoContrato + '%'; 
	--SET @PlantillaLike = '%' + @pPlantilla + '%'; 
	
	DECLARE @sqlString nvarchar(4000)
	SET @sqlString = N'	
With DocumentosTabla
as 
(
SELECT	C.idContrato
		,P.idProyecto
		,PL.Descripcion_Pl
		,TD.NombreTipoDoc
		,EM.RazonSocial		AS RazonSocialGama
		,EmCli.RazonSocial  AS RazonSocialCliente			
		,EC.Descripcion		AS Nombre
		,1 as Semaforo
		,ROW_NUMBER()Over(Order by C.idContrato) As RowNum
  FROM [Contratos] C
		INNER JOIN Proyectos P			ON C.idProyecto = P.idProyecto
		INNER JOIN Plantillas PL		ON C.idPlantilla = PL.idPlantilla
		INNER JOIN Empresas EM			ON PL.RutEmpresa = EM.RutEmpresa
		INNER JOIN TipoDocumentos TD	ON PL.idTipoDoc = TD.idTipoDoc
		INNER JOIN EstadoContratos EC	ON C.idEstado = EC.idEstado		
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

		IF (@ptipodocumento != '')
		BEGIN
			SET @sqlString += ' AND TD.NombreTipoDoc = @ptipodocumento' + @nl
		END	

		IF (@pidProyecto != '')
		BEGIN
			SET @sqlString += ' AND P.idProyecto LIKE @idProyectolike' + @nl
		END

		IF (@pRazonSocialGama != '')
		BEGIN
			SET @sqlString += ' AND EM.RazonSocial LIKE @pRazonSocialGamaLike' + @nl
		END				
				  
		IF (@pRazonSocialCliente != '')
		BEGIN
			SET @sqlString += ' AND EmCli.RazonSocial LIKE @pRazonSocialClienteLike' + @nl
		END				

		--IF (@pEstadoContrato != '')
		--BEGIN
		--	SET @sqlString += ' AND EC.Descripcion LIKE @EstadoContratoLike' + @nl
		--END

		--IF (@pPlantilla	 != '')
		--BEGIN
		--	SET @sqlString += ' AND PL.Descripcion_Pl LIKE @PlantillaLike' + @nl
		--END

	

		SET @sqlString += N') 
					  SELECT 
							 idContrato
							,idProyecto
							,Descripcion_Pl
							,NombreTipoDoc
							,RazonSocialCliente
							,RazonSocialGama
							,Nombre
							,Semaforo
							,RowNum 
					  FROM DocumentosTabla
					  WHERE	RowNum BETWEEN @Pinicio AND @Pfin'                              

			--SET @sqlString += ' AND   idContrato = 66'

			DECLARE @Parametros nvarchar(300)
			
			SET @Parametros =  N'@ptipousuarioid INT, @idProyectolike NVARCHAR(50), @Pinicio INT,
								 @Pfin INT, @PidContrato INT, @ptipodocumento VARCHAR(50),
								 @pRazonSocialGamaLike Varchar(50), @pRazonSocialClienteLike NVARCHAR(50),
								 @pidusuario Varchar(10)'
								 --,@EstadoContratoLike Varchar(50), @PlantillaLike VARCHAR(50)
								 
			
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END
	
			
			EXECUTE sp_executesql @sqlString, @Parametros, 
									@ptipousuarioid , @idProyectolike ,  @Pinicio , @Pfin, @PidContrato, @ptipodocumento,
									 @pRazonSocialGamaLike, @pRazonSocialClienteLike, @pidusuario
									 --,@EstadoContratoLike, @PlantillaLike
							
        					

			                       
                  
                                 	
    RETURN                                                             

END
GO
