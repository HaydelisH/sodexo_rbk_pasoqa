USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ConfiguracionCorreoApp]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		RC
-- Create date: 20180307
-- Description:	Obtiene la configuracion de Correo para una empresa espeficica
-- =============================================
CREATE PROCEDURE [dbo].[sp_ConfiguracionCorreoApp] 
AS
BEGIN
	SET NOCOUNT ON;

	   SELECT TOP 1	[ContratoTipoXEmpresa]
			   ,[ConfigCorreoMultiEmpresa]
			   ,VariableEncriptada
	  FROM [dbo].ConfiguracionCorreoApp
	  
END
GO
