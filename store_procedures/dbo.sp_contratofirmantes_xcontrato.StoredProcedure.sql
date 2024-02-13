USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_contratofirmantes_xcontrato]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 24/07/2018
-- Descripcion: obtener firmantes de un Documento
-- Ejemplo:sp_contratofirmantes_xcontrato 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_contratofirmantes_xcontrato]
	@idContrato INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	SET NOCOUNT ON;

	SELECT  
	UPPER(ContratoFirmantes.RutFirmante) As RutFirmante,
	personas.nombre + ' ' +  personas.appaterno + ' ' + personas.apmaterno AS nombre,
	ContratoFirmantes.idEstado,
	CASE CONVERT(VARCHAR(10),ContratoFirmantes.FechaFirma,105)
	WHEN '01-01-1900' THEN ''
	ELSE CONVERT(VARCHAR(10),ContratoFirmantes.FechaFirma,105) + ' ' + CONVERT(VARCHAR(10),ContratoFirmantes.FechaFirma,108)
	END   AS FechaFirma,
	ContratoFirmantes.Orden
	FROM ContratoFirmantes 
	LEFT JOIN personas			ON ContratoFirmantes.RutFirmante	= personas.personaid
	LEFT JOIN Contratos			ON ContratoFirmantes.idDocumento = Contratos.idDocumento
	WHERE ContratoFirmantes.idDocumento = @idContrato AND Contratos.Eliminado = 0
	ORDER BY ContratoFirmantes.Orden
	
END
GO
