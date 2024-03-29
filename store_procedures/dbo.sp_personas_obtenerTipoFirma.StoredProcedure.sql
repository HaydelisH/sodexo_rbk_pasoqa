USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_personas_obtenerTipoFirma]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Creado el: 17/08/2018
-- Descripcion: Consulta el tipo de Firma del Firmante
-- =============================================
CREATE PROCEDURE [dbo].[sp_personas_obtenerTipoFirma]
      @personaid	VARCHAR (10),
      @idcontrato	INT
AS   
BEGIN

	SELECT 
		U.idFirma,
		F.Descripcion,
		CF.Orden,
		CF.RutEmpresa,
		CF.idDocumento,
		CF.Firmado,
		UPPER(cf.RutFirmante) As RutUsuario
	FROM 
		ContratoFirmantes CF 
	INNER JOIN Usuarios U ON U.usuarioid = CF.RutFirmante
	INNER JOIN Firmas F ON F.idFirma = U.idFirma
	WHERE 
		CF.RutFirmante = @personaid
		AND 
		CF.idDocumento = @idcontrato
		AND 
		CF.Firmado = 0                     
	ORDER BY CF.Orden ASC
END
GO
