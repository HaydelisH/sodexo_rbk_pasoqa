USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_total_firmaunitaria_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
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
--		exec [sp_documentos_total] '', 1, 1, 10, 0, '' , '' , '' , '', '','',  1  -- TODOS
--		exec [sp_documentos_total] '', 2, 1, 10, 66, '', '' , '' , 1  -- X Contrato			                       
--		exec [sp_documentos_total] '', 2, 1, 10, 0, 'Contrato', '', '' , 1 -- X TipoDocumento
--		exec [sp_documentos_total] '', 2, 1, 10, 0, '', 'Gama ', '' , 1 -- X Empresa
--		exec [sp_documentos_total] '', 2, 1, 10, 0, '', '', 'Empori' , 1 -- X CLIENTE
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_total_firmaunitaria_20210920]

@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
@pagina					INT,	-- numero de pagina
@decuantos          DECIMAL,	-- total pagina
@pidDocumento			INT,	-- Id Documento
@pidtipodocumento		INT,	-- TipoDocumento
@pidEstadoContrato		INT,	-- Estado del Contrato
@pidTipoFirma			INT,	-- 1 = Manual y 2 = Elctronico
@pidProceso				INT,	-- Id del Proceso
@pFirmante		varchar(100),	-- Firmante del Contrato
@debug			tinyint	= 0		-- DEBUG 1= imprime consulta

AS
BEGIN
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	
	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @FirmanteLIKE	VARCHAR(100)
	
	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos										  
               
    SET @FirmanteLIKE = '%' + @pFirmante + '%'; 
	
	DECLARE @sqlString nvarchar(max)
	
    DECLARE @vdecimal DECIMAL (9,2)
    
	SET @sqlString = N'	
	With DocumentosTabla
	as 
	(
		SELECT	
			C.idDocumento
		FROM [Contratos] C
		INNER JOIN Documentos D				ON D.idDocumento = C.idDocumento
		INNER JOIN Plantillas PL			ON PL.idPlantilla = C.idPlantilla
		INNER JOIN TipoDocumentos TD	    ON PL.idTipoDoc = TD.idTipoDoc
		INNER JOIN Procesos P				ON P.idProceso = C.idProceso
		INNER JOIN ContratosEstados CE		ON CE.idEstado = C.idEstado
		INNER JOIN FirmasTipos FT			ON FT.idTipoFirma = C.idTipoFirma
		INNER JOIN ContratosEstados EW		ON EW.idEstado = C.idEstado	
		INNER JOIN Empresas E				ON E.RutEmpresa = C.RutEmpresa
		LEFT JOIN WorkflowEstadoProcesos WEP	ON C.idWF = idWorkflow AND C.idEstado =  WEP.idEstadoWF' + @nl
		
		IF (@pFirmante != '')
		BEGIN
				SET @sqlString += ' INNER JOIN ContratoFirmantes CF			ON CF.idDocumento = C.idDocumento AND CF.RutFirmante = @pFirmante AND CF.idEstado = C.idEstado' + @nl
		END
																			
		SET @sqlString += N' WHERE C.Eliminado = 0 ' + @nl
						
		IF (@pidDocumento != 0)
		BEGIN
			SET @sqlString += ' AND C.idDocumento = @pidDocumento ' + @nl
		END

		IF (@pidtipodocumento != 0)
		BEGIN
			SET @sqlString += ' AND PL.idTipoDoc = @pidtipodocumento' + @nl
		END	

		IF( @pidEstadoContrato > 0 )
		BEGIN
			SET @sqlString += ' AND C.idEstado = @pidEstadoContrato' + @nl
		END

		IF( @pidEstadoContrato < 0 )
		BEGIN
			SET @sqlString += ' AND C.idEstado IN (2,3,6,8,9,10,11)' + @nl
		END
				
		IF( @pidEstadoContrato = 0 )
		BEGIN
			SET @sqlString += ' AND C.idEstado IN (2,3,10,11)' + @nl
		END
		
		IF (@pidTipoFirma != '')
		BEGIN
			SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
		END
		
		IF (@pidProceso != 0)
		BEGIN
			SET @sqlString += ' AND P.idProceso = @pidProceso' + @nl
		END
	
		
		SET @sqlString += N') 
					  SELECT 
							 @totalorig = count(idDocumento)
					  FROM DocumentosTabla '                              
		
		DECLARE @Parametros nvarchar(max)
		
		SET @Parametros =  N'@ptipousuarioid INT, @Pinicio INT,
							 @Pfin INT, @PidDocumento INT, @pidtipodocumento INT,
							 @pidEstadoContrato INT, @pidTipoFirma INT,  @pidProceso INT,
							 @pFirmante VARCHAR(100), @FirmanteLIKE VARCHAR(100) , @totalorig INT OUTPUT'
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString, @Parametros, 
							  @ptipousuarioid , @Pinicio , @Pfin, @PidDocumento, @pidtipodocumento,
							  @pidEstadoContrato,@pidTipoFirma,@pidProceso, @pFirmante,@FirmanteLIKE, @totalorig = @totalorig OUTPUT
		
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
GO
