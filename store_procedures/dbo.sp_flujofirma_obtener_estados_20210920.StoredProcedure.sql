USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_flujofirma_obtener_estados_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Cristian Soto
-- Creado el: 10/06/2018
-- Descripcion: Obtener estados del flujo de firma por id
-- Ejemplo:exec sp_flujofirma_obtener_estados 1
-- =============================================

CREATE PROCEDURE [dbo].[sp_flujofirma_obtener_estados_20210920]
@pidworkflow INT

AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	SELECT 
	idworkflow,
	idestadowf,
	ContratosEstados.Descripcion as nombre,
	Orden AS orden,
	diasmax
	FROM WorkflowEstadoProcesos		
	LEFT JOIN ContratosEstados ON WorkflowEstadoProcesos.idEstadoWF = ContratosEstados.idEstado
	WHERE idWorkflow = @pidworkflow
    ORDER BY WorkflowEstadoProcesos.Orden
	

END
GO
