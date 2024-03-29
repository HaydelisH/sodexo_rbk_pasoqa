USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_contarClausulas]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Haydelis Hernandez
-- Create date: 31-07-2018
-- Description:	sp_plantillas_contarClausulas 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_contarClausulas]
	@idPlantilla INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    -- Insert statements for procedure here
	SELECT COUNT(idClausula) AS total FROM PlantillasClausulas WHERE idPlantilla = @idPlantilla
	
END
GO
