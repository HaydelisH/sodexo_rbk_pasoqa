USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_modelosContratos_listadoxplantilla]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Haysdelis Hernandez
-- Create date: 03-08-2018
-- Description:	sp_modelosContratos_listadoxplantilla
-- =============================================
CREATE PROCEDURE [dbo].[sp_modelosContratos_listadoxplantilla]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    -- Insert statements for procedure here
	SELECT idMC, DescripcionMC FROM ModelosContratos WHERE Eliminado = 0 AND idMC <> 6
END
GO
