USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_modelosContratos_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Haysdelis Hernandez
-- Create date: 009-08-2018
-- Description:	sp_modelosContratos_obtener
-- =============================================
CREATE PROCEDURE [dbo].[sp_modelosContratos_obtener]
	@idMC INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    -- Insert statements for procedure here
	SELECT idMC, DescripcionMC FROM ModelosContratos WHERE (idMC = @idMC AND Eliminado = 0)
END
GO
