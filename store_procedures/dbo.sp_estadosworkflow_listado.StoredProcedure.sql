USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadosworkflow_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
CREATE PROCEDURE [dbo].[sp_estadosworkflow_listado]

AS
BEGIN
	
	SELECT 
	idestado,
	Descripcion as nombre
	FROM ContratosEstados 
                         
    RETURN                                                             

END
GO
