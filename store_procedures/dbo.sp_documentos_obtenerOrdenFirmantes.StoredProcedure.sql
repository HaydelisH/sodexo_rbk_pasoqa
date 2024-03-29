USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerOrdenFirmantes]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 13/07/2018
-- Descripcion: obtener los firmantes de un Documento
-- Ejemplo:sp_documentos_obtenerOrdenFirmante 'xxxxxxxxxx-1'
-- =============================================

CREATE PROCEDURE [dbo].[sp_documentos_obtenerOrdenFirmantes]
	@idDocumento INT,
	@personaid VARCHAR(10)

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
			personas.personaid,
			personas.nombre,
			personas.appaterno,
			ContratoFirmantes.Orden
		FROM 
			ContratoFirmantes
		INNER JOIN 
			personas
		ON 
			ContratoFirmantes.RutFirmante = personas.personaid
		WHERE 
			personas.personaid = @personaid
			AND
			ContratoFirmantes.idDocumento = @idDocumento
		ORDER BY 
			ContratoFirmantes.Orden ASC
	END 
END
GO
