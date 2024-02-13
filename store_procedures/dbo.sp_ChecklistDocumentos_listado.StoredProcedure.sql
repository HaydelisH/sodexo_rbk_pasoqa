USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ChecklistDocumentos_listado]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Haydelis Hernández
-- Create date: 05-07-2019
-- Description:	Listado de ChecklistDocumentos
-- Ejemplo: sp_ChecklistDocumentos_listado 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_ChecklistDocumentos_listado]
	
	@pTipoMovimiento INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	SELECT 
		TM.idTipoMovimiento,
		TM.Descripcion,
		TG.idTipoGestor,
		TG.Nombre,
		CD.Obligatorio
	FROM 
		ChecklistDocumentos CD
	INNER JOIN TipoMovimiento TM ON CD.idTipoMovimiento = TM.idTipoMovimiento
	INNER JOIN TipoGestor TG ON CD.idTipoGestor = TG.idTipoGestor
	WHERE
		CD.idTipoMovimiento = @pTipoMovimiento
	ORDER BY 
		CD.Obligatorio 
	DESC

END
GO
