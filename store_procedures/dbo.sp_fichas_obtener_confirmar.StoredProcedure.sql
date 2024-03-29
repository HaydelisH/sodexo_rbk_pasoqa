USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_obtener_confirmar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_fichas_obtener_confirmar]
	@fichaid            INT
AS	
BEGIN
	SET NOCOUNT ON;
	
	SELECT 
		DISTINCT COUNT(fd.fichaid)As total
	FROM 
		fichasdocumentos fd 
		INNER JOIN EnvioCorreos ec ON ec.documentoid = fd.documentoid
    WHERE 
		fd.fichaid = @fichaid AND ec.CodCorreo = 2
		
	RETURN;
END
GO
