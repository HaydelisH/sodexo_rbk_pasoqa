USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ArchivosAdjuntosXTipo]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


-- =============================================
-- Author:		RC
-- Create date: 20170214
-- Description:	Obtiene configuracion para el envio de correo
-- [sp_ArchivosAdjuntosXTipo] 10
-- =============================================
CREATE PROCEDURE [dbo].[sp_ArchivosAdjuntosXTipo] 
@Tipo int,
@RutEmpresa varchar(12) = '1-9'
AS
BEGIN
	SET NOCOUNT ON;

	SELECT [Tipo]
		  ,[NombreArchivo]
	FROM [CorreoAdjuntos]
	WHERE TIPO = @Tipo
	and RutEmpresa = @RutEmpresa  
  
  
END
GO
