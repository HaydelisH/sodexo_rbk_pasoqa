USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadosworkflow_listado_filtro]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
CREATE PROCEDURE [dbo].[sp_estadosworkflow_listado_filtro]

AS
BEGIN
	
	SELECT 
		idEstado As idestado,
		Descripcion As nombre
	FROM ContratosEstados
	WHERE 
		VerWF = 1
                         
    RETURN                                                             

END
GO
