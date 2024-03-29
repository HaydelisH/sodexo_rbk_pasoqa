USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadosworkflow_listado_sin_rechazo]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
CREATE PROCEDURE [dbo].[sp_estadosworkflow_listado_sin_rechazo]
@pidworkflow INT
AS
BEGIN
	

	SELECT 
	idestado,
	ContratosEstados.Descripcion as nombre
	FROM WorkflowEstadoProcesos		
	LEFT JOIN ContratosEstados ON WorkflowEstadoProcesos.idEstadoWF = ContratosEstados.idEstado
	WHERE idWorkflow = @pidworkflow
	
	UNION
	SELECT 
	1 as idestado,
	'Generado en espera de aprobacion' AS nombre
	
	UNION
	SELECT
	6 as idestado,
	'Firmado' AS nombre
	
	ORDER BY idestado
	
	
                           
    RETURN                                                             

END
GO
