USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadosworkflow_listado_limitado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 27-10-2018
-- Descripcion: Listado de estados limitado
-- Ejemplo:exec sp_estadosworkflow_listado_limitado
-- =============================================
CREATE PROCEDURE [dbo].[sp_estadosworkflow_listado_limitado]
	@pidworkflow	INT,	--id del flujo
	@pidestado		INT		--id del estado en el que esta
AS
BEGIN

	DECLARE @orden INT;
	DECLARE @sig INT;
	DECLARE @res INT;
		
	SELECT 
		@orden = WP.Orden
	FROM WorkflowEstadoProcesos WP
		INNER JOIN ContratosEstados EW ON WP.idEstadoWF = EW.idEstado
	WHERE 
	    WP.idWorkflow = @pidworkflow  AND EW.idEstado = @pidestado
	    
	SET @sig = @orden + 1 
	
	--Cuento cuantos registros trae
	
	SELECT 
		@res = COUNT(EW.idEstado)
	FROM WorkflowEstadoProcesos WP
		INNER JOIN ContratosEstados EW ON WP.idEstadoWF = EW.idEstado
	WHERE 
	    WP.idWorkflow = @pidworkflow  AND WP.Orden BETWEEN @orden AND @sig 
	    
	--Si el resultado el 2
	IF ( @res = 2 ) 
		BEGIN
			SELECT 
				EW.idEstado As idestado,
				EW.Descripcion As nombre,
				WP.Orden
			FROM WorkflowEstadoProcesos WP
				INNER JOIN ContratosEstados EW ON WP.idEstadoWF = EW.idEstado
			WHERE 
				WP.idWorkflow = @pidworkflow  AND WP.Orden BETWEEN @orden AND @sig 
				ORDER BY WP.Orden ASC
		END
	ELSE
		BEGIN
			SELECT 
				EW.idEstado As idestado,
				EW.Descripcion As nombre,
				WP.Orden
			FROM WorkflowEstadoProcesos WP
				INNER JOIN ContratosEstados EW ON WP.idEstadoWF = EW.idEstado
			WHERE 
				WP.idWorkflow = @pidworkflow  AND WP.Orden BETWEEN @orden AND @sig 
			UNION
				SELECT 6 as idestado,'Firmado' AS nombre, 10 As Orden
			ORDER BY WP.Orden ASC
		END
    RETURN                                                             
END
GO
