USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_panel]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 26/06/2017
-- Descripcion:   Consultas para mostrar en panel
-- Ejemplo:exec sp_panel '13559051-7'
-- =============================================
CREATE PROCEDURE [dbo].[sp_panel]
 @usuarioid VARCHAR (10)      
AS    
BEGIN
		 DECLARE @ptipousuarioid INT, @totaldoc INT
		--DECLARE @usuarioid VARCHAR(10)

		--SET @usuarioid = '13559051-7'
		--SELECT @ptipousuarioid = tipousuarioid FROM usuarios WHERE [usuarioid] = @usuarioid
		;

		With DocumentosTabla as 
		(
            SELECT
                CF.RutFirmante
                ,CASE CE.idEstado
                    WHEN 2 THEN 'DocumentosEnProceso'
                    WHEN 3 THEN 'DocumentosEnProceso'
                    WHEN 9 THEN 'DocumentosEnProceso'
                    WHEN 10 THEN 'DocumentosEnProceso'
                    WHEN 11 THEN 'DocumentosEnProceso'
                    --WHEN 8 THEN 'Rechazados'
                    --WHEN 6 THEN 'DocumentosFirmados'
                END AS Estado
                ,COUNT(DISTINCT C.idDocumento) AS Total		
            FROM [Contratos] C
            INNER JOIN Plantillas PL			ON PL.idPlantilla = C.idPlantilla
            INNER JOIN ContratosEstados CE		ON CE.idEstado = C.idEstado
            INNER JOIN Empresas E				ON E.RutEmpresa = C.RutEmpresa
            LEFT  JOIN WorkflowEstadoProcesos WEP	ON C.idWF = idWorkflow AND C.idEstado =  WEP.idEstadoWF
            INNER JOIN ContratoFirmantes CF	ON CF.idDocumento = C.idDocumento AND CF.RutFirmante = @usuarioid AND CF.idEstado = C.idEstado
            WHERE C.Eliminado = 0
            AND CE.idEstado NOT IN (1, 6, 8)
            GROUP BY CF.RutFirmante, CE.idEstado
            UNION

			SELECT	
                CF.RutFirmante
                ,CASE EW.idEstado
                    --WHEN 2 THEN 'DocumentosEnProceso'
                    --WHEN 3 THEN 'DocumentosEnProceso'
                    --WHEN 9 THEN 'DocumentosEnProceso'
                    --WHEN 10 THEN 'DocumentosEnProceso'
                    --WHEN 8 THEN 'Rechazados'
                    WHEN 6 THEN 'DocumentosFirmados'
                END AS Estado
                ,COUNT(DISTINCT C.idDocumento) AS Total		
            FROM [Contratos] C			
            INNER JOIN Plantillas	PL				ON PL.idPlantilla = C.idPlantilla
            INNER JOIN ContratosEstados EW			ON EW.idEstado = C.idEstado	
            INNER JOIN ContratoDatosVariables CDV	ON CDV.idDocumento = C.idDocumento
            INNER JOIN Empresas E					ON E.RutEmpresa = C.RutEmpresa
            --INNER JOIN accesoxusuarioccosto     ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.centrocostoid = CDV.CentroCosto AND ACC.usuarioid = @usuarioid
            --INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.centrocostoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid	AND ACC.lugarpagoid = CDV.lugarpagoid
            --INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.lugarpagoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid AND ACC.centrocostoid = CDV.lugarpagoid
            --INNER JOIN tiposdocumentosxperfil TAPP	ON TAPP.idtipodoc = PL.idTipoDoc AND TAPP.tipousuarioid = @ptipousuarioid	
            INNER JOIN ContratoFirmantes CF ON C.idDocumento = CF.idDocumento AND CF.RutFirmante = @usuarioid
            WHERE C.Eliminado = 0
            AND EW.idEstado NOT IN (1, 8, 2, 3, 9, 10, 11)
            GROUP BY CF.RutFirmante, EW.idEstado
        )				
        Select 
            'Panel' as Total, 
            isnull(DocumentosEnProceso,0) as DocumentosEnProceso,
            isnull(DocumentosFirmados,0) as DocumentosFirmados ,
            isnull([TotalDocumentos],0) as [TotalDocumentos], 
            isnull ([Rechazados],0) as [DocumentosRechazados]
        from (
            select 
                RutFirmante, 
                Estado as Descripcion ,
                Sum(Total) as Total  
            from DocumentosTabla
            Group By RutFirmante, Estado 
            UNION ALL
            select @usuarioid,'TotalDocumentos', (SELECT SUM(total) FROM DocumentosTabla) as Total
        ) as SoruceTable
				PIVOT
				( Sum(total)
				For Descripcion in (DocumentosEnProceso,DocumentosFirmados,[TotalDocumentos], Rechazados)
				) AS PivotTable; 
	
    -- exec sp_panel '13559051-7'                         
	--		Select 'Panel' as Total, DocumentosEnProceso,DocumentosFirmados,[TotalDocumentos]
	--From
	--(select Descripcion, Total from Panel )as SoruceTable
	--PIVOT
	--( Sum(total)
	--For Descripcion in (DocumentosEnProceso,DocumentosFirmados,[TotalDocumentos])
	--) AS PivotTable;                             
                             
      RETURN
END
GO
