USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosmanuales_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
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
--		exec [sp_documentos_listado] '', 1, 1, 10, 0, '1' , '' , '' , '', '', '', 1  -- TODOS
--		exec [sp_documentos_listado] '', 1, 1, 10, 0, '2' , '' , '' , '', '', '', 1  -- TODOS
--		exec [sp_documentos_listado] '', 2, 1, 10, 66, '', '' , '' , 1  -- X Contrato			                       
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, 'Contrato', '', '' , 1 -- X TipoDocumento
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, '', 'Gama ', '' , 1 -- X Empresa
--		exec [sp_documentos_listado] '', 2, 1, 10, 0, '', '', 'Empori' , 1 -- X CLIENTE
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosmanuales_listado]
@pidProyecto			NVARCHAR(50),           -- nombre del tipo de usuario
@ptipousuarioid     INT,                        -- id del tipo de usuario o perfil
@pagina             INT,                        -- numero de pagina
@decuantos          DECIMAL,                    -- total pagina
@pidContrato		INT,						-- Id Contrato
@pidtipodocumento		INT,				-- TipoDocumento
@pRutRazonSocialGama   VARCHAR(10),				-- RazonSocial Gama
@pRazonSocialCliente   VARCHAR(50),				-- RazonSocial Cliente
@pidusuario			varchar(10),
@pidEstadoContrato	INT,
@pPlantilla			Varchar(50),
@debug				tinyint	= 0				-- DEBUG 1= imprime consulta

AS
BEGIN
	SET @pidusuario = '' --***************************************************
	DECLARE @idProyectolike NVARCHAR(50),
			@pRazonSocialClienteLike NVARCHAR(50)			
	
	DECLARE @PlantillaLike NVARCHAR(50);
			
	DECLARE @Pinicio int 
	DECLARE @Pfin int
	DECLARE @nl   char(2) = char(13) + char(10)
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
    SET @idProyectolike = '%' + @pidProyecto + '%'; 
	SET @pRazonSocialClienteLike = '%' + @pRazonSocialCliente + '%'; 
	SET @PlantillaLike = '%' + @pPlantilla + '%' 
	
	DECLARE @sqlString nvarchar(max)
	SET @sqlString = N'	
With DocumentosTabla
as 
(
SELECT	C.idContrato
		,P.idProyecto
		,PL.Descripcion_Pl
		,TD.idTipoDoc		AS idTipoDoc
		,TD.NombreTipoDoc	
		,EM.RutEmpresa		AS RutEmpresaGama		
		,EM.RazonSocial		AS RazonSocialGama		
		,EmCli.RazonSocial  AS RazonSocialCliente			
		,EC.idEstado		AS idEstadoDocumento
		,EC.Descripcion		AS Nombre
		,1 as Semaforo
		,ROW_NUMBER()Over(Order by C.idContrato) As RowNum
		,CONVERT(CHAR(10), C.FechaCreacion,105)	AS FechaCreacion
		,CONVERT(CHAR(10),C.FechaUltimaFirma,105) AS FechaUltimaFirma
		,WEP.DiasMax
		,C.idWF
  FROM [Contratos] C
		LEFT JOIN Proyectos P			ON C.idProyecto = P.idProyecto
		INNER JOIN Plantillas PL		ON C.idPlantilla = PL.idPlantilla
		INNER JOIN Empresas EM			ON PL.RutEmpresa = EM.RutEmpresa
		INNER JOIN TipoDocumentos TD	ON PL.idTipoDoc = TD.idTipoDoc
		INNER JOIN EstadoContratos EC	ON C.idEstado = EC.idEstado		
		LEFT JOIN WorkflowEstadoProcesos WEP ON C.idWF = idWorkflow AND C.idEstado =  WEP.idEstadoWF
		INNER JOIN DocumentosVariables  DV ON C.idContrato = DV.idDocumento
		INNER JOIN Empresas EmCli			ON DV.RutCliente = EmCli.RutEmpresa
		INNER JOIN accesodocxperfillugarespago AccLP ON		EmCli.RutEmpresa = AccLP.lugarpagoid 
														AND EM.RutEmpresa = AccLP.empresaid
														AND AccLP.tipousuarioid = @ptipousuarioid
		INNER JOIN tiposdocumentosxperfil TDP ON TD.idTipoDoc = TDP.idtipodoc 
												AND TDP.tipousuarioid = @ptipousuarioid'  + @nl
		
		IF (@pidusuario != '')
		BEGIN
			SET @sqlString += ' INNER JOIN ContratoFirmantes CF ON C.idContrato = CF.idContrato AND CF.RutFirmante = @pidusuario' + @nl
		END
																																					
	SET @sqlString += N' WHERE 1 = 1 AND idTipoFirma = 1' + @nl
						
		IF (@pidContrato != 0)
		BEGIN
			SET @sqlString += ' AND C.idContrato = @PidContrato ' + @nl
		END

		IF (@pidtipodocumento != 0)
		BEGIN
			SET @sqlString += ' AND TD.idTipoDoc = @pidtipodocumento' + @nl
		END	

		IF (@pidProyecto != '')
		BEGIN
			SET @sqlString += ' AND P.idProyecto LIKE @idProyectolike' + @nl
		END

		IF (@pRutRazonSocialGama != '')
		BEGIN
			SET @sqlString += ' AND EM.RutEmpresa = @pRutRazonSocialGama' + @nl
		END				
				  
		IF (@pRazonSocialCliente != '')
		BEGIN
			SET @sqlString += ' AND EmCli.RazonSocial LIKE @pRazonSocialClienteLike' + @nl
		END				

		IF (@pidEstadoContrato != 0)
		BEGIN
			SET @sqlString += ' AND EC.idEstado = @pidEstadoContrato' + @nl
		END

		IF (@pPlantilla != '')
		BEGIN
			SET @sqlString += ' AND PL.Descripcion_Pl LIKE @PlantillaLike' + @nl
		END

	

		SET @sqlString += N') 
					  SELECT 
							 idContrato
							,idProyecto
							,idTipoDoc
							,Descripcion_Pl
							,NombreTipoDoc
							,RutEmpresaGama
							,RazonSocialCliente
							,RazonSocialGama
							,idEstadoDocumento
							,Nombre
							,Semaforo
							,RowNum 
							,FechaCreacion
							,FechaUltimaFirma
							,DiasMax as DiasEstadoActual
							,idWF
					  FROM DocumentosTabla
					  WHERE	RowNum BETWEEN @Pinicio AND @Pfin'                              


			DECLARE @Parametros nvarchar(400)
			
			SET @Parametros =  N'@ptipousuarioid INT, @idProyectolike NVARCHAR(50), @Pinicio INT,
								 @Pfin INT, @PidContrato INT, @pidtipodocumento INT,
								 @pRutRazonSocialGama Varchar(10), @pRazonSocialClienteLike NVARCHAR(50),
								 @pidusuario Varchar(10),@pidEstadoContrato INT,@PlantillaLike NVARCHAR(50)'
			
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END
	
			
			EXECUTE sp_executesql @sqlString, @Parametros, 
									@ptipousuarioid , @idProyectolike ,  @Pinicio , @Pfin, @PidContrato, @pidtipodocumento,
									 @pRutRazonSocialGama, @pRazonSocialClienteLike, @pidusuario,@pidEstadoContrato,@PlantillaLike
							
        					

			                       
                  
                                 	
    RETURN                                                             
END
GO
