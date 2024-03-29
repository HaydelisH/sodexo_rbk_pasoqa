USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_agregarFirmantes]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04/07/2018
-- Descripcion: Agrega un firmante de un Contrato
-- Ejemplo:exec sp_documentos_agregarFirmantes 'nombre','pdf'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_agregarFirmantes]
	@idContrato INT,
	@RutEmpresa VARCHAR(10),
	@RutFirmante VARCHAR(10),
	@idEstado INT,
	@Orden INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
			
	INSERT INTO ContratoFirmantes(RutEmpresa, idDocumento,RutFirmante,idEstado,Firmado,FechaFirma,DiasTardoFirma,Orden)
	VALUES (@RutEmpresa, @idContrato,@RutFirmante,@idEstado,0,'',0,@Orden)

	RETURN
   
END
GO
