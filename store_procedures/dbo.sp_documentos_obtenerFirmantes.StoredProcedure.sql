USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerFirmantes]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 05/07/2018
-- Descripcion: obtener los firmantes de un Documento
-- Ejemplo:sp_documentos_obtenerFirmantes 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerFirmantes]
	@idContrato INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
    BEGIN
		SELECT 
			UPPER(p.personaid) As personaid,
			p.nombre,
			p.appaterno,
			CF.Orden,
			CF.idEstado,
			CE.Descripcion As Nombre,
			CF.Firmado,
			CA.idCargo,
			CA.Descripcion As Cargo,
			u.idFirma,
			FT.Descripcion As TipoFirma,
			C.idWF 

		FROM Contratos C 
		INNER JOIN ContratoFirmantes CF ON C.idDocumento = CF.idDocumento
		INNER JOIN ContratosEstados CE ON CF.idEstado = CE.idEstado
		INNER JOIN Plantillas PL ON C.idPlantilla = PL.idPlantilla
		LEFT JOIN Firmantes F ON CF.RutFirmante =  F.RutUsuario AND C.RutEmpresa = F.RutEmpresa 
		INNER JOIN personas p ON CF.RutFirmante = p.personaid 
		LEFT JOIN usuarios u ON p.personaid = u.usuarioid 
		LEFT JOIN Firmas FT ON u.idFirma = FT.idFirma
		LEFT JOIN Cargos CA ON F.idCargo = CA.idCargo 
		WHERE 
			C.idDocumento = @idContrato
		ORDER BY 
			CF.Orden ASC
	END 
END
GO
