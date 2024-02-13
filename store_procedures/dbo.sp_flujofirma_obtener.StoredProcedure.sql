USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 10/06/2018
-- Descripcion: Obtener flujo de firma por id
-- Ejemplo:exec sp_flujofirma_obtener 1
-- =============================================

CREATE PROCEDURE [dbo].[sp_flujofirma_obtener]
	@pidwf INT

AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	SELECT 
	idwf,
	nombrewf,
	(SELECT MAX(idestadowf) FROM WorkflowEstadoProcesos WHERE idWorkflow = @pidwf) AS idestadowfmax
	FROM WorkflowProceso 
	WHERE idWF = @pidwf
	AND Eliminado = 0

END
GO
