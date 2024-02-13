USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposdocumentos_listado_TipoGestor]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Alexander Montenegro
-- Creado el: 27/09/2021

CREATE PROCEDURE [dbo].[sp_tiposdocumentos_listado_TipoGestor]

AS
BEGIN

	--SELECT TipoDocumentos.*
 --   FROM TipoDocumentos
 --   INNER JOIN Plantillas ON Plantillas.idTipoDoc = TipoDocumentos.idTipoDoc
	--	AND TipoDocumentos.Eliminado = 0 
	--	AND Plantillas.Eliminado = 0
 --   INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = Plantillas.idWF
	--	AND WorkflowProceso.tipoWF IS NULL
 --   GROUP BY TipoDocumentos.idTipoDoc, TipoDocumentos.NombreTipoDoc, TipoDocumentos.Eliminado
 --   ORDER BY TipoDocumentos.NombreTipoDoc


	SELECT TipoGestor.idTipoGestor as idTipoDoc, TipoGestor.Nombre as NombreTipoDoc
    FROM TipoGestor
	INNER JOIN Plantillas  ON Plantillas.idTipoGestor = TipoGestor.idTipoGestor
    INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = Plantillas.idWF
		AND WorkflowProceso.tipoWF IS NULL
    GROUP BY TipoGestor.idTipoGestor, TipoGestor.Nombre  
    ORDER BY TipoGestor.Nombre  

END
GO
