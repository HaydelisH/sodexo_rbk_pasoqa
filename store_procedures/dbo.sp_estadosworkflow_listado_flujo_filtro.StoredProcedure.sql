USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadosworkflow_listado_flujo_filtro]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 11/06/2018
-- Descripcion: Obtener estados del flujo que no estan seleccionados por id de flujo
-- Ejemplo:exec sp_estadosworkflow_listado_flujo 1
-- =============================================

CREATE PROCEDURE [dbo].[sp_estadosworkflow_listado_flujo_filtro]
@pidworkflow INT
AS
BEGIN

	SELECT 
	ContratosEstados.idEstado AS idestado,
	ContratosEstados.Descripcion as nombre
	FROM  ContratosEstados
	LEFT JOIN   WorkflowEstadoProcesos ON ContratosEstados.idEstado = WorkflowEstadoProcesos.idEstadoWF  
	AND  WorkflowEstadoProcesos.idWorkflow = @pidworkflow
	WHERE WorkflowEstadoProcesos.idEstadoWF IS NULL
	and ContratosEstados.VerWF = 1
	ORDER BY WorkflowEstadoProcesos.Orden
                    
    RETURN                                                             

END
GO
