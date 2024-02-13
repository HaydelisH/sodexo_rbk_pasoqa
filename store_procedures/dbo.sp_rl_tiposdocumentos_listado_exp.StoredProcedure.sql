USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_tiposdocumentos_listado_exp]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Modificado por: Gdiaz 11/01/2021

CREATE PROCEDURE [dbo].[sp_rl_tiposdocumentos_listado_exp]

AS
BEGIN
	
    SELECT TD.idTipoDoc, TD.NombreTipoDoc 
    FROM TipoDocumentos as TD
	WHERE td.Eliminado=0
    AND EXISTS (SELECT PLA.IdTipoDoc FROM Plantillas as PLA WHERE Pla.idTipoDoc = TD.idTipoDoc AND PLA.tipogeneracion = 1 AND PLA.Eliminado = 0)
	
    RETURN                                                             

END
GO
