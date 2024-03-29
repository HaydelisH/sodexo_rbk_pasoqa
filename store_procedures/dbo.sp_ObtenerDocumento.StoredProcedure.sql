USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtenerDocumento]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		RC
-- Create date: 20180926
-- Description:	Obtiene Documento por Numero de Contrato
-- =============================================
CREATE PROCEDURE [dbo].[sp_ObtenerDocumento] 
	@NumeroContrato int
AS
BEGIN	
	SET NOCOUNT ON;

	SELECT [idDocumento] as NumeroContrato,[documento] as Documento From Documentos
	where [idDocumento] = @NumeroContrato
END


/****** Object:  StoredProcedure [dbo].[sp_ObtieneDatosCorreo]    Script Date: 10/02/2018 11:05:51 ******/
SET ANSI_NULLS ON
GO
