USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposdocumentos_listado_20210920]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_tiposdocumentos_listado_20210920]

AS
BEGIN
	
    SELECT idTipoDoc, NombreTipoDoc 
    FROM TipoDocumentos
	WHERE Eliminado=0
                         
    RETURN                                                             

END
GO
