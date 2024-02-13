USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_estadosworkflow_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO



CREATE PROCEDURE [dbo].[sp_rl_estadosworkflow_listado]

AS
BEGIN
	
	SELECT 
		idEstado As idestado,
		Descripcion As nombre,
		idEstado,
		Descripcion
	FROM ContratosEstados
	WHERE 
		VerWF_RL = 1
                         
    RETURN

END


GO
