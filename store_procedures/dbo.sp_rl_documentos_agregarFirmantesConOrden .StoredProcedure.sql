USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_documentos_agregarFirmantesConOrden ]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 22/10/2019
-- Descripcion: Agrega un firmante de un Contrato con orden interno
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_documentos_agregarFirmantesConOrden 'nombre','pdf'
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_documentos_agregarFirmantesConOrden ]
	@idContrato INT,
	@RutEmpresa VARCHAR(10),
	@RutFirmante VARCHAR(10),
	@idEstado INT,
	@Orden INT,
	@OrdenMismoEstado INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
			
	INSERT INTO ContratoFirmantes(RutEmpresa, idDocumento,RutFirmante,idEstado,Firmado,FechaFirma,DiasTardoFirma,Orden,OrdenMismoEstado)
	VALUES (@RutEmpresa, @idContrato,@RutFirmante,@idEstado,0,'',0,@Orden, @OrdenMismoEstado)

	RETURN
   
END
GO
