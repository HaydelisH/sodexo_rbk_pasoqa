USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tipodocumentos_obtenerNombreTipoDoc]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_tipodocumentos_obtenerNombreTipoDoc]
	@idTipoDoc INT
AS
BEGIN
	
     SELECT NombreTipoDoc FROM TipoDocumentos
	 WHERE idTipoDoc = @idTipoDoc AND Eliminado=0
                         
    RETURN                                                             

END
GO
