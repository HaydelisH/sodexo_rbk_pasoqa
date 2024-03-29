USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_tiposdocumentos_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_rl_tiposdocumentos_listado]

AS
BEGIN

	SELECT TipoDocumentos.*
    FROM TipoDocumentos
    INNER JOIN Plantillas ON Plantillas.idTipoDoc = TipoDocumentos.idTipoDoc
		AND TipoDocumentos.Eliminado = 0 
		AND Plantillas.Eliminado = 0
    INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = Plantillas.idWF
		AND WorkflowProceso.tipoWF = 1
    GROUP BY TipoDocumentos.idTipoDoc, TipoDocumentos.NombreTipoDoc, TipoDocumentos.Eliminado
    ORDER BY TipoDocumentos.NombreTipoDoc

END
GO
