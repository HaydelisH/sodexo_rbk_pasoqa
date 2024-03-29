USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuarioEmpresa_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Haydelis Hernández
-- Create date: 05-07-2019
-- Description:	Obtener de fichasDatosImportacion
-- Ejemplo: sp_usuarioEmpresa_obtener 2
-- =============================================
CREATE PROCEDURE [dbo].[sp_usuarioEmpresa_obtener]

	@pusuarioid varchar(10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	SELECT 
		usuarios.RutEmpresa,
		Empresas.RazonSocial,
		usuarios.centrocostoid,
		centroscosto.nombrecentrocosto
	FROM
		usuarios
    INNER JOIN Empresas ON Empresas.RutEmpresa = usuarios.RutEmpresa
	INNER JOIN centroscosto ON centroscosto.centrocostoid = usuarios.centrocostoid
	WHERE 
		usuarios.usuarioid = @pusuarioid
END
GO
