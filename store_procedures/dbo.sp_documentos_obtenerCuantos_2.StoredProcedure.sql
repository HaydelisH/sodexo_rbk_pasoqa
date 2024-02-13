USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerCuantos_2]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 28/09/2018
-- Descripcion: Obtiene cuantos contratos hay un mes
-- Ejemplo:exec [sp_documentos_obtenerCuantos] 8
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerCuantos_2]
	@mes			INT,
	@anno			INT,
	@pEjecutivo		VARCHAR(10),
	@pEmpresa		VARCHAR(10),
	@ptipousuarioid INT,                        -- id del tipo de usuario o perfil
	@debug		    tinyint	= 0					-- DEBUG 1= imprime consulta 
AS
BEGIN	
	DECLARE @nl   char(2) = char(13) + char(10)
	DECLARE @sqlString nvarchar(max)
	SET @sqlString = N'	

	With DocumentosTabla
	as 
	(
		SELECT 
			CASE C.idEstado
				WHEN 1 THEN ''Pendiente''
				WHEN 2 THEN ''Proceso''
				WHEN 3 THEN ''Proceso''
				WHEN 4 THEN ''Proceso''
				WHEN 5 THEN ''Proceso''
				WHEN 8 THEN ''Rechazados''
				WHEN 6 THEN ''Firmados''
			END AS Estado,' + @nl
			
			IF (@pEjecutivo != '0')
			BEGIN
				SET @sqlString += ' Eje.RutEjecutivo,' + @nl
			END
			
			IF (@pEmpresa != '0')
			BEGIN
				SET @sqlString += ' DV.RutEmpresa,' + @nl
			END
			
			SET @sqlString += 'COUNT(C.idContrato) As Total
		FROM 
			Contratos C
			INNER JOIN DocumentosVariables DV ON DV.idDocumento = C.idContrato
			INNER JOIN Empresas E             ON E.RutEmpresa = DV.RutEmpresa
			LEFT JOIN Ejecutivos Eje         ON Eje.RutCliente = DV.RutCliente
			INNER JOIN accesodocxperfillugarespago AccLP ON DV.RutCliente = AccLP.lugarpagoid 
														AND DV.RutEmpresa = AccLP.empresaid
														AND AccLP.tipousuarioid = @ptipousuarioid
		WHERE E.Eliminado = 0 AND MONTH(C.FechaCreacion) = @mes AND YEAR(C.FechaCreacion) = @anno ' + @nl
		
		IF (@pEjecutivo != '0')
		BEGIN
			SET @sqlString += ' AND Eje.RutEjecutivo = @pEjecutivo ' + @nl
		END
		
		IF (@pEmpresa != '0')
		BEGIN
			SET @sqlString += ' AND DV.RutEmpresa = @pEmpresa ' + @nl
		END
		
		SET @sqlString += ' GROUP BY C.idEstado' + @nl;
		
		IF (@pEjecutivo != '0')
		BEGIN
			SET @sqlString += ' ,Eje.RutEjecutivo' + @nl
		END
		
		IF (@pEmpresa != '0')
		BEGIN
			SET @sqlString += ' ,DV.RutEmpresa' + @nl
		END
		
		SET @sqlString += N') 
					  SELECT 
							Estado, ' + @nl
							
						IF (@pEjecutivo != '0')
						BEGIN
							SET @sqlString += ' RutEjecutivo,' + @nl
						END
						
						IF (@pEmpresa != '0')
						BEGIN
							SET @sqlString += ' RutEmpresa,' + @nl
						END
			
						SET @sqlString += '	Total
					  FROM DocumentosTabla'
					                                
			DECLARE @Parametros nvarchar(400)
			
			SET @Parametros =  N'@mes INT, @anno INT, 
								 @pEjecutivo VARCHAR(10), @pEmpresa VARCHAR(10), @ptipousuarioid INT'
			
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END
				
			EXECUTE sp_executesql @sqlString, @Parametros, 
								  @mes , @anno , @pEjecutivo , @pEmpresa, @ptipousuarioid
		RETURN	
END
GO
